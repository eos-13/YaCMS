<?php

class view_bdd extends common
{
    private $templ;
    public function run()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "comment")
        {
            $this->set_comment($_REQUEST);
        }
        load_alternative_class('class/template.class.php');
        if ($this->page->get_model_refid() > 0)
        {
            $fiche = false;
            $this->templ = new template($this->db);
            $this->templ->fetch($this->page->get_model_refid() );
            $this->load_page_properties();

            if ($this->templ->get_path())
            //Le template est html
                $fiche = $this->template_html();
            //Le template est en bdd
            elseif ($this->templ->get_content())
                $fiche = $this->template_bdd();
            if ($fiche)
            {
                echo $fiche;
            } else {
                include_once 'errors/500.php';
                exit;
            }
        } else {
            return false;
        }
    }
    private function template_html()
    {
        $this->set_template_file(make_path('template',$this->templ->get_path(),'html'));
        $this->set_main($this->page->get_content_with_section());
        $this->set_title($this->page->get_title());
        if ($this->page->get_form_refid() > 0)
        {
            $this->set_extra_render('form', $this->page->get_form()->get_content());
            $this->set_css($this->page->get_form()->get_restit_css());
            $this->set_js($this->page->get_form()->get_restit_js());
        }
        if ($this->page->get_extra_params() && $this->page->get_extra_params()."x" != "x")
        {
            $data = json_decode($this->page->get_extra_params());
            foreach($data as $key=>$val)
            {
                if ($key == "image")
                {
                    load_alternative_class('class/data_file.class.php');
                    $df = new data_file($this->db);
                    $a = $df->fetch_by_file_path($val);
                    foreach($a as $key1 =>$val1)
                    {
                        $this->set_extra_render('image_'.$val1->data_name, $val1->data_value);
                    }
                }
                if ($key == "thumb")
                {
                    load_alternative_class('class/data_file.class.php');
                    $df = new data_file($this->db);
                    $a = $df->fetch_by_file_path($val);
                    foreach($a as $key1 =>$val1)
                    {
                        $this->set_extra_render('thumb_'.$val1->data_name, $val1->data_value);
                    }
                }
                if ($key == "image_thumb")
                {
                    load_alternative_class('class/data_file.class.php');
                    $df = new data_file($this->db);
                    $a = $df->fetch_by_file_path($val);
                    foreach($a as $key1 =>$val1)
                    {
                        $this->set_extra_render('image_thumb_'.$val1->data_name, $val1->data_value);
                    }
                }
                if ($key == "file")
                {
                    load_alternative_class('class/data_file.class.php');
                    $df = new data_file($this->db);
                    $a = $df->fetch_by_file_path($val);
                    foreach($a as $key1 =>$val1)
                    {
                        $this->set_extra_render('file_'.$val1->data_name, $val1->data_value);
                    }
                }
                if (preg_match('/_use_cache$/',$val)) continue;
                if (preg_match('/_cache_id$/',$val)) continue;
                if ($val == "load") continue; ///Erreur d'enregistrement ?
                $cache = $key."_use_cache";
                if (isset($data->$cache) && $data->$cache == 1)
                {
                    $tmp = $key."_cache_id";
                    $cache_id = $data->$tmp;
                    load_alternative_class('class/cache.class.php');
                    $c = new cache($this->db);
                    $c->fetch($cache_id);
                    $this->set_extra_render($key, json_decode($c->get_cached_data()));
                } elseif (preg_match('/_path$/',$key)) {
                    $regex = false;
                    if (preg_match('/^image/',$key))
                    {
                        $regex = "^.+\.(png|jpeg|gif|jpg)$";
                    } elseif (preg_match("/^video/",$key))
                    {
                        $regex = "^.+\.(mp4|ogg)$";
                    }
                    $Directory = new RecursiveDirectoryIterator($conf->main_document_root."/files/".$val);

                    $Iterator = new RecursiveIteratorIterator($Directory);
                    $files = false;
                    foreach ($Iterator as $info)
                    {
                        if (preg_match('/^\./',$info->getFilename())) continue;

                        if ($regex && preg_match('/'.$regex.'/i',$info))
                        {
                            $f = $info->getPathname();
                            $l = strlen($conf->main_document_root."/files/");
                            $f = substr($f, $l);
                            if (preg_match('/^\./',$f)) continue;
                            $a = array( 'path' => $f,'name' =>$info->getFilename());
                            load_alternative_class('class/data_file.class.php');
                            $df = new data_file($this->db);
                            $ar = $df->fetch_by_file_path($f);
                            if (is_array($ar) && count($ar) > 0)
                                foreach($a as $key1 =>$val1)
                                {
                                    $a[$val1->data_name] = $val1->data_value;
                                }
                            $files[] = $a;
                        } else if(!$regex) {
                            $f = $info->getPathname();
                            $l = strlen($conf->main_document_root."/files/");
                            $f = substr($f, $l);
                            if (preg_match('/^\./',$f)) continue;
                            $a = array( 'path' => $f,'name' =>$info->getFilename());
                            load_alternative_class('class/data_file.class.php');
                            $df = new data_file($this->db);
                            $ar = $df->fetch_by_file_path($f);
                            if (is_array($ar) && count($ar) > 0)
                                foreach($ar as $key1 =>$val1)
                                {
                                    $a[$val1->data_name] = $val1->data_value;
                                }
                            $files[] = $a;
                        }
                    }
                    if ($files) $val = $files;
                    $this->set_extra_render($key, $val);
                } else {
                    $this->set_extra_render($key, $val);
                }
            }
        }

        return $this->gen_page();
    }
    private function template_bdd()
    {
        global $conf;
        $array = array();
        $tpl_fake_name = 'tmp.html';
        $array[$tpl_fake_name] = $this->templ->get_content();
        $array = $this->extends_loader($array);
        $this->set_loader(hash_loader($array));
        $this->set_template_file($tpl_fake_name);
        $this->set_main($this->page->get_content());
        $this->set_section($this->page->get_section());
        $this->set_title($this->page->get_title());
        $this->set_permit_comment($this->page->get_permit_comment());
        $this->set_comment_page_id($this->page->id);
        if ($this->page->get_form_refid() > 0)
        {
            $this->set_extra_render('form', $this->page->get_form()->get_content());
            $this->set_css($this->page->get_form()->get_restit_css());
            $this->set_js($this->page->get_form()->get_restit_js());
        }
        if ($this->page->get_extra_params() && $this->page->get_extra_params()."x" != "x")
        {
            $data = json_decode($this->page->get_extra_params());
            foreach($data as $key=>$val)
            {
                if ($key == "image")
                {
                    load_alternative_class('class/data_file.class.php');
                    $df = new data_file($this->db);
                    $a = $df->fetch_by_file_path($val);
                    foreach($a as $key1 =>$val1)
                    {
                        $this->set_extra_render('image_'.$val1->data_name, $val1->data_value);
                    }
                }
                if ($key == "thumb")
                {
                    load_alternative_class('class/data_file.class.php');
                    $df = new data_file($this->db);
                    $a = $df->fetch_by_file_path($val);
                    foreach($a as $key1 =>$val1)
                    {
                        $this->set_extra_render('thumb_'.$val1->data_name, $val1->data_value);
                    }
                }
                if ($key == "image_thumb")
                {
                    load_alternative_class('class/data_file.class.php');
                    $df = new data_file($this->db);
                    $a = $df->fetch_by_file_path($val);
                    foreach($a as $key1 =>$val1)
                    {
                        $this->set_extra_render('image_thumb_'.$val1->data_name, $val1->data_value);
                    }
                }
                if ($key == "file")
                {
                    load_alternative_class('class/data_file.class.php');
                    $df = new data_file($this->db);
                    $a = $df->fetch_by_file_path($val);
                    foreach($a as $key1 =>$val1)
                    {
                        $this->set_extra_render('file_'.$val1->data_name, $val1->data_value);
                    }
                }
                if (preg_match('/_use_cache$/',$val)) continue;
                if (preg_match('/_cache_id$/',$val)) continue;
                if ($val == "load") continue; ///Erreur d'enregistrement ?
                $cache = $key."_use_cache";
                if (isset($data->$cache) && $data->$cache == 1)
                {
                    $tmp = $key."_cache_id";
                    $cache_id = $data->$tmp;
                    load_alternative_class('class/cache.class.php');
                    $c = new cache($this->db);
                    $c->fetch($cache_id);
                    $this->set_extra_render($key, json_decode($c->get_cached_data()));
                } elseif (preg_match('/_path$/',$key)) {
                    $regex = false;
                    if (preg_match('/^image/',$key))
                    {
                        $regex = "^.+\.(png|jpeg|gif|jpg)$";
                    } elseif (preg_match("/^video/",$key))
                    {
                        $regex = "^.+\.(mp4|ogg)$";
                    }
                    $Directory = new RecursiveDirectoryIterator($conf->main_document_root."/files/".$val);

                    $Iterator = new RecursiveIteratorIterator($Directory);
                    $files = false;
                    foreach ($Iterator as $info)
                    {
                        if (preg_match('/^\./',$info->getFilename())) continue;
                        if (preg_match('/\/\.tmb\//',$info->getPathName ())) continue;
                        if ($regex && preg_match('/'.$regex.'/i',$info))
                        {
                            $f = $info->getPathname();
                            $l = strlen($conf->main_document_root."/files/");
                            $f = substr($f, $l);
                            if (preg_match('/^\./',$f)) continue;
                            $a = array( 'path' => $f,'name' =>$info->getFilename());
                            load_alternative_class('class/data_file.class.php');
                            $df = new data_file($this->db);
                            $ar = $df->fetch_by_file_path($f);
                            if (is_array($ar) && count($ar) > 0)
                            foreach($a as $key1 =>$val1)
                            {
                                $a[$val1->data_name] = $val1->data_value;
                            }
                            $files[] = $a;
                        } else if (!$regex) {

                            $f = $info->getPathname();
                            $l = strlen($conf->main_document_root."/files/");
                            $f = substr($f, $l);
                            if (preg_match('/^\./',$f)) continue;
                            $a = array( 'path' => $f,'name' =>$info->getFilename());
                            load_alternative_class('class/data_file.class.php');
                            $df = new data_file($this->db);
                            $ar = $df->fetch_by_file_path($f);
                            if (is_array($ar) && count($ar) > 0)
                            foreach($ar as $key1 =>$val1)
                            {
                                $a[$val1->data_name] = $val1->data_value;
                            }
                            $files[] = $a;
                        }
                    }
                    if ($files) $val = $files;
                    $this->set_extra_render($key, $val);
                } else {
                    $this->set_extra_render($key, $val);
                }
            }
        }
        return $this->gen_page();
    }
    private function extends_loader($array)
    {
        if (preg_match('/\{% extends [\W]?([\w_\-\.]*)[\W]? %\}/',$this->templ->get_content(),$arrMatch))
        {
            $arrMatch[1]=preg_replace('/\.html$/','',$arrMatch[1]);
            $file = make_path('template',$arrMatch[1],"html");
            if ($file){
                $array[$arrMatch[1].'.html'] = file_get_contents($file);
            } else {
                //On le trouve en bdd
                $requete = "SELECT *
                              FROM model
                             WHERE name = '".addslashes($arrMatch[1])."'";
                $sql = $this->db->query($requete);
                $res = $this->db->fetch_object($sql);
                //2 cas Path ou content
                if ($res->path."x" != "x")
                {
                    $file=make_path('template',$res->path,'html');
                    if ($file)
                    {
                        $array[$arrMatch[1].'.html'] = file_get_contents($file);
                    } else {
                        include_once 'errors/500.php';
                        exit;
                    }
                } elseif ($res->content."x" != "x") {
                    $array[$arrMatch[1].'.html'] = $res->content;
                } else {
                    include_once 'errors/500.php';
                    exit;
                }
            }
        }
        return $array;
    }
}
?>
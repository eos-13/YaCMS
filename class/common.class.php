<?php
class common
{
    private $main = "";
    private $section = array();
    private $title;
    private $loader = false;
    private $extra_render=false;
    private $tpl_file;
    private $cache = 'file';
    private $permit_comment = false;

    protected $db;
    protected $req;
    protected $page;
    protected $search_mode=false;
    protected $comment_response = false;
    protected $needrights=array();

    public $menu = "";
    public $css = array();
    public $css_cache = array();
    public $js = array();
    public $js_cache = array();
    public $js_external = array();
    public $css_code = false;
    public $js_code = array();
    public $meta = false;
    public $no_use_cache;
    public $breadcrumbs=false;
    public $log;
    public $social = false;

    public function __construct($db,$req,$page=false,$run=true,$search_mode=false)
    {
        global $log;
        $this->log = $log;
        $this->no_use_cache=false;
        $this->db = $db;
        $this->get_menu();
        $this->req=$req;
        $this->search_mode = $search_mode;
        if ($this->search_mode) $this->no_use_cache = true;
        $this->title = $this->req;
        if ($page)
            $this->page = $page;
        else {
            load_alternative_class('class/page.class.php');
            $p = new page($this->db);
            $p->fetch_by_url($req);
            if ($p->get_id()>0)
                $this->page = $p;

        }
        if (isset($this->page) && isset ($this->page->use_cache) && $this->page->use_cache != 1)
            $this->no_use_cache = true;
        $this->get_breadcrumbs();

        global $conf;
        $this->set_template_file(make_path('template',$this->req,'html'));

        if ($conf->site_js && $conf->site_js."x" != "x")
        {
            $this->set_js(json_decode($conf->site_js));
        }
        if ($conf->site_css && $conf->site_css."x" != "x")
        {
            $this->set_css(json_decode($conf->site_css));
        }
        $this->set_css($req);
        $this->set_js($req);

        if (function_exists('apc_add')) { $this->set_cache('apc'); }
        elseif (function_exists( 'memcache_set' ) ) { $this->set_cache('memcache'); }

        if ($run)
            $this->run();
    }
    public function get_menu()
    {
        load_alternative_class("class/menu.class.php");
        $menu = new menu($this->db);
        $arr = $menu->draw_tree();
        $this->menu = $arr['menu'];
        $this->set_css($arr['css']);
        $this->set_js($arr['js']);
        $this->set_js_code($arr['js_code']);
    }
    public function get_breadcrumbs()
    {
        load_alternative_class("class/breadcrumbs.class.php");
        $breadcrumbs = new breadcrumbs($this->db);
        $c = get_class($this);
        $breadcrumbs->set_page($this->page);
        $breadcrumbs->get_breadcrumbs($c);
        $this->breadcrumbs = $breadcrumbs->breadcrumbs;
        $this->set_css($breadcrumbs->get_css());
        $this->set_js($breadcrumbs->get_js());
        $this->set_js_code($breadcrumbs->get_js_code());
    }
    public function get_css()
    {
        $this->css = array_unique($this->css);
        return $this->css;
    }
    public function get_css_cache()
    {
        global $conf;
        if ($conf->use_css_cache == "on"  && !$this->no_use_cache)
        {
            $this->css = array_unique($this->css);
            $fname = md5(join(',',$this->css));
            if ($conf->admin_mode)
            {
                $fname = md5("admin_mode".$fname);
            }
            if (!is_file($conf->main_document_root.'/cache/css/'.$fname.".css"))
            {
                if (is_file($conf->main_document_root.'/cache/tmp/'.$fname))
                {
                    unlink($conf->main_document_root.'/cache/tmp/'.$fname);
                }
                $tmp="";
                $oneKo = false;
                foreach($this->css as $key => $val)
                {
                    if (preg_match("/\/cache\//",$val)) continue;
                    $l = strlen($conf->main_url_root);
                    $val = substr($val, $l);

                    $command = "cd cache/tmp; /usr/bin/java -Xss10240k -jar yuicompressor-2.4.8.jar --type css --nomunge --charset utf8 --disable-optimizations -o ".basename($val)." ". $conf->main_document_root."/".$val;
                    $return = false;
                    system($command,$return);
                    if ($return ."x" == "0x")
                    {
                        $tmp1 = file_get_contents($conf->main_document_root."/cache/tmp/".basename($val));
                        //Elfinder
                        $tmp1 = preg_replace('/url\("\.\.\/\.\.\/\.\.\/\.\.\//','url("'.$conf->main_base_path,$tmp1);
                        $tmp1 = preg_replace('/url\(\'\.\.\/\.\.\/\.\.\/\.\.\//','url(\''.$conf->main_base_path,$tmp1);
                        $tmp1 = preg_replace('/url\(\.\.\/\.\.\/\.\.\/\.\.\//','url(\''.$conf->main_base_path,$tmp1);
                        //Plugins

                        if (preg_match('/^plugins\/template\/(.*?)\//',$val,$arrMatch))
                        {
                            $tmp1 = preg_replace('/url\("\.\.\/img/','url("'.$conf->main_base_path."/plugins/template/".$arrMatch[1]."/img",$tmp1);
                            $tmp1 = preg_replace('/url\(\'\.\.\/img/','url(\''.$conf->main_base_path."/plugins/template/".$arrMatch[1]."/img",$tmp1);
                            $tmp1 = preg_replace('/url\(\.\.\/img/','url(\''.$conf->main_base_path."/plugins/template/".$arrMatch[1]."/img",$tmp1);
                        }

                        //Autres cas
                        if (preg_match_all("/url[\s]*\(([\W\w]*?)\)/",$tmp1,$arrMatch))
                        {
                            foreach($arrMatch[1] as $key1=>$val1)
                            {
                                if (preg_match('/data:image/',$val1)) continue;
                                $info = new SplFileInfo($val1);
                                $ext = preg_replace('/[^\w]/',"",$info->getExtension());
                                $file_info = $info->getBasename('.'.$info->getExtension());
                                $file = make_path('image',$file_info, $ext);
                                $path_info = "customer";
                                if (preg_match('/^main/',$file))
                                {
                                    $path_info = "main";
                                }
                                $tmp1 = preg_replace('/url\("\.\.\/image\/'.$file_info.'/','url("'.$conf->main_base_path.($conf->admin_mode?"/".$path_info."/admin/image":$path_info."/image").'/'.$file_info,$tmp1);
                                $tmp1 = preg_replace('/url\(\'\.\.\/image\/'.$file_info.'/','url(\''.$conf->main_base_path.($conf->admin_mode?"/".$path_info."/admin/image":$path_info."/image").'/'.$file_info,$tmp1);
                                $tmp1 = preg_replace('/url\(\.\.\/image\/'.$file_info.'/','url('.$conf->main_base_path.($conf->admin_mode?"/".$path_info."/admin/image":$path_info."/image").'/'.$file_info,$tmp1);

                            }

                        }

                        file_put_contents($conf->main_document_root."/cache/tmp/".$fname,$tmp1,FILE_APPEND);
                        unlink($conf->main_document_root.'/cache/tmp/'.basename($val));
                    } elseif(!$oneKo) {
                        $oneKO=true;
                    }
                }
                if (!$oneKo)
                {
                    copy($conf->main_document_root.'/cache/tmp/'.$fname,$conf->main_document_root."/cache/css/".$fname.".css");
                    unlink($conf->main_document_root.'/cache/tmp/'.$fname);
                    $this->css_cache[] = $conf->main_url_root."/cache/css/".$fname.".css";
                    global $trigger,$current_user;
                    $trigger->run_trigger("MAKE_CSS_CACHE", $this,$current_user);
                } else {
                    unlink($conf->main_document_root.'/cache/tmp/'.$fname.".css");
                    return false;
                }
            } else {
                $this->css_cache[] = $conf->main_url_root."/cache/css/".$fname.".css";
            }
        }
        $this->css_cache = array_unique($this->css_cache);
        return $this->css_cache;
    }
    public function get_js()
    {
        $this->js = array_unique($this->js);
        return $this->js;
    }
    public function get_js_cache()
    {
        global $conf;

        if ($conf->use_js_cache == "on"  && !$this->no_use_cache)
        {
            $this->js = array_unique($this->js);
            $fname = md5(join(',',$this->js));
            if ($conf->admin_mode)
            {
                $fname = md5("admin_mode".$fname);
            }
            if (!is_file($conf->main_document_root.'/cache/js/'.$fname.".js"))
            {
                if (is_file($conf->main_document_root.'/cache/tmp/'.$fname))
                {
                    unlink($conf->main_document_root.'/cache/tmp/'.$fname);
                }
                $tmp="";
                $oneKo = false;
                foreach($this->js as $key => $val)
                {
                    if (preg_match("/\/cache\//",$val)) continue;
                    $l = strlen($conf->main_url_root);
                    $val = substr($val, $l);

                    $command = "cd cache/tmp; /usr/bin/java  -Xss5120k -jar yuicompressor-2.4.8.jar --type js --nomunge --charset utf8 -o ".basename($val)." ". $conf->main_document_root."/".$val;
                    $return = false;
                    system($command,$return);
                    if ($return ."x" == "0x")
                    {
                        file_put_contents($conf->main_document_root."/cache/tmp/".$fname,file_get_contents($conf->main_document_root."/cache/tmp/".basename($val)),FILE_APPEND);
                        unlink($conf->main_document_root.'/cache/tmp/'.basename($val));
                    } elseif(!$oneKo) {
                        $oneKO=true;
                    }
                }
                if (!$oneKo)
                {
                    copy($conf->main_document_root.'/cache/tmp/'.$fname,$conf->main_document_root."/cache/js/".$fname.".js");
                    unlink($conf->main_document_root.'/cache/tmp/'.$fname);
                    $this->js_cache[] = $conf->main_url_root."/cache/js/".$fname.".js";
                    global $trigger,$current_user;
                    $trigger->run_trigger("MAKE_JS_CACHE", $this,$current_user);
                } else {
                    unlink($conf->main_document_root.'/cache/tmp/'.$fname.".js");
                    return false;
                }
            } else {
                $this->js_cache[] = $conf->main_url_root."/cache/js/".$fname.".js";
            }
        }
        $this->js_cache = array_unique($this->js_cache);
        return $this->js_cache;
    }
    public function get_js_external()
    {
        $this->js_external = array_unique($this->js_external);
        return $this->js_external;
    }
    public function set_css($in)
    {
        if(is_array($in))
        {
            foreach($in as $val)
            {
                $tmp = make_path('css',$val,"css",false,true);
                if ($tmp) $this->css[]=$tmp;
            }
        } else {
            $tmp = make_path('css',$in,"css",false,true);
            if ($tmp) $this->css[]=$tmp;
        }
    }
    public function set_plugin_css($plugin,$in)
    {
        if(is_array($in))
        {
            foreach($in as $val)
            {
                $tmp = make_path_plugin($plugin,'css',$val,"css",false,true);
                if ($tmp) $this->css[]=$tmp;
            }
        } else {
            $tmp = make_path_plugin($plugin,'css',$in,"css",false,true);
            if ($tmp) $this->css[]=$tmp;
        }
    }
    public function set_js($in)
    {
        if(is_array($in))
        {
            foreach($in as $val)
            {
                $tmp = make_path('js',$val,"js",false,true);
                if ($tmp) $this->js[]=$tmp;
            }
        } else {
            $tmp = make_path('js',$in,"js",false,true);
            if ($tmp) $this->js[]=$tmp;
        }
    }
    public function set_plugin_js($plugin,$in)
    {
        if(is_array($in))
        {
            foreach($in as $val)
            {
                $tmp = make_path_plugin($plugin,'js',$val,"js",false,true);

                if ($tmp) $this->js[]=$tmp;
            }
        } else {
            $tmp = make_path_plugin($plugin,'js',$in,"js",false,true);
            if ($tmp) $this->js[]=$tmp;
        }
    }
    public function set_js_external($in)
    {
        if(is_array($in))
        {
            foreach($in as $val)
            {
                if (preg_match('^http[s]?://',$in))
                {
                    $this->js_external[]=$val;
                }
            }
        } else {
            if (preg_match('/^http[s]?:\/\//',$in))
            {

                $this->js_external[]=$in;
            }
        }

    }
    public function set_js_code($in)
    {
        if ($in && $in."x" != "x" && preg_replace('/\s*/','',$in)."x" != "x")
            $this->js_code[] = $in;
        //$trace=debug_backtrace();
        //$caller=$trace[1];
        //echo "Called by {$caller['function']}";
        //if (isset($caller['class']))
        //    echo " in {$caller['class']}";
        //var_dump($in);
    }
    public function set_css_code($in)
    {
        if ($in && $in."x" != "x" && preg_replace('/\s*/','',$in)."x" != "x")
            $this->css_code[] = $in;
    }
    public function get_js_code()
    {
        return $this->js_code;
    }
    public function get_css_code()
    {
        return $this->css_code;
    }
    public function set_meta($meta)
    {
        $this->meta .= $meta;
    }
    public function get_meta()
    {
        global $conf;
        $this->meta .= ($this->meta."x" != "x"?", ":"") .$conf->meta;
        return $this->meta;
    }
    protected function load_page_properties()
    {
        if ($this->page)
        {
            $this->set_meta($this->page->get_meta());
            $this->set_css_code($this->page->get_css_code());
            $this->set_js_code($this->page->get_js_code());
            $this->set_js($this->page->get_js());
            $this->set_js_external($this->page->get_js_external());
            $this->set_css($this->page->get_css());
        }
    }
    public function gen_error_page($msg,$debug_data=array())
    {
        $this->set_template_file(make_path('template',"error",'html'));
        $this->i18n_js();
        $this->load_page_properties();
        try{
            global $conf,$debug,$current_user;
            if (isset($current_user)) $current_user->get_all();
            $render_array = array(
                    'css' => ($conf->use_css_cache == "on" && !$this->no_use_cache?$this->get_css_cache():$this->get_css()),
                    'js' => ($conf->use_js_cache == "on" && !$this->no_use_cache?$this->get_js_cache():$this->get_js()),
                    'js_external' => $this->get_js_external(),
                    'js_code' => $this->get_js_code(),
                    'css_code' => $this->get_css_code(),
                    'title' => "Error from ".$this->req,
                    "conf" => $conf,
                    "main" => $this->req,
                    "msg" => $msg,
                    "debug_app" => $debug->display(),
                    "debug_bool" => ($conf->debug_on_error=="on"?true:false),
                    'cur_user' => $current_user->get_all()
            );
            global $lang;
            $template = new H2o($this->get_template_file(), array( 'cache' => $this->get_cache(), 'i18n' => array('locale' => $lang ) ));
            $fiche = $template->render($render_array);
            echo $fiche;
        } catch (Exception $e){
            echo 'Exception fatale reçue : ',  $e->getMessage(), "\n";
        }
    }
    public function gen_page()
    {
        $h2o_params['cache']=$this->get_cache();
        global $lang;
        //$h2o_params['i18n']=  array('locale' => 'fr_FR' , 'extract_message'=> true);
        $h2o_params['i18n']=  array('locale' =>$lang);
        if ($this->get_loader()) $h2o_params['loader'] = $this->get_loader();
        try{
            $template = new H2o($this->get_template_file(), $h2o_params);
            $fiche = $template->render($this->get_render_array());
            global $conf;

            if ($conf->prettyHtml == "on" && $this->req != 'php_info')
            {
                load_alternative_class('class/indend.class.php');
                $i = new indent();
                $fiche = $i->reindent($fiche);
            }
            global $trigger,$current_user;
            $trigger->run_trigger("GEN_PAGE", $this,$current_user);
            return $fiche;
        } catch (Exception $e) {
            global $conf,$debug;
            $debug->collect($e);
            $this->gen_error_page($e->getMessage(),array($conf,$this));
            return "";
        }
    }
    private function get_render_array()
    {
        global $debug,$conf;
        $array1 = array();
        $this->i18n_js();
        if (! $conf->admin_mode )
        {
            $array1['col_gauche'] = $this->get_col_gauche();
            $array1['col_droite'] = $this->get_col_droite();
        }
        $this->switch_lang_bar();

        $extra = $this->get_extra_render();
        if ($extra)
        {
            $remPath = false;
            $remThb = false;
            $remPrefix = false;
            foreach($extra as $key=>$val)
            {
                if ($val['key'] == 'col_gauche' && $val['val']."x" == "x") continue;
                if ($val['key'] == 'col_droite' && $val['val']."x" == "x") continue;
                $array1[$val['key']] = $val['val'];
                if ($val['key'] == "image_path") $remPath = $val;
                elseif ($val['key'] == "thb_path") $remThb = $val;
                if ($val['key'] == "thb_prefix") $remPrefix = $val['val'];
            }

            if ($remPath && $remThb )
            {
                $array_thumb_image = array();
                $prefix = "";
                if ($remPrefix) $prefix = $remPrefix;

                foreach($remThb['val'] as $datas_thumb)
                {
                    foreach($remPath['val'] as $key=>$datas_image)
                    {
                        if (isset($prefix)
                                && is_object($datas_image)
                                && is_object($datas_thumb)
                                && isset($datas_image->name)
                                && isset($datas_thumb->name)
                                && $prefix.$datas_image->name == $datas_thumb->name)
                        {
                            $array_tmp =array('path' => $datas_image->path,
                                              'name' => $datas_image->name,
                                              'thb_path' => $datas_thumb->path,
                                              'thb_name' => $datas_thumb->name);

                            load_alternative_class('class/data_file.class.php');
                            $df = new data_file($this->db);
                            $array_info = $df->fetch_by_file_path(preg_replace('/^\//','',$datas_image->path));
                            foreach($array_info as $key1 =>$val1)
                            {
                                $array_tmp[$val1->data_name] = $val1->data_value;
                            }
                            $array_thumb_image[] = $array_tmp;
                            break;
                        } else if (isset($prefix)
                                && is_array($datas_image)
                                && is_array($datas_thumb)
                                && isset($datas_image['name'])
                                && isset($datas_thumb['name'])
                                && $prefix.$datas_image['name'] == $datas_thumb['name'])
                        {
                            $array_tmp =array('path' => $datas_image['path'],
                                    'name' => $datas_image['name'],
                                    'thb_path' => $datas_thumb['path'],
                                    'thb_name' => $datas_thumb['name']);

                            load_alternative_class('class/data_file.class.php');
                            $df = new data_file($this->db);
                            $array_info = $df->fetch_by_file_path(preg_replace('/^\//','',$datas_image['path']));
                            foreach($array_info as $key1 =>$val1)
                            {
                                $array_tmp[$val1->data_name] = $val1->data_value;
                            }
                            $array_thumb_image[] = $array_tmp;
                            break;
                        }
                    }
                }
                $array1['thumb_image_path'] = $array_thumb_image;
            }
        }

        $array1['bandeau_growl_bool'] = false;
        if ($conf->use_growl_for_message == 'on')
        {
            $array1['bandeau_growl'] = $this->get_message();
            $array1['bandeau_growl_bool'] = true;
            $this->set_css('jquery.growl');
            $this->set_js(array('jquery.growl','jquery'));

            $array1['bandeau_growl_publication'] = $this->get_message_publication();
            $array1['bandeau_growl_publication_bool'] = true;

        } else {
            $array1['bandeau'] = $this->get_message();
            $array1['bandeau_publication'] = $this->get_message_publication();
        }
        if (! $conf->admin_mode )
        {
            $array1['header'] = $this->get_header();
            $array1['footer'] = $this->get_footer();
            $array1['social'] = $this->get_social();
            $array1['header_management'] = $this->get_header_management();
            $array1['cookie_msg'] = $this->get_cookiemessage();
            $array1['comment_bool'] = $this->get_permit_comment();
            if ($this->get_permit_comment())
            {
                $this->set_js(array('validation/jquery.validate.min','validation/localization/messages_fr.min'));
                $this->set_js_code('jQuery(document).ready(function(){ jQuery("#comment_form").validate(); }); ');
                $array1['commenthtml'] = $this->get_comment_block();
                if ($conf->reCaptcha_key."x" != "x")
                {
                    $this->set_js_external('https://www.google.com/recaptcha/api.js');
                }
                if ($conf->use_tinymce_for_comment == "on")
                {
                    $css = ($conf->use_css_cache == "on" && !$this->no_use_cache?$this->get_css_cache():$this->get_css());
                    $this->set_js(array('tinymce/tinymce.min','tinymce/jquery.tinymce.min','tinymce/tinymce_comment'));
                    global $lang,$conf;
                    $tmpLang = substr($lang,0,2);
                    if (is_file(make_path('js', "tinymce/langs/".$lang, "js")))
                    {
                        $this->set_js('tinymce/langs/'.$lang.'.js');
                    } elseif (is_file(make_path('js', "tinymce/langs/".$tmpLang, "js")))
                    {
                        $this->set_js('tinymce/langs/'.$tmpLang.'.js');
                    } else {
                        $tmpLang = substr($conf->default_lang,0,2);
                        if (is_file(make_path('js', "tinymce/langs/".$conf->default_lang, "js")))
                        {
                            $this->set_js('tinymce/langs/'.$conf->default_lang.'.js');
                        } else {
                            $this->set_js('tinymce/langs/'.$tmpLang.'.js');
                        }
                    }
                    $this->set_js_code('var base_root_path="'.$conf->main_base_path.'";');
                    $this->set_js_code('var content_css_set="'.join(',',$css).'";');
                    $this->set_js_code('var max_char_comment="'.(isset($conf->comment_max_length) && $conf->comment_max_length > 0?$conf->comment_max_length:1000).'";');
                    $this->set_js_code('jQuery(document).ready(function(){ load_tiny_mce_comment(content_css_set,base_root_path,max_char_comment); })');
                }
            }

        }

        $array1['plugins'] = $this->get_plugins_datas();
        global $lang;
        $array1['lang'] = $lang;
        $array1['lang_short'] = substr($lang, 0,2);
        $this->set_js_code("var lang='".$lang."'; var lang_short='".substr($lang, 0,2)."';   ");
        if (make_path('js/datepicker_i18n/','datepicker-'.$lang,"js"))
            $this->set_js('datepicker_i18n/datepicker-'.$lang);
        if (make_path('js/datepicker_i18n/','datepicker-'.substr($lang, 0,2),"js"))
            $this->set_js('datepicker_i18n/datepicker-'.substr($lang, 0,2));
        global $current_user;
        if (isset($current_user)) $current_user->get_all();
        $array = array(
                'menu' => $this->menu,
                'breadcrumbs' => $this->breadcrumbs,
                'title' => $this->get_title(),
                'main' => $this->get_main(),
                'section' => $this->get_section(),
                'req'  => $this->req,
                'meta' => $this->get_meta(),
                'conf'=>$conf,
                'lang' => $lang,
                'lang_short' => substr($lang, 0,2),
                'debug_bool' => $debug->has_data(),
                'debug_app' => $debug->display(),
                'search_mode' => $this->search_mode,
                'css_code' => $this->get_css_code(),
                'js_code' => $this->get_js_code(),
                'js' => ($conf->use_js_cache == "on" && !$this->no_use_cache?$this->get_js_cache():$this->get_js()),
                'js_external' => $this->get_js_external(),
                'css' => ($conf->use_css_cache == "on" && !$this->no_use_cache?$this->get_css_cache():$this->get_css()),
                'cur_user' => $current_user,
        );
        $array = array_merge($array1,$array);
        return $array;
    }
    public function set_extra_render($key,$val)
    {
        $this->extra_render[]=array('key' => $key, 'val' => $val);
    }
    public function get_extra_render()
    {
        return ($this->extra_render);
    }
    public function set_loader($loader)
    {
        $this->loader = $loader;
    }
    public function get_loader()
    {
        return $this->loader;
    }
    public function set_template_file($template_file)
    {
        $this->tpl_file = $template_file;
    }
    public function get_template_file()
    {
        return $this->tpl_file;
    }
    public function get_main()
    {
        return $this->main;
    }
    public function get_title()
    {
        return $this->title;
    }
    public function set_title($title)
    {
        $this->title = $title;
    }
    public function set_main($main)
    {
        $this->main = $main;
    }
    public function get_cache()
    {
        return $this->cache;
    }
    public function set_cache($cache)
    {
        $this->cache = $cache;
    }
    private function get_header()
    {
        load_alternative_class('class/header.class.php');
        $h = new header($this->db);
        global $lang;
        $h->display_lang($lang);
        $h->fetch();
        $this->set_css($h->get_css());
        $this->set_js($h->get_js());
        $this->set_css_code($h->get_css_code());
        $this->set_js_code($h->get_js_code());
        return "<div id='main-header'>" . $h->get_content()."</div>";
    }
    private function get_footer()
    {
        load_alternative_class('class/footer.class.php');
        $f = new footer($this->db);
        global $lang;
        $f->display_lang($lang);
        $f->fetch();
        $this->set_css($f->get_css());
        $this->set_js($f->get_js());
        $this->set_css_code($f->get_css_code());
        $this->set_js_code($f->get_js_code());
        return $f->get_content();
    }
    private function get_col_gauche()
    {
        load_alternative_class('class/col_gauche.class.php');
        $f = new col_gauche($this->db);
        global $lang;
        $f->display_lang($lang);
        $f->fetch();
        $this->set_css($f->get_css());
        $this->set_js($f->get_js());
        $this->set_css_code($f->get_css_code());
        $this->set_js_code($f->get_js_code());
        return $f->get_content();
    }
    private function get_col_droite()
    {
        load_alternative_class('class/col_droite.class.php');
        $f = new col_droite($this->db);
        global $lang;
        $f->display_lang($lang);
        $f->fetch();
        $this->set_css($f->get_css());
        $this->set_js($f->get_js());
        $this->set_css_code($f->get_css_code());
        $this->set_js_code($f->get_js_code());
        return $f->get_content();
    }

    private function get_message()
    {
        load_alternative_class('class/message.class.php');
        $f = new message($this->db);
        global $lang;
        $f->display_lang($lang);
        $f->fetch();
        if ($f->get_content()."x" != "x")
        {
            $this->set_css($f->get_css());
            $this->set_js($f->get_js());
            $this->set_css_code($f->get_css_code());
            $this->set_js_code($f->get_js_code());
            return $f->get_content();
        } else {
            return false;
        }
    }
    private function get_message_publication()
    {
        global $current_user;
        if ($current_user->get_id()>0)
        {
            load_alternative_class('class/message_publication.class.php');
            $f = new message_publication($this->db);
            $requete = "SELECT *
                          FROM group_publication_user
                         WHERE user_refid = ".$current_user->get_id();
            $sql = $this->db->query($requete);
            if ($sql && $this->db->num_rows($sql) > 0)
            {
                $a = array();
                while($res = $this->db->fetch_object($sql))
                {
                    $f->set_id($res->group_publication_refid);
                    $f->fetch();
                    if ($f->get_content()."x" != "x")
                    {
                        $this->set_css($f->get_css());
                        $this->set_js($f->get_js());
                        $this->set_css_code($f->get_css_code());
                        $this->set_js_code($f->get_js_code());
                        $a[$res->group_publication_refid] = array( "id" => $res->group_publication_refid,
                                              "msg" => $f->get_content());
                    }
                }
                return $a;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function get_cookiemessage()
    {
        load_alternative_class('class/cookiemessage.class.php');
        $f = new cookiemessage($this->db);
        global $lang;
        $f->display_lang($lang);
        $f->fetch();
        if ($f->get_content()."x" != "x" && (isset($_COOKIE['accept']) && $_COOKIE['accept'] != "oui")){
            $this->set_css($f->get_css());
            $this->set_js($f->get_js());
            $this->set_css_code($f->get_css_code());
            $this->set_js_code($f->get_js_code());
            return $f->get_content();
        } else {
            return false;
        }
    }
    protected function set_permit_comment($permit_comment)
    {
        $this->permit_comment = $permit_comment;
    }
    public function get_permit_comment()
    {
        return $this->permit_comment;
    }
    protected  function set_comment_page_id($page_id)
    {
        $this->comment_page_refid = $page_id;
    }
    public  function get_comment_page_id()
    {
        return $this->comment_page_refid;
    }
    protected function get_comment_block()
    {
        load_alternative_class('class/comment.class.php');
        $c = new comment($this->db);
        return $c->get_comment_block($this->get_comment_page_id(),$this->comment_response);
    }
    public function set_comment($post)
    {
        global $conf;
        if ($post['action'] == "comment")
        {
            load_alternative_class('class/comment.class.php');
            $c = new comment($this->db);

            if ($conf->reCaptcha_key."x" != "x")
            {
                $remote_ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:false);
                if (!$remote_ip) { $remote_ip = $_SERVER['REMOTE_ADDR'];}
                $ret = $c->verify_captcha_response($post['g-recaptcha-response'],$remote_ip);
                if (!$ret)
                {
                    $this->comment_response="KO";
                    return false;
                }
            }
            if ($conf->akismet_api_key."x" != "x")
            {
                load_alternative_class('class/comment.class.php');
                $c = new comment($this->db);
                if ($c->verify_akismet_key())
                {
                    $ret = $c->check_spam_comment($post);
                    if (!$ret)
                    {
                        $this->comment_response="KO";
                        return false;
                    }
                }
            }
            $count = strlen(strip_tags($post['comment']));
            if ($count > $conf->comment_max_length)
            {
                $this->comment_response="KO";
                return false;
            }
            global $current_user;
            $id = $c->create();
            $c->fetch($id);
            $c->set_title($post['title']);
            if ($conf->moderation_type != "on")
                $c->set_valid(1);
            else
                $c->set_valid(0);
            $c->set_content($post['comment']);
            $c->set_page_refid($this->page->get_id());
            if ($current_user->get_id() > 0)
            {
                $c->set_author_refid($current_user->get_id());
                $c->set_author($current_user->get_firstname()." ".$current_user->get_name());
            } else {
                $c->set_author($post['author']);
            }
            $this->comment_response = "OK";
            return true;
        }
    }
    private function get_all_access_rights()
    {
        $restrict = array(
                //Admin page restriction
                'admin'               => array('admin','read'),
                'akismet'             => array('admin','moderate','manage'),
                'cache'               => array('admin','manage','edit_page','edit_section'),
                'col_droite'          => array('admin','headerfooter'),
                'col_gauche'          => array('admin','headerfooter'),
                'comment'             => array('admin','moderate'),
                'conf'                => array('admin','conf'),
                'cookiemessage'       => array('admin','headerfooter','manage'),
                'data_file'           => array('admin','manage','edit_page'),
                'edit'                => array('admin','edit_page'),
                'edit_page'           => array('admin','edit_page'),
                'edit_section'        => array('admin','edit_page','edit_section'),
                'external_msg'        => array('admin','manage','moderate'),
                'files'               => array('admin','edit_page','edit_section','manage'),
                'footer'              => array('admin','headerfooter'),
                'forms'               => array('admin','edit_page'),
                'group'               => array('admin','user'),
                'group_publication'   => array('admin','publication'),
                'group_rights'        => array('admin','user'),
                'header'              => array('admin','headerfooter'),
                'hierarchie'          => array('admin','manage','edit_page'),
                'historic_modif'      => array('admin','edit_page'),
                'histo_preview'       => array('admin','edit_page'),
                'image'               => array('admin','manage','edit_page','edit_section'),
                'import_xml'          => array('admin','manage','edit_page'),
                'index'               => array('admin','read','edit_page','edit_section','conf','user','headerfooter','template','publication','manage','moderate'),
                'internal_msg'        => array('admin','manage','moderate'),
                'lang'                => array('admin','read','edit_page','edit_section','conf','user','headerfooter','template','publication','manage','moderate'),
                'mail_model'          => array('admin','template','manage'),
                'manage'              => array('admin','manage'),
                'map'                 => array('admin','read','manage','edit_page','edit_section'),
                'message'             => array('admin','headerfooter','manage'),
                'message_publication' => array('admin','headerfooter','manage','publication'),
                'pageondisk'          => array('admin','edit_page'),
                'php_info'            => array('admin','read','manage'),
                'plugins'             => array('admin','read','edit_page','edit_section','conf','user','headerfooter','template','publication','manage','moderate'),
                'preview'             => array('admin','edit_page'),
                'publication'         => array('admin','publication'),
                'submenu'             => array('admin','edit_page'),
                'result_forms'        => array('admin','edit_page'),
                'revision'            => array('admin','edit_page'),
                'rights'              => array('admin','user'),
                'robot'               => array('admin','manage'),
                'template'            => array('admin','template'),
                'user'                => array('admin','user'),
                'view_revision'       => array('admin','edit_page'),
                'http://127.0.0.1/iZend2/' => array('admin','read','edit_page','edit_section','conf','user','headerfooter','template','publication','manage','moderate'),
        );
        return $restrict;
    }
    protected function access_rights($class_orig=false)
    {
        if (!$class_orig) $class=$this;
        else $class=$class_orig;
        if (is_object($class))
            $class = get_class($class);
        $class = preg_replace('/^view_/','',$class,1);
        $class = preg_replace('/^controller_/','',$class);
        $class = preg_replace('/^model_/','',$class);
        $restrict = $this->get_all_access_rights();
        if (!isset($restrict[$class])) return false;
        if ($class_orig) return $restrict[$class];
        else
            $this->set_needrights($restrict[$class]);
    }
    protected function get_needrights()
    {
        return $this->needrights;
    }
    protected function set_needrights($r)
    {
        $this->needrights = $r;
    }
    protected function is_granted($array_rights=false)
    {
        if ($this->search_mode) return true;
        if (!$array_rights)
            $array_rights = $this->get_needrights();
        global $current_user;
        $granted = false;
        foreach($current_user->get_rights() as $key=> $val)
        {
            if (in_array($key, $array_rights) && $val>0)
            {
                $granted = true;
                break;
            }
        }
        return $granted;
    }
    protected function get_header_management()
    {
        global $current_user,$conf;
        $html = false;

        if ($current_user->get_id()>0 && ( $current_user->has_right('edit_page') || $current_user->has_right('admin') ))
        {
            $html = "<div class='header_management'>";
            $html .= "<img src='".preg_replace('/\/$/','',$conf->main_base_path)."/".preg_replace('/^\//','',$conf->logo)."' height=16 />";

            if (! empty($this->page) && $this->page->get_page_on_disk() == 0)
            {
                $html .= "<button onclick='location.href=\"".$conf->main_base_path."/admin/edit_page?id=".$this->page->get_id()."\"'>"._("Editer cette page")."</button>";
            }
            elseif (!empty($this->req))
                $html .= "<button onclick='location.href=\"".$conf->main_base_path."/admin/pageondisk?id=".$this->req."\"'>"._("Editer cette page")."</button>";
            $html .= "<button onclick='location.href=\"".$conf->main_base_path."/admin\"'>"._("Admin")."</button>";
            $html .= "</div>";
            $this->set_css('header_management');
            $this->set_js_code('jQuery(document).ready(function(){ jQuery(".header_management").find("button").button(); });');
        }else if ($current_user->get_id()>0 && ( $current_user->has_some_admin_right() ))
        {
            $html = "<div class='header_management'>";
            $html .= "<img src='".preg_replace('/\/$/','',$conf->main_base_path)."/".preg_replace('/^\//','',$conf->logo)."' height=16 />";
            $html .= "<button onclick='location.href=\"".$conf->main_base_path."/admin\"'>"._("Admin")."</button>";
            $html .= "</div>";
            $this->set_css('header_management');
            $this->set_js_code('jQuery(document).ready(function(){ jQuery(".header_management").find("button").button(); });');
        }
        return $html;
    }
    public function hide_email($email) {
        $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
        $key = str_shuffle($character_set);
        $cipher_text = ''; $id = 'e'.rand(1,999999999);
        for ($i=0;$i<strlen($email);$i+=1)
            $cipher_text.= $key[strpos($character_set,$email[$i])];
        $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
        $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
        $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
        $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
        $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
        return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
    }
    public function verify_captcha_response($g_recaptcha_response,$remote_ip)
    {
        global $conf;
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = "secret=".$conf->reCaptcha_key_private."&response=".$g_recaptcha_response."&remoteip=".$remote_ip;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST ,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); /* obey redirects */
        curl_setopt($ch, CURLOPT_HEADER, 0);  /* No HTTP headers */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  /* return the data */

        $result = curl_exec($ch);
        $r = json_decode($result);
        curl_close($ch);
        return $r->success;
    }

    public function get_section()
    {
        return $this->section;
    }
    public function set_section($section)
    {
        $this->section = array_merge($this->section,$section);
    }
    public function get_social()
    {
        load_alternative_class('class/social.class.php');
        $s = new social($this->db);
        $r = $s->generate($this->page);
        if ($r)
        {
            $this->set_js($s->get_js());
            $this->js_code = array_merge($this->js_code,$s->get_js_code());
            $this->js_external = array_merge($this->js_external,$s->get_js_external());
            return $s->get_html();
        } else {
            return false;
        }
    }
    public function get_plugins_datas()
    {
        if ($this->page && $this->page->get_id()>0)
        {
            $array=array();
            $template_id = $this->page->get_model_refid();
            if ($template_id > 0)
            {
                load_alternative_class('class/template.class.php');
                $template = new template($this->db);
                $template->fetch($template_id);
                $a = array();
                if ($template->get_plugins())
                {
                    foreach(json_decode($template->get_plugins()) as $val)
                    {
                        require_once('plugins/template/'.$val."/".$val.".class.php");
                        $plugin = new $val($this->db);
                        $array[$val] = $plugin->get_params($this->page);
                        if ($plugin->get_display_js())
                            $this->set_plugin_js($val,$plugin->get_display_js());

                        if ($plugin->get_display_css())
                            $this->set_plugin_css($val,$plugin->get_display_css());

                        if ($plugin->get_display_css_code())
                            $this->set_css_code($plugin->get_display_css_code());

                        if ($plugin->get_display_js_code())
                            $this->set_js_code($plugin->get_display_js());

                        if ($plugin->get_display_js_external())
                            $this->set_js_external($plugin->get_display_js());
                    }
                } else {
                    return false;
                }
                return $array;

            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    protected function i18n_js()
    {
        global $lang;
        $this->set_js('jed');
        $js_code = "var i18n = new Jed(";
        $js_code .= file_get_contents('lang/'.$lang.'/LC_MESSAGES/jsmessages.json');
        $js_code .= ');';
        $this->set_js_code($js_code);
    }
    public function switch_lang_bar()
    {
        $add_sep = "?";
        if (preg_match('/\?/',curPageURL()))
        {
            $add_sep = "&";
        }
        global $conf,$lang;
        $a = preg_split('/,/',$conf->available_lang);
        $html = "<div class='flags_switch'>";
        foreach($a as $key=>$val)
        {
            if (is_file($conf->main_document_root .'/img/flags/'.strtolower(substr($val,3,2)).'.png'))
            {
                if (strtolower(substr($val,3,2)) == strtolower(substr($lang,3,2)))
                {
                    $html.='<span><a title="'.$val.'" href="'.curPageURL().$add_sep.'lang='.$val.'"><img class="current_lang" src ="'.$conf->main_base_path.'/img/flags/'.strtolower(substr($val,3,2)).'.png" /></a></span>';
                } else {
                    $html.='<span><a title="'.$val.'" href="'.curPageURL().$add_sep.'lang='.$val.'"><img src ="'.$conf->main_base_path.'/img/flags/'.strtolower(substr($val,3,2)).'.png" /></a></span>';
                }
            }
        }
        $html.="</div>";
        $this->set_extra_render('lang_switch', $html);
    }
}
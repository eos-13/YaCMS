<?php
ini_set('max_execution_time',3600);

class model_edit_page extends admin_common
{
    public $id;
    public $can_unlock;
    public $can_edit;
    private $page_access;
    public $message = false;
    public $pages_lang;
    public function run()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $res = $p->fetch($this->id);
        $this->page_access = $p;
        $this->can_unlock = $this->can_unlock($p->get_is_locked_for_edition());
        $this->can_edit = $this->can_edit($p);

        if (!$res) return false;
        $array = array();
        $array['content']['content']=$p->get_content();
        $array['content']['title']=$p->get_title();
        $array['section']=$p->get_all_section();
        $array['property']=$this->get_all_properties();
        $array['group_publi']['group_publication'] = $this->get_all_group_publi();
        $array['group_publi']['group_publication_refid'] = $this->get_all_my_group_publi();
        $array['all_publication_status'] = $this->get_all_publication_status();
        $array['has_draft'] = $this->seek_draft();
        $array['last_edit']= $this->last_edit_data();
        $array['can_approuve'] = $this->can_approuve();
        $this->fetch_all_lang();
        return $array;
    }
    private function fetch_all_lang()
    {
        $requete = "SELECT id,
                           lang
                      FROM page
                     WHERE url = (SELECT url
                                    FROM page
                                   WHERE id = ".$this->id." )";
        global $lang, $conf;
        foreach(preg_split('/,/',$conf->available_lang)  as $key=>$val)
        {
            $this->pages_lang[$val] = false;
        }
        $sql = $this->db->query($requete);
        while ($res = $this->db->fetch_object($sql))
        {
            if ($res->lang. "x" == "x")
            {
                $this->pages_lang[$conf->default_lang] = $res->id;
            } else {
                $this->pages_lang[$res->lang] = $res->id;
            }
        }
    }
    public function set_page()
    {
        $p = new page($this->db);
        $res = $p->fetch($this->id);
        $this->page_access = $p;
        $this->page_access->get_all();
    }
    private function last_edit_data()
    {
        $by = $this->page_access->get_last_modif_by_user_obj()->get_name()." ".$this->page_access->get_last_modif_by_user_obj()->get_firstname();
        return array('by'=>$by, 'date'=>convert_time_us_to_fr($this->page_access->get_date_derniere_maj()));
    }
    private function seek_draft()
    {
        $requete = "SELECT *
                      FROM page
                     WHERE is_a_draft_for = ".$this->id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $a = array();
            $res = $this->db->fetch_object($sql);
            $a[] = array(
                        "id" => $res->id,
                        "title" => "Draft ". $res->title,
            );
            return $a;
        } else {
            return false;
        }
    }
    private function get_all_publication_status()
    {
        $requete = "SELECT *
                      FROM publication_status
                  ORDER BY `order`";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[]=array('id' => $res->id, 'name'=>$res->name);
        }
        return $a;
    }
    private function get_all_group_publi()
    {
        $requete = "SELECT *
                      FROM group_publication
                     WHERE active=1";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[]=array('id' => $res->id, 'name'=>$res->name);
        }
        return $a;
    }
    private function get_all_my_group_publi()
    {
        $requete = "SELECT group_publication.id
                      FROM group_publication,
                           group_publication_page
                     WHERE group_publication.active=1
                       AND group_publication_page.group_publication_refid = group_publication.id
                       AND group_publication_page.page_refid = ".$this->id;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[]=array('id' => $res->id);
        }
        return $a;
    }
    private function get_all_properties()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $arr = $p->list_properties();
        foreach($arr as $key=>$val)
        {
            $value = $p->get_property($val['field']);
            $arr[$key]['value']=$value;
            $ret=$this->make_formpart($val,$value,$this->can_edit);
            if (is_array($ret))
            {
                $arr[$key]['formpart']=array('html' => $ret['html'],
                                             'js' => $ret['js'],
                                             'js_code' => $ret['js_code']);
            } else {
                $arr[$key]['formpart']=$ret;
            }

        }
        return $arr;
    }
    public function get_all_page()
    {
        $requete = "SELECT *
                      FROM page
                     WHERE id <> ".$this->id. "
                  ORDER BY title";
        $sql = $this->db->query($requete);
        $arr = array();
        while($res = $this->db->fetch_object($sql))
        {
            $arr[]=array(
                    'id' => $res->id,
                    'title' => $res->title
            );
        }
        return $arr;
    }
    public function switch_lang($post)
    {
        if (!isset($post['id']) && isset($post['from_id']) && $post['from_id'] > 0)
        {
            load_alternative_class('class/page.class.php');
            $p = new page($this->db);
            $p->fetch($post['from_id']);
            $res = $p->page_clone($post['editlang'],false);
            if ($res) $this->message = _("Opération effectuée");
            else $this->message = _("Opération echouée");
            return $res;
        }
    }
    public function update_content($content)
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $res = $p->set_content($content);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function update_title($title)
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $res = $p->set_title($title);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function update_url($url)
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $res = $p->set_url($url);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function edit_properties($post)
    {
        load_alternative_class("class/page.class.php");
        $page = new page($this->db);
        $res = $page->parse_POST_and_update($post);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function add_section()
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->req = $this->req;
        $newId = $s->add($this->id);
        if ($newId && $newId >0)
            $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return 1;
    }
    public function del_section($section_id)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->req = $this->req;
        $res = $s->delete($section_id);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return 1;
    }
    public function deactivate_section ($section_id)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->req = $this->req;
        $s->fetch($section_id);
        $res = $s->set_active(0);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return 1;
    }
    public function activate_section($section_id)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->req = $this->req;
        $s->fetch($section_id);
        $res = $s->set_active(1);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return 1;
    }
    public function clone_section($section_id)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->req = $this->req;
        $s->fetch($section_id);
        $newId = $s->section_clone();
        if ($newId && $newId > 0)
            $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return 1;
    }
    public function add_page()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $new_id = $p->add();
        if ($new_id && $new_id>0)
            $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $new_id;
    }
    public function del_page()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $res = $p->del();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return 1;
    }
    public function deactivate_page()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $res = $p->set_active("0");
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return 1;
    }
    public function activate_page()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $res = $p->set_active(1);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return 1;
    }
    public function clone_page()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $res = $p->page_clone();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }
    public function make_a_draft()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $res = $p->make_a_draft();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }
    public function clone_to_section($section_id,$page_id)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->req = $this->req;
        $s->fetch($section_id);
        $newId = $s->section_clone($page_id);
        if ($newId && $newId > 0)
            $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function move_section($section_id,$page_id)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->req = $this->req;
        $s->fetch($section_id);
        $res = $s->set_page_refid($page_id);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function edit_sections($post)
    {
        load_alternative_class('class/section.class.php');
        $section = new section($this->db);
        $section->req = $this->req;
        $page_id = $this->id;
        $error = false;
        if ($page_id > 0)
        {
            foreach($post as $key => $val)
            {
                if (preg_match('/^title_([0-9]*)$/',$key,$arrMatch))
                {
                    $sid = $arrMatch[1];
                    if (isset($post['save_1_section']))
                    {
                        if ($sid != $post['save_1_section']) continue;
                    }
                    $section->fetch($sid);
                    $title = $val;
                    $content = $post['section_'.$sid];
                    $res = $section->set_content($content,false);
                    if (!$res && !$error) $error = true;
                    $res = $section->set_title($title,false);
                    if (!$res && !$error) $error = true;
                    if (isset($post['section_image_val_'.$sid]))
                    {
                        $res = $section->set_associated_img($post['section_image_val_'.$sid]);
                        if (!$res && !$error) $error = true;
                    } else {
                        $res = $section->set_associated_img("null");
                        if (!$res && !$error) $error = true;
                    }
                }
            }
            if (isset($post['sort']) && $post['sort']."x" != "x")
            {
                $arr=array();
                $s=preg_split('/&/', $post['sort']);
                foreach($s as $key=>$val)
                {
                    $sid = preg_split('/=/',$val);
                    $arr[]=$sid[1];
                }
                $res = $section->reset_pos($page_id);
                if (!$res && !$error) $error = true;
                foreach($arr as $pos=>$sid)
                {
                    $section->fetch($sid);
                    $res  = $section->set_order($pos);
                    if (!$res && !$error) $error = true;
                }
            }
            $section->force_reindex();
        }
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function edit($post)
    {
        if (isset($post['content']))
            $res1 = $this->page_access->set_content($post['content']);
        if (isset($post['title']))
            $res2 = $this->page_access->set_title($post['title']);
        if ($res1 && $res2) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");

    }
    public function export_xml()
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($this->id);
        $xml = $p->export();
        header("Content-type: application/octet-stream" );

        header("Content-Length: " . strlen($xml)*8);
        header("Content-Disposition: attachment; filename=export_page_".$p->get_id().".xml");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        echo $xml;
        exit;
    }
    public function validate($post)
    {
        global $conf;
        $valid = false;
        $requete = "SELECT *
                      FROM page
                     WHERE url='".addslashes($post['url'])."' AND id <> ".$post['id'];
        $sql = $this->db->query($requete);
        if (!$sql)
        {
            return (json_encode('DB Error'));
        }
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            return (json_encode('Une page cette url existe déjà'));
        }
        //Check filesystem
//         $ret = $this->parse_dir_for($post['url']);
//         if (!$ret) { $valid = true; } else {
//             return (json_encode('Une page sur le disque avec cette url existe déjà'));
//         }
        $valid=true;
        if (!$valid)
        {
            return (json_encode('Une page avec cette url existe déjà'));
        } else {
            return (json_encode(true));
        }
    }
    private function parse_dir_for($asked)
    {
        global $conf;
        $found = false;
        foreach(array("main","customer") as $dir_root)
        {
            if (is_dir($conf->main_document_root."/". $dir_root."/view"))
            {
                $dir = new DirectoryIterator($conf->main_document_root."/".$dir_root."/view");
                foreach ($dir as $fileinfo)
                {
                    if ($fileinfo->getFilename() == $asked.".php") $found = true;
                }
            }
        }
        return $found;
    }
    public function save_extra_params($post)
    {
        load_alternative_class ('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($post['id']);
        $error = false;
        foreach($post as $key=>$val)
        {
            if ($key == "id") continue;
            if ($key == "action") continue;
            if ($key == "load") continue;
            if ($key == "file")
            {
                $a= $p->set_extra_params($key, urlencode($val));
                if (!$a && !$error) $error = true;
            } else {
                $a= $p->set_extra_params($key, $val);
                if (!$a && !$error) $error = true;
            }

            if (preg_match('/^(.*path)_use_cache$/',$key,$arrMatch))
            {
                load_alternative_class('class/cache.class.php');
                $cache = new cache($this->db);
                $func = "build_".$arrMatch[1]."_cache";
                $has_prefix=false;
                $has_prefix = preg_replace('/path/','prefix',$arrMatch[1]);
                $prefix=false;

                if (isset($post[$has_prefix]) && $post[$has_prefix]."x" != "x")
                {
                    $prefix = $post[$has_prefix];
                }
                $c = $cache->$func($post[$arrMatch[1]],$post['id'],$prefix);
                if ($c)
                {
                    $res = $p->set_extra_params($arrMatch[1].'_cache_id', $c);
                    if (!$res && !$error) $error = true;
                }
            }
            if ($key == 'file')
            {
                $res = $p->set_extra_params('file_basename', basename($val));
                if (!$res && !$error) $error = true;
            }
        }
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");

        return $a;
    }
    public function set_group_publi($post)
    {
        $error = false;
        $requete = "DELETE FROM group_publication_page
                          WHERE page_refid = ".$post['id'];
        $sql = $this->db->query($requete);
        if (!$sql) $error = true;
        foreach($post['group_publication'] as $key => $val)
        {
            $requete = "INSERT INTO group_publication_page
                                    (group_publication_refid, page_refid)
                             VALUES (".$val.",".$post['id'].")";
            $sql = $this->db->query($requete);
            if (!$sql) $error = true;
        }
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->refresh_sitemap();
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function lock_page($post)
    {
        $error = false;
        load_alternative_class('class/section.class.php');
        global $current_user;
        $p = new page($this->db);
        $p->fetch($post['id']);
        $all = $p->get_all_section();
        foreach($all as $key=>$val)
        {
            $id = $val['id'];
            $s = new section($this->db);
            $s->fetch($id);
            $res = $s->set_is_locked_for_edition($current_user->get_id());
            if (!$res) $error = true;
        }
        $tmp =  $p->set_is_locked_for_edition($current_user->get_id());
        if (!$tmp) $error = true;
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $tmp;
    }
    public function unlock_page($post)
    {
        load_alternative_class('class/section.class.php');
        $p = new page($this->db);
        $p->fetch($post['id']);
        $all = $p->get_all_section();
        $error = false;
        foreach($all as $key=>$val)
        {
            $id = $val['id'];
            $s = new section($this->db);
            $s->fetch($id);
            $res = $s->set_is_locked_for_edition("0");
            if (!$res) $error = true;
        }
        $tmp = $p->set_is_locked_for_edition("0");
        if (!$tmp) $error = true;
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $tmp;
    }
    public function can_unlock($who_lock)
    {
        global $current_user;
        if ($current_user->get_id() > 0)
        {
            if ($who_lock == $current_user->get_id())
            {
                return true;
            }
        }
        if ($current_user->has_right('admin'))
        {
            return true;
        }
        if ($current_user->has_right('publication'))
        {
            return true;
        }
        $requete = "SELECT group_publication.id,
                           group_publication.is_admin
                      FROM group_publication,
                           group_publication_user
                     WHERE user_refid = ".$current_user->get_id(). "
                       AND group_publication.id = group_publication_user.group_publication_refid";
        $sql = $this->db->query($requete);
        $a = array();
        while($res=$this->db->fetch_object($sql))
        {
            if ($res->is_admin == 1) return true;
            $a[]=$res->id;
        }
        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_user
                     WHERE user_refid = ".$who_lock. "
                       AND group_publication.id = group_publication_user.group_publication_refid
                       AND group_publication.id IN (".join(',',$a).") ";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0) return true;
        return false;
    }
    public function can_edit($p)
    {
        global $current_user;
        if ($current_user->has_right('admin'))
        {
            return true;
        }

        if ($this->page_access->has_a_draft())
        {
            return false;
        }
        if ($current_user->get_id() > 0)
        {
            if ($p->get_author_refid() == $current_user->get_id())
            {
                return true;
            }
        }
        if (!$current_user->has_right('publication'))
        {
            return true;
        }
        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_user
                     WHERE user_refid = ".$current_user->get_id(). "
                       AND group_publication.id = group_publication_user.group_publication_refid";
        $sql = $this->db->query($requete);
        $a = array();
        while($res=$this->db->fetch_object($sql))
        {
            if ($res->is_admin == 1) return true;
            $a[]=$res->id;
        }
        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_user
                     WHERE user_refid = ".$p->get_author_refid. "
                       AND group_publication.id = group_publication_user.group_publication_refid
                       AND group_publication.id IN (".join(',',$a).") ";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0) return true;
        return false;
    }
    public function save_plugin($post)
    {
        $plug = $post['plugin'];
        $a=array();
        foreach($post as $key=>$val)
        {
            if ($key == "id") continue;
            if ($key == "action") continue;
            if ($key == "load") continue;
            $a[$key]=$val;
        }
        $a = json_encode($a);
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($post['id']);
        $array = json_decode($p->get_plugins(),false);
        if (isset($post['process']))
        {
            require_once('plugins/template/'.$plug."/".$plug.".class.php");
            $plugin = new $plug($this->db);
            if (method_exists($plugin, $post['process']))
            {
                $method = $post['process'];
                $a = $plugin->$method($post,$array);
            }
        }
        $array->$plug=$a;
        $res = $p->set_plugins(json_encode($array));
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return false;
    }
    public function can_approuve()
    {
        global $current_user;
        if ($current_user->has_right('admin'))
        {
            return true;
        }
        if (!$this->can_edit) return false;
        if (!$this->page_access->get_is_a_draft_for() > 0) return false;

        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_user,
                           group_publication_page
                     WHERE user_refid = ".$current_user->get_id(). "
                       AND group_publication.id = group_publication_user.group_publication_refid
                       AND group_publication.id = group_publication_page.group_publication_refid
                       AND group_publication_page.page_refid = ".$this->page_access->get_id();
        $sql = $this->db->query($requete);
        $a = array();
        while($res=$this->db->fetch_object($sql))
        {
            if ($res->is_admin == 1) return true;
            if ($res->can_approuve == 1 )return true;
            $a[]=$res->id;
        }
        return false;
    }
    public function publish_a_draft($post)
    {
        $id = $post['id'];
        $this->page_access->fetch($id);
        return $this->page_access->publish_draft();
    }
    public function publish($post)
    {
        $id = $post['id'];
        $this->page_access->fetch($id);
        return $this->page_access->set_publication_status_refid(2);
    }

    public function make_a_rev($post)
    {

        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($post['id']);
        $res = $p->make_a_revision();
        return $res;
    }
    public function edit_form($post)
    {
        load_alternative_class('class/form.class.php');
        $f = new form($this->db);
        $f->fetch($post['form_id']);
        $res = $f->set_type_connector($post['stock_result_form_type']);
        $a = array(
            'to' => $post['destinataire'],
            'from' => $post['emetteur'],
            'model' => $post['mail_model'],
            'field_to' => $post['field_to'],
            'field_from' => $post['field_from'],
            'reply_to' => $post['reply_to'],
            'cc' => $post['cc']
        );
        $a = json_encode($a);
        $res1 = $f->set_connector_option($a);
        if ($res1 && $res)
        {
            $this->message = _("Opération effectuée");
            return true;
        } else {
            $this->message = _("Opération echouée");
            return false;
        }
    }
}
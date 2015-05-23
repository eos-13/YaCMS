<?php
class model_submenu extends admin_common
{
    public $root;
    public $message = false;
    public $submenu = array();

    public function run()
    {
        $array = array();
        $this->find_root();
        $this->get_all();
        return $array;
    }
    public function get_all()
    {
        $requete = "SELECT page.title,
                           page.url,
                           page.id,
                           page.lang,
                           submenu.sublink,
                           submenu.content,
                           submenu.img_src,
                           submenu.active
                      FROM page
                 LEFT JOIN submenu ON submenu.page_refid = page.id
                     WHERE parent_refid = ".$this->root."
                       ";
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $this->submenu[]= array(
                    'title' => $res->title,
                    'url' => $res->url,
                    'content' => $res->content,
                    'active' => $res->active,
                    'id' => $res->id,
                    'img' => $res->img_src,
                    'submenu' => $this->find_sub($res->id,$res->sublink),
            );
        }
    }
    private function find_sub($id,$sublink)
    {
        $sublink = json_decode($sublink);
        $requete = "SELECT *
                      FROM page
                     WHERE parent_refid = ".$id."
                  ORDER BY title
                       ";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]=array(
                    'id' => $res->id,
                    'title' => $res->title
            );
            if (is_array($sublink) && in_array($res->id,$sublink))
            {
                $a[$res->id]['selected']=true;
            } else {
                $a[$res->id]['selected']=false;
            }
        }
        return $a;
    }
    private function find_root()
    {
        global $editlang,$conf;
        $this->root = 1;
        $requete = "SELECT lang,
                           id
                      FROM page
                     WHERE url = 'home'";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            while ($res = $this->db->fetch_object($sql))
            {
                if ($res->lang == $editlang)
                {
                    $this->root = $res->id ;
                    return true;
                } else {
                    if ($res->lang."x" == "x" && $editlang == $conf->default_lang)
                    {
                        $this->root = $res->id;
                        return true;
                    }
                }
            }
            $this->root = 1;
            return false;
        } else {
            return false;
        }
    }
    public function save($post)
    {
        $requete = "INSERT INTO submenu
                                (`content`,`img_src`,`page_refid`, `sublink`, `active`)
                         VALUES ('".addslashes($post['content-'.$post['id']])."',
                                 '".addslashes($post['img_src-'.$post['id']])."',
                                  ".$post['id'].",
                                 '".json_encode($post['sublinks'])."',
                                  ".$post['active-'.$post['id']]."
                                 )
         ON DUPLICATE KEY UPDATE  `content`='".addslashes($post['content-'.$post['id']])."',
                                  `img_src`= '".addslashes($post['img_src-'.$post['id']])."',
                                  `sublink`= '".addslashes(json_encode($post['sublinks']))."',
                                  `active` = ".$post['active-'.$post['id']]."      ";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->message = _("Opération effectuée");
            return true;
        } else {
            $this->message = _("Opération echouée");
            return false;
        }
    }
}
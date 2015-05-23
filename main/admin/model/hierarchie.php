<?php
class model_hierarchie extends admin_common
{
    public $id;
    public $message = false;
    public $all_page;
    public $all_page_meta;
    public $root = 1;

    public function run()
    {
        $this->find_root();
        $this->get_all();
        return (true);
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
    private function get_all()
    {
        global $conf;
        global $editlang;
        $requete = "SELECT page.id,
                           page.title,
                           page.parent_refid,
                           page.url,
                           page.lang
                      FROM page
                 LEFT JOIN menu_hierarchy ON page.id = menu_hierarchy.page_refid
                     WHERE active = 1
                       AND in_menu = 1
                  ORDER BY menu_hierarchy.level";
        $sql = $this->db->query($requete);
        $maxlevel = 1;
        $aChild = array();
        while($res = $this->db->fetch_object($sql))
        {
            if ($res->id == $this->root) continue;
            $obj = $this->hierarchy($res->id);
            if ($res->lang != $editlang)
            {
                if ($res->lang."x"=="x" && $editlang != $conf->default_lang)
                    continue;
                elseif ($res->lang."x"=="x" && $editlang == $conf->default_lang)
                    $ok=1;
                else continue;
            }
            $this->all_page[]=array(
                    'id' => $res->id,
                    'parent_page_refid' => (isset($obj) && isset($obj->parent_page_refid) && $obj->parent_page_refid > 0?$obj->parent_page_refid :false),
                    'title' => $res->title,
                    'url' => preg_replace('/\/$/','',$conf->main_base_path)."/". $res->url,
                    'level' => (isset($obj) && isset($obj->level) && $obj->level > 0?$obj->level:false),
                    'top' => (isset($obj->top) && $obj->top > 0?$obj->top:100),
                    'left' => (isset($obj->left) && $obj->left > 0 ? $obj->left: 150),
                    "endpoint_pos" => (isset($obj->endpoint_pos) && $obj->endpoint_pos?$obj->endpoint_pos:"BottomCenter")
            );
            if (isset($obj) && isset($obj->level) && $obj->level > 0)
            {
                $maxlevel = max($maxlevel,$obj->level);
            }
        }
        $this->all_page_meta['maxLevel']=$maxlevel;
        $this->all_page_meta['countChild']=$aChild;
    }

    private function hierarchy($id)
    {
        $requete = "SELECT *
                      FROM menu_hierarchy
                     WHERE page_refid = ".$id;
        $sql = $this->db->query($requete);
        $res = $this->db->fetch_object($sql);
        return $res;
    }
    private function find_level($id)
    {
        if (!$id) return 0;
        $requete = "SELECT IFNULL(level,0) as level
                      FROM menu_hierarchy
                     WHERE page_refid = ".$id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            return $res->level;
        } else {
            return 1;
        }
    }
    public function newConnection($post)
    {
        $level = $this->find_level($post['s']) + 1;
        $left = (isset($post['left'])?$post['left']:150);
        $left = preg_replace('/px$/',"",$left);
        $top = (isset($post['top'])?$post['top']:150);
        $top = preg_replace('/px$/',"",$top);
        $requete = "INSERT INTO menu_hierarchy
                                (parent_page_refid,page_refid,level,endpoint_pos,`top`,`left`)
                         VALUES (".$post['s'].",".$post['t'].",".$level.", '".addslashes($post['endpoint_pos'])."', '".addslashes($top)."', '".addslashes($left)."')
        ON DUPLICATE KEY UPDATE parent_page_refid = ".$post['s'].", level = ".$level.", endpoint_pos ='".addslashes($post['endpoint_pos'])."', `left` = '".addslashes($left)."', `top` = '".addslashes($top)."' ";
        $sql = $this->db->query($requete);
        require_once("class/page.class.php");
        $p = new page($this->db);
        $p->fetch($post['t']);
        $p->set_parent_refid($post["s"]);
        if ($sql) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $sql;
    }
    public function delConnection($post)
    {
        $requete = "DELETE FROM menu_hierarchy
                          WHERE parent_page_refid = ".$post['s']."
                            AND page_refid = ".$post['t'];
        $sql = $this->db->query($requete);
        if ($sql) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $sql;
    }
    public function moveConnection($post)
    {
        $level = $this->find_level($post['sus']) + 1;
        $requete = "UPDATE menu_hierarchy
                       SET parent_page_refid = ".$post['sus'].",
                           endpoint_pos = '".addslashes($post['endpoint_pos'])."'
                     WHERE page_refid = ".$post['s']."
                       AND parent_page_refid = ".$post['t'];
                $sql = $this->db->query($requete);
        require_once("class/page.class.php");
        $p = new page($this->db);
        $p->fetch($post['t']);
        $p->set_parent_refid($post["s"]);
        if ($sql) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $sql;
    }
    public function storePos($post)
    {
        $top = preg_replace('/,/','.',round($post['top'],2));
        $left = preg_replace('/,/','.',round($post['left'],2));
        $id = $post['id'];
        $requete = "INSERT INTO menu_hierarchy
                                (page_refid, top, `left`)
                         VALUES (".$id.",'".$top."','".$left."')
               ON DUPLICATE KEY UPDATE top='".$top."', `left`='".$left."' ";
        $sql = $this->db->query($requete);
        if ($sql) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $sql;
    }
}
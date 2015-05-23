<?php
class model_map extends admin_common
{
    public $message = false;
    public $root;

    public function run()
    {
        $this->find_root();
        $array=array();
        $array['noparent']=$this->noparent_page();
        $array['allpage']=$this->all_page();
        return $array;
    }
    public function json()
    {
        $array=array();
        $this->find_root();
        $array['json']=$this->draw_tree();
        return $array;
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
    private function draw_tree()
    {
        $child = $this->draw_tree_recurs($this->root);
        $orphan = $this->add_orphan_page();
        $tmp = array(
            0 => array(
                    "name" => "In Menu",
                    "uid"  => $this->root,
                    "children" => $child,
                    "ondisk" => false
            ),
            1 => array(
                    "name" => "orphan",
                    "uid"  => false,
                    "children" => $orphan,
                    "ondisk" => false
            )
        );
        $std = new make_json($this->root,"home",$tmp);
        return json_encode($std);
    }
    private function add_orphan_page()
    {
        $child = array();
        $requete = "SELECT title,
                           id,
                           page_on_disk,
                           lang
                      FROM page
                     WHERE parent_refid is null
                       AND id <> ".$this->root;
        $sql = $this->db->query($requete);
        global $editlang,$conf;
        while ($res = $this->db->fetch_object($sql))
        {
            if ($res->lang == $editlang || $res->lang."x" == "x" && $editlang == $conf->default_lang || $res->page_on_disk == 1 )
                $child[]=array(
                        "name" => $res->title,
                        "uid" => $res->id ,
                        "style" => ($res->page_on_disk==1?"ondisk":false));
        }
        return $child;
    }
    private function draw_tree_recurs($id)
    {
        $requete = "SELECT *
                      FROM page
                     WHERE parent_refid = ".$id;
        $sql = $this->db->query($requete);
        $tmp = array();
        $iter=0;
        if (!$sql || $this->db->num_rows($sql) == 0)
        {
            return false;
        }
        while ($res = $this->db->fetch_object($sql))
        {
            $tmp1 = $this->draw_tree_recurs($res->id);
            $tmp1a = $this->draw_tree_recurs_section($res->id);

            if (is_array($tmp1) && is_array($tmp1a))
            {
                $tmp1 = array_merge($tmp1,$tmp1a);
            } elseif (is_array($tmp1a) && !$tmp1){
                $tmp1a = $tmp1;
            }


            if ($tmp1)
            {
                $tmp[] =  array("name" => $res->title,
                                "uid" => $res->id,
                                "children" => $tmp1,
                                "style" => ($res->page_on_disk==1?"ondisk":false)
                );
            } else {
                $tmp2 = $this->draw_tree_recurs_section($res->id);
                if ($tmp2){
                    $tmp[] =  array("name" => $res->title,
                            "uid" => $res->id,
                            "children" => $tmp2,
                            "ondisk" => false
                            );
                } else {
                    $tmp[] =array("name" => $res->title,
                                  "uid" => $res->id,
                                  "style" => ($res->page_on_disk==1?"ondisk":false)

                            );
                }
            }
        }
        return ($tmp);
    }
    private function draw_tree_recurs_section($id)
    {
        $requete = "SELECT *
                      FROM section
                     WHERE page_refid = ".$id;
        $sql = $this->db->query($requete);
        if (!$sql || $this->db->num_rows($sql) == 0) return false;
        $tmp = array();
        while($res = $this->db->fetch_object($sql))
        {
            $tmp[]=array(
                    "name" => $res->title,
                    "uid" => $res->id,
                    "style" => "section");
        }
        return $tmp;
    }
    private function noparent_page()
    {
        global $editlang;
        $requete = "SELECT id
                      FROM page
                     WHERE parent_refid is NULL
                       AND id <> 1
                       AND (lang IS NULL OR lang = '".addslashes($editlang)."')
                  ORDER BY title";
        $sql = $this->db->query($requete);
        $array=array();
        while($res = $this->db->fetch_object($sql))
        {
            array_push($array, $res->id);
        }
        return $array;
    }
    private function all_page()
    {
        global $editlang;
        $requete = "SELECT id
                      FROM page
                     WHERE lang is NULL
                        OR lang = '".addslashes($editlang)."'
                  ORDER BY title";
        $sql = $this->db->query($requete);
        $array = false;
        while($res=$this->db->fetch_object($sql)){
            $array[]=$res->id;
        }
        return $array;
    }
}
class make_json
{
    public $name;
    public $uid;
    public $children;
    public $style;

    public function __construct($uid,$name,$children=false,$style=false)
    {
        $this->name = $name;
        $this->uid = $uid;
        $this->style=$style;
        if ($children)
        {
            $this->children = array();
            foreach($children as $key=>$val)
            {
                $tmp = new make_json($val['uid'],
                                     $val['name'],
                                     (isset($val['children'])?$val['children']:false),
                                     (isset($val['style'])?$val['style']:false));
                array_push($this->children,$tmp);
            }

        }
    }
}
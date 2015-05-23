<?php
load_alternative_class('class/common_soap_object.class.php');
class api_menu extends common_soap_object
{
    protected $db;
    public $menu;
    public $log;

    public function __construct($db)
    {
        $this->db = $db;
        global $log;
        $this->log = $log;
    }
    /**
     * @desc Get all menu data
     * @return array $datas[]
     */
    public function draw_tree()
    {
        $html = $this->menu();
        return array('menu' => $html,
                     'css' => $this->get_css(),
                     'js' => $this->get_js(),
                     'js_code' => $this->get_js_code());
    }
    private function menu($jmenu=true)
    {
        $this->menu = "";
            if ($jmenu)
            {
                $this->menu = '<center><ul id="jMenu">';
            } else {
                $this->menu = "<ul id='menu'>";
            }
            $iter=0;
            $requete = "SELECT id,
                               url,
                               title,
                               lang
                          FROM page
                         WHERE url='home'
                           AND in_menu = 1
                      ORDER BY id";
            $sql = $this->db->query($requete);
            load_alternative_class('class/page.class.php');
            $p = new page($this->db);
            global $lang, $conf;
            while ($res = $this->db->fetch_object($sql))
            {
                if ($lang != $res->lang)
                {
                    if ($res->lang."x" == "x" && $lang == $conf->default_lang)
                    {
                        $ok=1;
                    } else {
                        continue;
                    }
                }
                $granted = $p->check_group_publication($res->id,false);
                if (!$granted) continue;
                if ($jmenu)
                {
                    $this->menu.="<li class='col_".$iter."'><a href='".$res->url."'>".$res->title."</a>";
                } else {
                    $this->menu.="<li><a href='".$conf->main_url_root.$res->url."'>".$res->title."</a>";
                }
                $this->menu .= $this->menu_recurs($res->id,$res->url,$res->title,$jmenu,1);
                $this->menu .= "</li>";
            }
            $this->menu .= "</li>";
            $this->menu .= "</ul>";
            if ($jmenu) $this->menu .= "</center>";

        return ($this->menu);
    }
    private function menu_recurs($parent_id,$url,$name,$jmenu=true,$level)
    {
        $requete = "SELECT page.id,
                           page.url,
                           page.title,
                           page.lang,
                           menu_hierarchy.level
                      FROM page,
                           menu_hierarchy
                     WHERE parent_refid = ".$parent_id."
                       AND in_menu = 1
                       AND page.id = menu_hierarchy.page_refid
                  ORDER BY top,level,id";
        $sql1 = $this->db->query($requete);
        global $lang, $conf;
        if ($sql1 && $this->db->num_rows($sql1) > 0)
        {
            load_alternative_class('class/page.class.php');
            $p = new page($this->db);
            //var_dump($level);
            if ($level > 1)
            {
                $this->menu .= "<ul>";
            }
            if ($jmenu)
                $this->menu.="<li>";
            while ($res1 = $this->db->fetch_object($sql1))
            {
                if ($lang != $res1->lang)
                {
                    if ($res1->lang."x" == "x" && $lang == $conf->default_lang)
                    {
                        $ok=1;
                    } else {
                        continue;
                    }
                }
                $granted = $p->check_group_publication($res1->id,false);
                if (!$granted) continue;

                $this->menu.="<li><a href='".$res1->url."'>".$res1->title."</a>";
                $this->menu .= $this->menu_recurs($res1->id,$res1->url,$res1->title,$jmenu,$res1->level);
                $this->menu .= "</li>";
            }
            if ($level >1)
            {
                $this->menu .= "</ul>";
            }
        }
    }

    private function get_js()
    {
        return array('jquery','jMenu.jquery');
    }
    private function get_css()
    {
        return array('jmenu');
    }
    private function get_js_code()
    {
        $js = <<<EOF
        jQuery(document).ready(function(){
            jQuery("#jMenu").jMenu({
                openClick : false,
                ulWidth : 'auto',
                effects : {
                    effectSpeedOpen : 150,
                    effectSpeedClose : 150,
                    effectTypeOpen : 'slide',
                    effectTypeClose : 'slide',
                    effectOpen : 'linear',
                    effectClose : 'linear'
                },
                animatedText : true
            });
        });
EOF;
        return $js;
    }
}
?>
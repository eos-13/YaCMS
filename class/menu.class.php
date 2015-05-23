<?php
class menu
{
    protected $db;
    public $menu;
    public $log;
    private $submenu_count = 0;

    public function __construct($db)
    {
        $this->db = $db;
        global $log;
        $this->log = $log;
    }
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
                $this->menu = '<center><ul id="jMenu" class="cbp-hrmenu">';
            } else {
                $this->menu = "<ul id='menu' class='cbp-hrmenu'>";
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

                global $conf;
                $submenu=false;
                if ($conf->use_submenu == "on")
                {
                    $requete = "SELECT *
                                  FROM submenu
                                 WHERE page_refid = ".$res1->id;
                    $sql = $this->db->query($requete);
                    if ($sql && $this->db->num_rows($sql) > 0)
                    {
                        $res = $this->db->fetch_object($sql);
                        if ($res->active == 1)
                        {
                            $submenu=array(
                                'content' => $res->content,
                                'img_src' => $res->img_src,
                                'sublinks' => json_decode($res->sublink,true),
                            );
                        }
                    }
                }
                if ($submenu)
                {
                    $this->menu.="<li class='submenu'><a href='#'>".$res1->title."</a>";
                    $this->menu .= '<div id="menu_ctn_'.$this->submenu_count.'" class="cbp-hrsub">';
                    $this->menu .= '<div class="cbp-hrsub-inner">';
                    $this->menu .= '<div>';
                    if ($submenu['img_src']."x" != "x")
                        $this->menu .= "<img src='".$conf->main_base_path."/files/".$submenu['img_src']."'/>";
                    if ($submenu['content']."x" != "x")
                        $this->menu .= "<div>".$submenu['content']."</div>";
                    if (is_array($submenu['sublinks']) && count($submenu['sublinks']) > 0)
                        foreach($submenu['sublinks'] as $val)
                        {
                            $tmpp = new page($this->db);
                            $tmpp->fetch($val);
                            $this->menu .= "<a href='".$tmpp->get_url()."'>".$tmpp->get_title()."</a>";
                        }
                    $this->menu .= '</div>';
                    $this->submenu_count ++;
                    $this->menu .= '</div>';
                    $this->menu .= '</div>';
                    $this->menu .= "</li>";
                } else {
                    $this->menu.="<li><a href='".$res1->url."'>".$res1->title."</a>";
                    $this->menu .= $this->menu_recurs($res1->id,$res1->url,$res1->title,$jmenu,$res1->level);
                    $this->menu .= "</li>";
                }
            }
            if ($level >1)
            {
                $this->menu .= "</ul>";
            }
        }
    }

    public function get_js()
    {
        if ($this->submenu_count > 0)
        {
            return array('jquery','jMenu.jquery','cbpHorizontalMenu.min');
        } else {
            return array('jquery','jMenu.jquery');
        }
    }
    public function get_css()
    {
        if ($this->submenu_count > 0)
        {
            return array('jmenu','cbp-horizontal-menu');
        } else {
            return array('jmenu');
        }
    }
    public function get_js_code()
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
        if ($this->submenu_count > 0)
        {
            $js .= <<<EOF
            jQuery(document).ready(function(){
                cbpHorizontalMenu.init();
            });

EOF;
        }
        return $js;
    }
}
?>
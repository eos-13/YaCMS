<?php
load_alternative_class('class/plugins.class.php');
class tooltips extends plugins
{
    protected $name = "Tooltips";
    protected $version = "1.0";
    protected $path = "tooltips";
    private $css;
    private $js;
    private $css_code;
    private $js_code;
    private $js_external;
    private $params;

    public function make_form($url,$val,$form_tab_num,$data=false)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $html = "<div>".dcgettext('plugins', "Tooltips",LC_ALL)."</div>";
        $html .= "<div id='tabs_tooltip'>";
        $html .= "<ul>";
        $html .= "<li><a href='#tooltip'>".dcgettext('plugins', "Tooltips",LC_ALL)."</a></li>";
        $html .= "<li><a href='#params'>".dcgettext('plugins', "Paramètres",LC_ALL)."</a></li>";
        $html .= "</ul>";
        $html .= "<div id='tooltip'>";
        $html .= $this->params_add_form($url,$this->get_all_params(),$val,$form_tab_num);
        $html .= "</div>";
        $html .= "<div id='params'>";
        $html .= "<form action=\"".$url."\" METHOD='post'>";
        $html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "<input name='process' value='save_tooltips' type='hidden' />";
        $html .= $this->params_form_builder($this->get_all_params(),$val);
        $html .= "<br/><button>".dcgettext('plugins', "OK",LC_ALL)."</button>";
        $html .= "</form>";
        $html .= "</div>";
        $html .= "</div>";

        textdomain("messages");

       return $html;
    }
    public function get_display_js()
    {
            return array('jquery','jquery.simpletooltip.min');
    }
    public function get_display_css()
    {
            return array('tooltip');
    }
    public function get_display_css_code()
    {

        return "";
    }
    public function get_js()
    {
        return array('../../../plugins/template/tooltips/js/tinymce_tooltips');
    }
    public function get_css_code()
    {
        $css = <<<EOF
        #plugin-tabs-tooltips { padding: 1em 0 !important; };
EOF;
        return $css;
    }
    public function set_js_code($js_code)
    {
        $this->js_code .= $js_code;
    }
    public function get_js_code()
    {
        $js = "jQuery(document).ready(function()
               {
                   jQuery('#tabs_tooltip').tabs();
                   jQuery('#accordion-tooltips').accordion(
                   {
                       heightStyle: 'content',
                       collapsible: true,
                       active: false
                   });
               });";
        $js .= "extra_tme_plugin.tooltips = 'plugins/template/tooltips/js/tinymce_tooltips.js' ;";
        $js .= $this->js_code;
        return $js;
    }

    private function get_all_params()
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $array = array(
                'click' => array (
                    'default' =>'true',
                    "possible_value" => "true|false",
                    "help" => dcgettext('plugins', 'Faut il cliquer pour afficher un tooltip ?',LC_ALL),
                ),
                'delay' => array (
                        'default' =>'0.5',
                        "help" => dcgettext('plugins', 'Combien de temps (s) avant la disparition du tooltip',LC_ALL),
                ),
                'showEffect' => array (
                        'default' =>'fadeIn',
                        "help" => dcgettext('plugins', 'Effet d\'affichage du tooltip',LC_ALL),
                ),
                'hideEffect' => array (
                        'default' =>'fadeOut',
                        "help" => dcgettext('plugins', 'Effet de disparition du tooltip',LC_ALL),
                ),
        );
        textdomain("messages");

        return $array;
    }
    public function get_params($page)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $ret = false;
        $default = $this->get_all_params();
        foreach ($default as $key=>$val)
        {
            $ret[$key]=$val['default'];
        }
        $params_json = $page->get_plugins();
        if ($params_json."x" != "x")
        {
            $params_json = json_decode($params_json);
            $params = json_decode($params_json->tooltips);
            if (is_array($params) && count($params) > 0)
            {
                foreach ($params as $key=>$val)
                {
                    if ( $val."x" != "x")
                        $ret[$key]=$val;
                }
            } else if (is_object($params))
            {
                foreach ($params as $key=>$val)
                {
                    if (is_object($val))
                    {
                        $ret[$key]=$val;
                    } elseif ( $val."x" != "x") {
                        $ret[$key]=$val;
                    }
                }
            }
        }
        $this->params = $ret;
        textdomain("messages");
        return $ret;
    }
    public function params_add_form($url,$params,$values,$form_tab_num)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $list=array();
        $html = "<div id='accordion-tooltips'>";
        if (is_object($values->tooltips))
        {
            foreach($values->tooltips as $key=>$val)
            {
                if (is_numeric($key))
                {
                    $html .= "<h3>".$val->title."</h3>";
                    $html .= "<div>";
                    $html .= "    <form action=\"".$url."\" METHOD='post'>";
                    $html .= "        <input name='process' value='save_tooltips' type='hidden' />";
                    $html .= "        <input type='hidden' name='load' value='".$form_tab_num."'/>";
                    $html .= "        <table width='100%' style='border-collapse: collapse;' CELLPADDING=5>";
                    $html .= "            <tr><th>".dcgettext("plugins", "Titre",LC_ALL)."</th>";
                    $html .= "                <td>";
                    $html .= "                    <input id='title_tooltips_".$key."' name='title_tooltips_".$key."' value='".$val->title."'>";
                    $html .= "                </td>";
                    $html .= "            </tr>";
                    $html .= "            <tr><th>".dcgettext("plugins","Contenu",LC_ALL)."</th>";
                    $html .= "                <td>";
                    $html .= "                    <textarea id='tooltips_".$key."' name='tooltips_".$key."'><div class='tooltip big els'>".$val->content."</div></textarea>";
                    $html .= "                </td>";
                    $html .= "            </tr>";
                    $html .= "            <tr>";
                    $html .= "                <td colspan='2'>";
                    $html .= "                    <button>".dcgettext("plugins","Save",LC_ALL)."</button><button name='del' value='".$key."'>".dcgettext("plugins","Delete",LC_ALL)."</button>";
                    $html .= "                </td>";
                    $html .= "            </tr>";
                    $html .= "        </table>";
                    $html .= "    </form>";
                    $html .= "</div>";
                    $list[] = $key;
                }
            }
        }
        $iter = max($list) + 1;
        $list[] = $iter;
        $html .= "<h3>".dcgettext("plugins","Nouveau",LC_ALL)."</h3>";
        $html .= "<div>";
        $html .= "    <form action=\"".$url."\" METHOD='post'>";
        $html .= "        <input name='process' value='save_tooltips' type='hidden'/>";
        $html .= "        <input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "        <table width='100%' style='border-collapse: collapse;' CELLPADDING=5>";
        $html .= "            <tr><th>".dcgettext("plugins","titre",LC_ALL)."</th>";
        $html .= "                <td>";
        $html .= "                    <input name='title_tooltips_".$iter."' id='title_tooltips_".$iter."' value='' placeholder='".dcgettext("plugins",'Title',LC_ALL)."'>";
        $html .= "                </td>";
        $html .= "            </tr>";
        $html .= "            <tr><th>".dcgettext("plugins","Contenu",LC_ALL)."</th>";
        $html .= "                <td>";
        $html .= "                    <textarea id='tooltips_".$iter."' name='tooltips_".$iter."'><div class='tooltip big els'></div></textarea>";
        $html .= "                </td>";
        $html .= "            </tr>";
        $html .= "            <tr>";
        $html .= "                <td colspan='2'>";
        $html .= "                    <button>".dcgettext("plugins","Save",LC_ALL)."</button>";
        $html .= "                </td>";
        $html .= "            </tr>";
        $html .= "        </table>";
        $html .= "    </form>";
        $html .= "</div>";
        $html .= "</div>";
        $js_var = "";
        foreach($list as $val)
        {
                $js_var .= " , tooltips_".$val;
        }
        $js = " all_tinymce += '".$js_var." '; \n";
        $js .= " var usecustomwrapper={'element':'div','class_css':['tooltip', 'big', 'els'], 'inst': 'tooltips' }; ";
        $this->set_js_code($js);
        textdomain("messages");
        return $html;
    }
    public function save_tooltips($post,$allplugins_data)
    {
        $tooltips_data = json_decode($allplugins_data->tooltips);
        //$allplugins_data = json_decode($allplugins_data);
        //On récupère tous les paramètres enregistrés
        foreach($post as $key => $val)
        {
            if (preg_match('/^tooltips_([0-9]*)$/',$key,$arrMatch))
            {
                if (isset($post['del']) && $post['del'] == $arrMatch[1])
                {
                    unset($tooltips_data->tooltips->$arrMatch[1]);
                } else {
                    $title = $post['title_tooltips_'.$arrMatch[1]];
                    $content = $val;
                    $tooltips_data->tooltips->$arrMatch[1] = array(
                            'title' => $title,
                            'content' => $content,
                    );
                }
            } else {
                foreach($post as $key=>$val)
                {
                    if ($key == "id") continue;
                    if ($key == "action") continue;
                    if ($key == "load") continue;
                    if ($key == "process") continue;
                    if ($key == "del") continue;
                    if (preg_match('/^title_tooltips_/',$key)) continue;
                    if (preg_match('/^tooltips_/',$key)) continue;
                    $tooltips_data->$key=$val;
                }
//                $a = json_encode($a);
            }
        }
        return json_encode($tooltips_data);
    }
}


?>
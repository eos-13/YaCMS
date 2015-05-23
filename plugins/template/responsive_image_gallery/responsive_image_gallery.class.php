<?php
load_alternative_class('class/plugins.class.php');
class responsive_image_gallery extends plugins
{
    protected $name = "Responsive Image Gallery";
    protected $version = "1.0";
    protected $path = "responsive_image_gallery";
    private $params;

    public function make_form($url,$val,$form_tab_num,$data=false)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $html = "<form action=\"".$url."\" METHOD='post'>";
        $html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "<div>".dcgettext('plugins',"Responsive Image Gallery",LC_ALL)."</div>";
        //$html .= $this->params_form_builder($this->get_all_params(),$val);
        //$html .= "<br/><button>".dcgettext('plugins','OK',LC_ALL)."</button>";
        $html .= "</form>";
        textdomain("messages");
        return $html;
    }
    public function get_display_js()
    {
        return array('gallery','jquery.easing.1.3','jquery.elastislide','jquery.tmpl.min');
    }
    public function get_display_css()
    {
        return array("elastislide","responsive_image_gallery");
    }
    public function get_display_css_code()
    {


    }
    private function get_all_params()
    {
//         bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
//         textdomain("plugins");
//         $array = array(
//                 'interval' => array (
//                         'default' => 'false',
//                         "help" => dcgettext('plugins',"Move to another block on intervals.",LC_ALL),
//                         "possible_value" => "true|false"
//                         ),
//         );
//         textdomain("messages");
        return array();
    }
    public function get_params($page)
    {
//         bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
//         textdomain("plugins");
//         $ret = false;
//         $default = $this->get_all_params();
//         foreach ($default as $key=>$val)
//         {
//             $ret[$key]=$val['default'];
//         }
//         $params_json = $page->get_plugins();
//         $params_json = json_decode($params_json);
//         if ($params_json)
//         {
//             $params = json_decode($params_json->responsive_image_gallery);
//             foreach ($params as $key=>$val)
//             {
//                 if ($val)
//                     $ret[$key]=$val;
//             }
//         }
//         $this->params = $ret;
//         textdomain("messages");
//         return $ret;
    }
}
?>
<?php
load_alternative_class('class/plugins.class.php');
class thumbnelia extends plugins
{
    protected $name = "Thumbnelia";
    protected $version = "1.0";
    protected $path = "thumbnelia";
    private $params;

    public function make_form($url,$val,$form_tab_num,$data=false)
    {

        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $html = "<form action=\"".$url."\" METHOD='post'>";
        $html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "<div>".dcgettext('plugins', "Thumbnelia Plugin",LC_ALL)."</div>";
        $html .= $this->params_form_builder($this->get_all_params(),$val);
        $html .= "<br style='clear:both;'/><button>".dcgettext('plugins', "OK",LC_ALL)."</button>";
        $html .= "</form>";

        textdomain("messages");

       return $html;
    }
    public function get_display_js()
    {
        if ($this->params['use_fancy_box'] == "true")
            return array('jquery','thumbnelia','jquery.fancybox');
        else
            return array('jquery','thumbnelia');
    }
    public function get_display_css()
    {
        if ($this->params['use_fancy_box'] == "true")
            return array('thumbnelia','jquery.fancybox');
        else
            return array('thumbnelia');
    }
    public function get_display_css_code()
    {
        $css = "";
        $css .= ".thumbelina-but.horiz {";
        $css .= "    width: 20px;";
        $css .= "    height: ". $this->params['height'] ."px;";
        $css .= "    line-height: ".$this->params['height'] . " px;";
        $css .= "    top:-1px;";

        $css .= "}";
        $css .= ".thumbelina-but.vert {";
        $css .= "    left:-1px;";
        $css .= "    height: 20px;";
        $css .= "    line-height: 20px;";
        $css .= "    width:". $this->params['width']  ."px";
        $css .= "}";

        if ($this->params['imagemaxheight'] > 0 || $this->params['imagemaxwidth'] > 0)
        {
            $css .= ".thumbelina li img {";
            if ($this->params['imagemaxheight'] > 0)
            {
                $css .= "    max-height:".$this->params['imagemaxheight']."px ;";
            }
            if ($this->params['imagemaxwidth'] > 0)
            {
                $css .= "    max-width:".$this->params['imagemaxwidth']."px ;";
            }
            $css .= "}";
        }


        $css .= ".horizontal#slider  {";
        $css .= "    position:relative;  /* Containers need relative or absolute position. */";
        $css .= "    margin-left:20px;";
        $css .= "    width:".$this->params['width']."px;";
        $css .= "    height:".$this->params['height']."px;";
        $css .= "    border-top:1px solid #aaa;";
        $css .= "    border-bottom:1px solid #aaa;";
        $css .= "}";
        $css .= ".vertical#slider  {";
        $css .= "    float:left;";
        $css .= "    margin-top:20px;";
        $css .= "    width:".($this->params['width'] - 1 )."px;";
        $css .= "    height:".($this->params['height'] - 1 )."px;";
        $css .= "    border-left:1px solid #aaa;";
        $css .= "    border-right:1px solid #aaa;";
        $css .= "    position:relative;";
        $css .= "    background-color:#fff;";
        $css .= "}";
        return $css;
    }
    private function get_all_params()
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $array = array(
                'orientation' => array (
                        'default' => "horizontal",
                        "help" => dcgettext('plugins',"Orientation mode, horizontal or vertical",LC_ALL),
                        "possible_value" => "vertical|horizontal"
                ),
                'easing' => array (
                        'default' => 8,
                        "help" => dcgettext('plugins',"Amount of easing (min 1) larger = more drift",LC_ALL),
                ),
                'maxSpeed' => array (
                        'default' => 5,
                        "help" => dcgettext('plugins',"Max speed of movement (pixels per cycle).",LC_ALL),
                ),
                'bwdBut' => array (
                        'default' => "jQuery(\"#slider .rwd\")",
                        "help" => dcgettext('plugins',"jQuery element used as backward button.",LC_ALL)
                ),
                'fwdBut' => array (
                        'default' => "jQuery(\"#slider .fwd\")",
                        "help" => dcgettext('plugins',"jQuery element used as forward button.",LC_ALL)
                ),
                'imagewidth' => array (
                        'default' => "100",
                        "help" => dcgettext('plugins',"Image width (0 auto)",LC_ALL)
                ),
                'imageheight' => array (
                        'default' => "0",
                        "help" => dcgettext('plugins',"Image height (0 auto)",LC_ALL)
                ),
                'imagemaxwidth' => array (
                        'default' => "0",
                        "help" => dcgettext('plugins',"Image max width (0 auto)",LC_ALL)
                ),
                'imagemaxheight' => array (
                        'default' => "0",
                        "help" => dcgettext('plugins',"Image max height (0 auto)",LC_ALL)
                ),
                'width' => array (
                        'default' => "500",
                        "help" => dcgettext('plugins',"Slider width",LC_ALL)
                ),
                'height' => array (
                        'default' => "250",
                        "help" => dcgettext('plugins',"Slider height",LC_ALL)
                ),
                'use_fancy_box' => array (
                        'default' => "false",
                        "help" => dcgettext('plugins',"Use fancybox",LC_ALL),
                        "possible_value" => "true|false"
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
            $params = json_decode($params_json->thumbnelia);
            if (is_array($params) && count($params) > 0)
            {
                foreach ($params as $key=>$val)
                {
                    if ($val && $val."x" != "x")
                        $ret[$key]=$val;
                }
            } else if (is_object($params))
            {
                foreach ($params as $key=>$val)
                {
                    if ($val && $val."x" != "x")
                        $ret[$key]=$val;
                }
            }
        }
        $this->params = $ret;
        textdomain("messages");
        return $ret;
    }
}


?>
<?php
load_alternative_class('class/plugins.class.php');
class circularslider extends plugins
{
    protected $name = "circularSlider";
    protected $version = "1.0";
    protected $path = "circularslider";
    private $params;

    public function make_form($url,$val,$form_tab_num,$data=false,$pid=false)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $html = "<form action=\"".$url."\" METHOD='post'>";
        $html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "<div>".dcgettext('plugins',"Circular Slider Plugin",LC_ALL)."</div>";
        $html .= $this->params_form_builder($this->get_all_params(),$val);
        $html .= "<br/><button>".dcgettext('plugins','OK',LC_ALL)."</button>";
        $html .= "</form>";
        textdomain("messages");
        return $html;
    }
    public function get_display_js()
    {
        if ($this->params['use_fancy_box'] == "true")
            return array('jquery','jquery.tinycircleslider','jquery.fancybox');
        else
            return array('jquery','jquery.tinycircleslider');
    }
    public function get_display_css()
    {
        if ($this->params['use_fancy_box'] == "true")
            return array('circularslider','jquery.fancybox');
        else
            return array('circularslider');
    }
    public function get_display_css_code()
    {
        global $conf;
        $css = "";
        $css .= '#circleslider {';
        $css .= '    height: '.$this->params['imgheight'].'px;';
        $css .= '    padding: 25px;';
        $css .= '    position: relative;';
        $css .= '    width: '.$this->params['imgwidth'].'px';
        $css .= '}';

        $css .= '#circleslider .viewport {';
        $css .= '    height: '.$this->params['imgheight'].'px;';
        $css .= '    position: relative;';
        $css .= '    margin: 0 auto;';
        $css .= '    overflow: hidden;';
        $css .= '    width: '.$this->params['imgwidth'].'px';
        $css .= '}';

        $css .= '#circleslider .overview {';
        $css .= '    background-color: #efefef;';
        $css .= '    position: absolute;';
        $css .= '    width: 798px;';
        $css .= '    list-style: none;';
        $css .= '    margin: 0;';
        $css .= '    padding: 0;';
        $css .= '    left: 0;';
        $css .= '    top: 0;';
        $css .= '}';

        $css .= '#circleslider .overview li {';
        $css .= '    height: '.$this->params['imgheight'].'px;';
        $css .= '    width: '.$this->params['imgwidth'].'px';
        $css .= '    margin: 0 20px 0 0;';
        $css .= '    float: left;';
        $css .= '    position: relative;';
        $css .= '}';
        $css .= '#circleslider .overlay {';
        $css .= '    background: url('.$conf->main_base_path.'/plugins/template/circularslider/img/bg-rotatescroll2.png) no-repeat;';
        $css .= '    pointer-events: none;';
        $css .= '    position: absolute;';
        $css .= '    left: 0;';
        $css .= '    top: 0;';
        $css .= '    background-size: contain; ';
        $css .= '    height: '. ($this->params['imgheight'] + 50) .'px;';
        $css .= '    width: '. ($this->params['imgwidth'] + 50) .'px;';
        $css .= '}';
        $css .= '#circleslider .thumb {';
        $css .= '    background: url('.$conf->main_base_path.'/plugins/template/circularslider/img/bg-thumb.png) no-repeat 50% 50%;';
        $css .= '    position: absolute;';
        $css .= '    top: -3px;';
        $css .= '    cursor: pointer;';
        $css .= '    left: 137px;';
        $css .= '    width: 100px;';
        $css .= '    z-index: 200;';
        $css .= '    height: 100px;';
        $css .= '}';

        $css .= '#circleslider .dot {';
        $css .= '    cursor: pointer;';
        $css .= '    background: url('.$conf->main_base_path.'/plugins/template/circularslider/img/bg-dot3.png) no-repeat 0 0;';
        $css .= '    height: 22px;';
        $css .= '    text-align: center;';
        $css .= '    line-height: 22px;';
        $css .= '    font-size: 10px;';
        $css .= '    color: #555;';
        $css .= '    width: 22px;';
        $css .= '    position: absolute;';
        $css .= '    left: 155px;';
        $css .= '    top: 3px;';
        $css .= '    z-index: 100;';
        $css .= '}';

        $css .= '#circleslider .dot span {';
        $css .= '    cursor: pointer;';
        $css .= '}';

        return $css;
    }
    private function get_all_params()
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");
        $array = array(
                'imgheight' => array (
                        'default' => '350',
                        "help" => dcgettext('plugins',"width of image",LC_ALL),
                ),
                'imgwidth' => array (
                        'default' => '350',
                        "help" => dcgettext('plugins',"height of image",LC_ALL),
                ),
                'imgminheight' => array (
                    'default' => '350',
                    "help" => dcgettext('plugins',"Min height for image",LC_ALL),
                ),
                'imgmaxwidth' => array (
                    'default' => '350',
                    "help" => dcgettext('plugins',"Max width for image.",LC_ALL),
                ),
                'interval' => array (
                        'default' => 'false',
                        "help" => dcgettext('plugins',"Move to another block on intervals.",LC_ALL),
                        "possible_value" => "true|false"
                        ),
                'intervalTime' => array (
                        'default' => '3500',
                        "help" => dcgettext('plugins',"Interval time in milliseconds.",LC_ALL)
                        ),
                'dotsSnap' => array (
                        'default' => 'false',
                        "help" => dcgettext('plugins',"Shows dots when user starts dragging and snap to them",LC_ALL),
                        "possible_value" => "true|false",
                        ),
                'dotsHide' => array (
                        'default' => 'true',
                        "help" => dcgettext('plugins',"Fades out the dots when user stops dragging.",LC_ALL),
                        "possible_value" => "true|false",
                        ),
                'radius' => array (
                        'default' => '140',
                        "help" => dcgettext('plugins',"Used to determine the size of the circleslider.",LC_ALL),
                        ),
                'start' => array (
                        'default' => '0',
                        "help" => dcgettext('plugins',"The slide to start with.",LC_ALL),
                        ),
                'use_fancy_box' => array (
                        'default' => 'false',
                        "help" => dcgettext('plugins',"Use fancy box",LC_ALL),
                        "possible_value" => "true|false",
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
        $params_json = json_decode($params_json);
        if ($params_json)
        {
            $params = json_decode($params_json->circularslider);
            foreach ($params as $key=>$val)
            {
                if ($val)
                    $ret[$key]=$val;
            }
        }
        $this->params = $ret;
        textdomain("messages");
        return $ret;
    }


}
?>
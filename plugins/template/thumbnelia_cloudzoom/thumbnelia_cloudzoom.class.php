<?php
load_alternative_class('class/plugins.class.php');
class thumbnelia_cloudzoom extends plugins
{
    protected $name = "Thumbnelia + Cloudzoom";
    protected $version = "1.0";
    protected $path = "thumbnelia_cloudzoom";

    private $params;

    public function make_form($url,$val,$form_tab_num,$data=false,$pid=false)
    {
        bindtextdomain("plugins", 'plugins/template/thumbnelia_cloudzoom/lang');
        textdomain("plugins");

        $html = "<form action=\"".$url."\" METHOD='post'>";
        $html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "<div>".dcgettext("plugins", "Thumbnelia + CloudZoom Plugin",LC_ALL)."</div>";
        $html .= $this->params_form_builder($this->get_all_params(),$val);
        $html .= "<br/><button>".dcgettext("plugins", "OK",LC_ALL)."</button>";
        $html .= "</form>";

        textdomain("messages");

       return $html;
    }
    public function get_display_js()
    {
            return array('jquery','thumbnelia','cloudzoom','thumbnelia_cloudzoom');
    }
    public function get_display_css()
    {
            return array('thumbnelia',"cloudzoom");
    }
    public function get_display_css_code()
    {
        $css = "";
        $css .= ".thumbelina-but.horiz {";
        $css .= "    width: 20px;";
        $css .= "    height: ". $this->params['height'] ."px;";
        $css .= "    line-height: ". $this->params['height'] ." px;";
        $css .= "    top:-1px;";

        $css .= "}";
        $css .= ".thumbelina-but.vert {";
        $css .= "    left:-1px;";
        $css .= "    height: 20px;";
        $css .= "    line-height: 20px;";
        $css .= "    width:". $this->params['width'] ."px";
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
        $array = array(
            'orientation' => array (
                        'default' => "horizontal",
                        "help" => dcgettext('plugins',dcgettext('plugins',"Orientation mode, horizontal or vertical",LC_ALL),LC_ALL),
                        "possible_value" => "vertical|horizontal",
                        "formbreak" => "Thumbnelia Related"
                ),
                'easing' => array (
                        'default' => 8,
                        "help" => dcgettext('plugins',dcgettext('plugins',"Amount of easing (min 1) larger = more drift",LC_ALL),LC_ALL),
                ),
                'maxSpeed' => array (
                        'default' => 5,
                        "help" => dcgettext('plugins',"Max speed of movement (pixels per cycle).",LC_ALL),
                ),
                'bwdBut' => array (
                        'default' => "jQuery(\"#slider .rwd\")",
                        "help" => dcgettext('plugins',"jQuery element used as backward button.",LC_ALL),
                ),
                'fwdBut' => array (
                        'default' => "jQuery(\"#slider .fwd\")",
                        "help" => dcgettext('plugins',"jQuery element used as forward button.",LC_ALL),
                ),
                'imagewidth' => array (
                        'default' => "100",
                        "help" => dcgettext('plugins',"Image width (0 auto)",LC_ALL),
                ),
                'imageheight' => array (
                        'default' => "0",
                        "help" => dcgettext('plugins',"Image height (0 auto)",LC_ALL),
                ),
                'imagemaxwidth' => array (
                        'default' => "0",
                        "help" => dcgettext('plugins',"Image max width (0 auto)",LC_ALL),
                ),
                'imagemaxheight' => array (
                        'default' => "0",
                        "help" => dcgettext('plugins',"Image max height (0 auto)",LC_ALL),
                ),
                'width' => array (
                        'default' => "500",
                        "help" => dcgettext('plugins',"Slider width",LC_ALL),
                ),
                'height' => array (
                        'default' => "250",
                        "help" => dcgettext('plugins',"Slider height",LC_ALL),
                ),
                'tintColor' => array (
                        'default' => '#fff',
                        "help" => dcgettext('plugins',"Couleur de l'overlay",LC_ALL),
                        "formbreak" => "CloudZoom Related"
                ),
                'tintOpacity' => array (
                        'default' => '0.5',
                        "help" => dcgettext('plugins',"Opacité de l'overlay",LC_ALL),
                        "possible_value" => "0..1|0.1"
                ),
                'animationTime' => array (
                        'default' => '500',
                        "help" => dcgettext('plugins',"Temps d'execution de l'animation (ms)",LC_ALL),
                ),
                'galleryEvent' => array (
                        'default' => 'click',
                        "help" => dcgettext('plugins',"Event pour la gallery",LC_ALL),
                ),
                'easeTime' => array (
                        'default' => '500',
                        "help" => dcgettext('plugins',"Temps d'execution ease de l'animation (ms)",LC_ALL),
                ),
                'zoomSizeMode' => array (
                        'default' => 'lens',
                        "help" => dcgettext('plugins',"zoom mode",LC_ALL),
                        "possible_value" => "lens|full"
                ),
                'zoomMatchSize' => array (
                        'default' => '!1',
                        "help" => dcgettext('plugins',"Match Size",LC_ALL),
                        "possible_value" => "0|1"
                ),
                'zoomPosition' => array (
                        'default' => '3',
                        "help" => dcgettext('plugins',"Position du zoom",LC_ALL),
                        "possible_value" => "0:En haut au centre|1:En haut à droite|2:En haut à droite extreme en dehors du cadre|3:A droite en haut|4:A droite au centre|5:A droite en bas|6:A droite en bas en dehors du cadre|7:En bas à droite|8:En bas au centre|9:En bas à gauche|10:En bas à gauche en dehors du cadre|11:A gauche en bas|12:A gauche au milieu|13:A gauche en haut|14:A gauche en haut en dehors du cadre|15:En haut à gauche"
                ),
                'zoomOffsetX' => array (
                        'default' => '15',
                        "help" => dcgettext('plugins',"Decalage horizontal du zoom",LC_ALL),
                ),
                'zoomOffsetY' => array (
                        'default' => '0',
                        "help" => dcgettext('plugins',"Decalage vertical du zoom",LC_ALL),
                ),
                'zoomFullSize' => array (
                        'default' => '!1',
                        "help" => dcgettext('plugins',"Zoom window appears at full size of zoom image.",LC_ALL),
                ),
                'uriEscapeMethod' => array (
                        'default' => '!1',
                        "help" => dcgettext('plugins',"Use of special characters or spaces in paths to images is not recommended. However, sometimes you may not have control over this. You can specify a character escape method for the image paths. Specify the JavaScript escaping method to use, either \"escape\" or \"encodeURI\" (false = no escaping).",LC_ALL),
                ),
                'variableMagnification' => array (
                        'default' => '!0',
                        "help" => dcgettext('plugins',"Whether to allow variable magnification",LC_ALL),
                ),
                'startMagnification' => array (
                        'default' => 'auto',
                        "help" => dcgettext('plugins',"Initial magnification (multiplier of small image size, do not put quotes around numbers). \"auto\" will choose best quality magnification based on large image size.",LC_ALL),
                ),
                'minMagnification' => array (
                        'default' => 'auto',
                        "help" => dcgettext('plugins',"Minimum allowable magnification (multiplier of small image size). \"auto\" will ensure lens size does not get larger than small image.",LC_ALL),
                ),
                'maxMagnification' => array (
                        'default' => 'auto',
                        "help" => dcgettext('plugins',"Maximum allowable magnification (multiplier of small image size). \"auto\" will choose best quality magnification based on large image size.",LC_ALL),
                ),
                'easing' => array (
                        'default' => '8',
                        "help" => dcgettext('plugins',"Higher the number, the smoother/slower the easing of movement.",LC_ALL),
                ),
                'lazyLoadZoom' => array (
                        'default' => '!1',
                        "help" => dcgettext('plugins',"Lazy load the zoom image. If true, zoom image will only be loaded after initial interaction with small image, otherwise will be loaded immediately on page load. Lazy loading can be useful if there are many zoom images that need to be loaded.",LC_ALL),
                ),
                'mouseTriggerEvent' => array (
                        'default' => 'mousemove',
                        "help" => dcgettext('plugins',"Mouse event used to trigger zoom. Use either \"mousemove\" or \"click\".",LC_ALL),
                        "possible_value" => "mousemove|click"
                ),
                'disableZoom' => array (
                        'default' => 'false',
                        "help" => dcgettext('plugins',"Use to disable zoom. false = no disable, true = disable always, \"auto\" = disable only if zoom image is the same size or smaller than small image. Note, if you have set magnification levels larger than 1, zoom will not be disabled.",LC_ALL),
                        "possible_value" => '"auto":auto|true:true|false:false'
                ),
                'galleryFade' => array (
                        'default' => '!0',
                        "help" => dcgettext('plugins',"Turns gallery fade effect on or off. If you are using mouseover for changing gallery images, false is recommended.",LC_ALL),
                        "possible_value" => "true|false"
                ),
                'galleryHoverDelay' => array (
                        'default' => '200',
                        "help" => dcgettext('plugins',"Number of milliseconds to delay changing of images when using galleryEvent:'mouseover'. Stops flooding browser with image load requests. ",LC_ALL),
                ),
                'permaZoom' => array (
                        'default' => '!1',
                        "help" => dcgettext('plugins',"If true, zoom window will remain open when mouse moves out of small image. ",LC_ALL),
                        "possible_value" => "true|false"
                ),
                'zoomWidth' => array (
                        'default' => '200',
                        "help" => dcgettext('plugins',"Specifies zoom window size (lens size adjusted). Overrides any CSS size. ",LC_ALL),
                ),
                'zoomHeight' => array (
                        'default' => '400',
                        "help" => dcgettext('plugins',"Specifies zoom window size (lens size adjusted). Overrides any CSS size. ",LC_ALL),
                ),
                'lensWidth' => array (
                        'default' => '50',
                        "help" => dcgettext('plugins',"Specifies lens size (zoom window size adjusted). Overrides any CSS size. ",LC_ALL),
                ),
                'lensHeight' => array (
                        'default' => '100',
                        "help" => dcgettext('plugins',"Specifies lens size (zoom window size adjusted). Overrides any CSS size. ",LC_ALL),
                ),
                'hoverIntentDelay' => array (
                        'default' => '0',
                        "help" => dcgettext('plugins',"Specifies the amount of time (milliseconds) that slow movement must be made to trigger zoom. A good value is 100. Zero means no hover intent. ",LC_ALL),
                ),
                'hoverIntentDistance' => array (
                        'default' => '2',
                        "help" => dcgettext('plugins',"Specifies the distance moved in pixels that would be classed as slow. ",LC_ALL),
                ),
                'autoInside' => array (
                        'default' => '0',
                        "help" => dcgettext('plugins',"If the page size becomes less than or equal to the specified width (pixels) Cloud Zoom will work in 'inside' mode. Useful for responsive pages ",LC_ALL),
                ),
                'disableOnScreenWidth' => array (
                        'default' => '0',
                        "help" => dcgettext('plugins',"Use to disable zoom if page size less than or equal to specified value. Useful for disabling zoom on small mobile devices. Gallery elements will still function and change the image.",LC_ALL),
                ),
                'touchStartDelay' => array (
                        'default' => '0',
                        "help" => dcgettext('plugins'," Allow normal page touch-scrolling to work when images take up full device width. ",LC_ALL),
                        "possible_value" => "true|false"
                ),
                'appendSelector' => array (
                        'default' => 'body',
                        "help" => dcgettext('plugins',"Element to attach zoom to",LC_ALL),
                ),
                'propagateGalleryEvent' => array (
                        'default' => '!1'
                ),
        );
        return $array;
    }
    public function get_params($page)
    {
        bindtextdomain("plugins", 'plugins/template/thumbnelia_cloudzoom/lang');
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
            $params = json_decode($params_json->thumbnelia_cloudzoom);
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
                    if ( $val."x" != "x")
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
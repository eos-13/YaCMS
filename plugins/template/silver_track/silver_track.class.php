<?php
load_alternative_class('class/plugins.class.php');
class silver_track extends plugins
{
    protected $name = "Silver Track";
    protected $version = "1.0";
    protected $path = "silver_track";
    private $params;

    public function make_form($url,$val,$form_tab_num,$data=false,$pid=false)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $html = "<form action=\"".$url."\" METHOD='post'>";
        $html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "<div>".dcgettext('plugins', "Silver Track Plugin",LC_ALL)."</div>";
        $html .= $this->params_form_builder($this->get_all_params(),$val);
        $html .= "<br/><button>".dcgettext('plugins', "OK",LC_ALL)."</button>";
        $html .= "</form>";

        textdomain("messages");

       return $html;
    }
    public function get_display_js()
    {
        $a = array();
        $a[] = 'jquery';
        $a[] = 'jquery.silver_track.min';
        $a[] = 'modernizr.mediaqueries.transforms3d';
        $a[] = 'jquery-easing-1.3.0';
        if ($this->params['use_responsiveHub_connector'] == "true")
        {
            $a[] = 'responsive-hub';
            $a[] = "jquery.silver_track.responsive_hub_connector";
        }
        if ($this->params['use_circular_animation'] == "true")
            $a[] = "jquery.silver_track.circular_navigator";
        if ($this->params['use_css_3_animation'] == "true")
            $a[] = "jquery.silver_track.css3_animation";
        if ($this->params['use_navigator'] == "true")
            $a[] = "jquery.silver_track.navigator";
        if ($this->params['use_bullet_navigator'] == "true")
            $a[] = "jquery.silver_track.bullet_navigator";
        if ($this->params['use_remote_content'] == "true")
            $a[] = "jquery.silver_track.remote_content";

        return $a;
    }
    public function get_display_css()
    {
//         if ($this->params['use_fancy_box'] == "true")
//             return array('thumbnelia','jquery.fancybox');
//         else
            return array('silver_track','slider');
    }
    public function get_display_css_code()
    {

    }
    private function get_all_params()
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $array = array(
                'orientation' => array (
                        'default' => "horizontal",
                        "help" => dcgettext('plugins',"Orientation mode, horizontal or vertical",LC_ALL),
                        "possible_value" => "vertical|horizontal",
                        "formbreak" => "Slider"
                ),
                'perPage' => array(
                        "help" => dcgettext('plugins',"The amount of items to display",LC_ALL),
                        "default" => 4
                        ),
                'itemClass' => array(
                        "help" => dcgettext('plugins',"The css class name that will be used to find the item",LC_ALL),
                        "default" => "item"
                ),
                'mode' => array(
                        "help" => dcgettext('plugins',"The direction of pagination (horizontal or vertical)",LC_ALL),
                        "default" => "horizontal",
                        "possible_value" => "vertical|horizontal"
                ),
                'animationAxis' => array(
                        "help" => dcgettext('plugins',"The direction of the animation (x or y). To animate in \"y\" axis you will need the mode \"vertical\" too",LC_ALL),
                        "default" => "x",
                        "possible_value" => "x|y"
                ),
                'autoHeight' => array(
                        "help" => dcgettext('plugins',"If it will adjust the track height after each pagination",LC_ALL),
                        "default" => "false",
                        "possible_value" => "true|false"
                ),
                'cover' => array(
                        "help" => dcgettext('plugins',"When set to true, the plugin will consider the first page as a cover and will consider it as one item",LC_ALL),
                        "default" => "false",
                        "possible_value" => "true|false"
                ),
                'easing' => array(
                        "help" => dcgettext('plugins',"Animation function used by SilverTrack. This function will be used by \$.animate, so you could use any plugin that adds easing functions. Ex: jQuery Easing",LC_ALL),
                        "default" => "swing"
                ),
                'duration' => array(
                        "help" => dcgettext('plugins',"The duration used by the animation function",LC_ALL),
                        "default" => "600"
                ),
                'animateFunction' => array(
                        "help" => dcgettext('plugins',"If set this function will be used instead of \$.animate. The function will receive movement, duration, easing and afterCallback. The movement object will be {left: someNumber} or {height: someNumber}. For an example of how to replace the animation function take a look at css3 animation plugin",LC_ALL),
                        "default" => "null"
                ),
                'use_navigator' => array (
                        'default' => "false",
                        "help" => dcgettext('plugins',"Use silver_track navigator",LC_ALL),
                        "possible_value" => "true|false",
                        "formbreak" => "Navigator"
                ),
                'disabledClass' => array(
                        'default' => "disabled",
                        "help" => dcgettext('plugins',"Class applied to disable the navigation element",LC_ALL),
                ),
                'prev' => array(
                        'default' => 'jQuery(".my-track a.prev")',
                        "help" => dcgettext('plugins',"Element that will receive the \"previous\" function",LC_ALL),
                ),
                'next' => array(
                        'default' => 'jQuery(".my-track a.next")',
                        "help" => dcgettext('plugins',"Element that will receive the \"next\" function",LC_ALL),
                ),
                'use_bullet_navigator' => array (
                        'default' => "false",
                        "help" => dcgettext('plugins',"Use silver_track bullet navigator",LC_ALL),
                        "possible_value" => "true|false",
                        "formbreak" => "Bullet Navigator",
                ),
                'bulletClass' => array (
                        'default' => "bullet",
                        "help" => dcgettext('plugins',"Class applied to each \"bullet\"",LC_ALL),
                ),
                'activeClass' => array(
                        'default' => "active",
                        "help" => dcgettext('plugins',"Class applied to the active \"bullet\"",LC_ALL),
                ),
                'container' => array(
                        'default' => "",
                        "help" => dcgettext('plugins',"Container to append the navigation links",LC_ALL),
                ),
                'use_remote_content' => array (
                        'default' => "false",
                        "help" => dcgettext('plugins',"Use silver_track remote content",LC_ALL),
                        "possible_value" => "true|false",
                        "formbreak" => "Remote Content",
                ),
                'lazy' => array(
                        "help" => dcgettext('plugins','It determines if RemoteContent will fetch the first page after the track starts or just when the user navigate to a page without cache. You will usually set this to false when you render your first page with the track and get the other items through ajax',LC_ALL),
                        'default' => 'true',
                        "possible_value" => "true|false",
                ),
                'prefetchPages' => array(
                        "help" => dcgettext('plugins','The amount of pages that will be prefetched ahead of time. It is based on current page so if you have configured \"prefetchPages: 2\" it will start loading 3 pages (the first + 2) and will keep the distance of 2 pages until the end, so when the current page is 2 there will be 4 loaded pages and so on',LC_ALL),
                        'default' => 0,
                ),
                'type' => array(
                        "help" => dcgettext('plugins','The type of request to make ("POST" or "GET"). This parameter is sent direct to ajaxFunction',LC_ALL),
                        'default' => 'GET'
                ),
                'params' => array(
                        "help" => dcgettext('plugins','Data to be sent to the server. This parameter is sent to option "data" of ajaxFunction',LC_ALL),
                        'default' => '{}',
                ),
                'url' => array(
                        "help" => dcgettext('plugins','A string containing the URL to which the request is sent or a function that returns an url. If this option is a function, it will receive a reference of track, the page number and the perPage value, if not RemoteContent will try to replace the substrings {page} and {perPage} with the current values',LC_ALL),
                        'default' => "",
                ),
                'ajaxFunction' => array(
                        "help" => dcgettext('plugins','A custom function for ajax. It defaults to $.ajax, if defined the provided function will receive the same options of $.ajax. It allows other ajax implementations such as ajaxQ or something based on promises',LC_ALL),
                        'default' => '$.ajax',
                ),
                'use_responsiveHub_connector' => array (
                        'default' => "false",
                        "help" => dcgettext('plugins',"Use silver_track responsive hub",LC_ALL),
                        "possible_value" => "true|false",
                        "formbreak" => "Responsive Hub",
                ),
                'layouts' => array(
                        "help" => dcgettext('plugins',"The resolution names configured in responsiveHub.",LC_ALL),
                        "default" => ""
                ),
                'onReady' => array(
                        "help" => dcgettext('plugins',"On ready event",LC_ALL),
                        "default" => ""
                ),
                'onChange' => array(
                        "help" => dcgettext('plugins',"on change event",LC_ALL),
                        "default" => ""
                ),
                'use_css_3_animation' => array (
                        'default' => "false",
                        "help" => dcgettext('plugins',"Use silver_track css 3 animation",LC_ALL),
                        "possible_value" => "true|false",
                        "formbreak" => "CSS 3.0 animation",
                ),
                'durationUnit' => array(
                        "help" => dcgettext('plugins','The unit used for the animations.',LC_ALL),
                        'default' => "ms",
                        'possible_value' => "ms|s"
                ),
                'delayUnit' => array(
                        "help" => dcgettext('plugins','The unit used for the delay property.',LC_ALL),
                        'default' => "null",
                        'possible_value' => 'null|"ms"|"s"',
                ),
                'setupParent' => array(
                        "help" => dcgettext('plugins','Configure the css3 properties to allow hardware acceleration in the parent of the container.',LC_ALL),
                        'default' => "true",
                        'possible_value' => "true|false"
                ),
                'setupTransitionProperty' => array(
                        "help" => dcgettext('plugins','Configure the css3 transition-property to the container.',LC_ALL),
                        'default' => "true",
                        'possible_value' => "true|false"
                ),
                'setupTransitionDuration' => array(
                        "help" => dcgettext('plugins','Configure the css3 transition-duration to the container. ',LC_ALL),
                        'default' => "true",
                        'possible_value' => "true|false"
                ),
                'setupTransitionTimingFunction' => array(
                        "help" => dcgettext('plugins','Configure the css3 transition-timing-function to the container, it already converts the easing names (easeInOutQuart, easeInCubic, etc) to proper cubic-bezier functions. ',LC_ALL),
                        'default' => "true",
                        'possible_value' => "true|false",
                ),
                'setupTransitionDelay' => array(
                        "help" => dcgettext('plugins','Configure the css3 transition-delay to the container. ',LC_ALL),
                        'default' => "true",
                        'possible_value' => "true|false",
                ),
                'slideDelay' => array(
                        "help" => dcgettext('plugins','The delay used between the transitions, note that delay it is not the same as the duration, this is the time that the browser will wait before the animation starts. ',LC_ALL),
                        'default' => "0",
                ),
                'autoHeightDuration' => array(
                        "help" => dcgettext('plugins','This option configures the duration of the height adjustment animation if the property autoHeight of track is set to true. It might also be done directly through the css file but be aware that this option is correctly configured with the duration of the slide animation, make sure that both will work. It automatically fallback to the option duration of the track',LC_ALL),
                        'default' => "null",
                ),
                'autoHeightEasing' => array(
                        "help" => dcgettext('plugins','This option configures the easing function of the height adjustment animation if the property autoHeight of the track is set to true. It might also be done directly through the css file but be aware that this option convert the easing name (easeInOutQuart, easeInCubic, etc) to the proper cubic-bezier function and is configured with the easing function of the slide animation, make sure that both will work. It automatically fallback to the option easing of the track',LC_ALL),
                        'default' => "null",
                ),
                'autoHeightDelay' => array(
                        "help" => dcgettext('plugins','This option configures the delay of the height adjustment animation if the property autoHeight of the track is set to true. It might also be done directly through the css file but be aware that this option is correctly configured with the delay of the slide animation, make sure that both will work. It automatically fallback to the option slideDelay',LC_ALL),
                        'default' => "null"
                ),
                'use_circular_animation' => array (
                        'default' => "false",
                        "help" => dcgettext('plugins',"Use silver_track css circular animation",LC_ALL),
                        "possible_value" => "true|false",
                        "formbreak" => "Circular animation",
                ),
                'autoPlay' => array(
                        'default' => false,
                        "help" => dcgettext('plugins','Allows your track to foward one page by "duration option" time. It automatically disabled when mouse is over the track.',LC_ALL),
                        "possible_value" => "true|false",
                ),
                'duration' =>array(
                        'default' => 3000,
                        "help" => dcgettext('plugins','time to auto play in milliseconds',LC_ALL),
                ),
                'clonedClass'=> array(
                        'default' => "cloned",
                        "help" => dcgettext('plugins','Class applied to the cloned items that makes track circular',LC_ALL)
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
            $params = json_decode($params_json->silver_track);
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
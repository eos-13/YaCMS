<?php
load_alternative_class('class/plugins.class.php');
class wowslider extends plugins
{
    protected $name = "Wow Slider";
    protected $version = "1.0";
    protected $path = "wowslider";
    private $params;

    public function make_form($url,$val,$form_tab_num,$data=false,$pid=false)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $html = "<form action=\"".$url."\" METHOD='post'>";
        $html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "<div>".dcgettext('plugins',"WoW Slider Plugin",LC_ALL)."</div>";
        $html .= $this->params_form_builder($this->get_all_params(),$val);
        $html .= "<br/><button>".dcgettext('plugins',"OK",LC_ALL)."</button>";
        $html .= "</form>";

        textdomain("messages");
        return $html;
    }
    public function get_display_js()
    {
        return array('jquery');
    }
    public function get_display_css()
    {
        return array('style');
    }
    private function get_all_params()
    {
        $html = <<<EOF
<div id="wowslider-container1">
<div class="ws_images"><ul>

<li><img src="plugins/template/wowslider/img/data1/images/1.jpg" alt="1" title="1" id="wows1_0"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/10.jpg" alt="10" title="10" id="wows1_1"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/11.jpg" alt="11" title="11" id="wows1_2"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/12.jpg" alt="12" title="12" id="wows1_3"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/13.jpg" alt="13" title="13" id="wows1_4"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/14.jpg" alt="14" title="14" id="wows1_5"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/15.jpg" alt="15" title="15" id="wows1_6"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/16.jpg" alt="16" title="16" id="wows1_7"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/17.jpg" alt="17" title="17" id="wows1_8"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/18.jpg" alt="18" title="18" id="wows1_9"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/19.jpg" alt="19" title="19" id="wows1_10"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/2.jpg" alt="2" title="2" id="wows1_11"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/20.jpg" alt="20" title="20" id="wows1_12"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/21.jpg" alt="21" title="21" id="wows1_13"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/22.jpg" alt="22" title="22" id="wows1_14"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/23.jpg" alt="23" title="23" id="wows1_15"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/24.jpg" alt="24" title="24" id="wows1_16"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/3.jpg" alt="3" title="3" id="wows1_17"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/4.jpg" alt="4" title="4" id="wows1_18"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/5.jpg" alt="5" title="5" id="wows1_19"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/6.jpg" alt="6" title="6" id="wows1_20"/></li>
<li><img src="plugins/template/wowslider/img/data1/images/7.jpg" alt="7" title="7" id="wows1_21"/></li>
<li><a href="http://wowslider.com"><img src="plugins/template/wowslider/img/data1/images/8.jpg" alt="full width slider" title="8" id="wows1_22"/></a></li>
<li><img src="plugins/template/wowslider/img/data1/images/9.jpg" alt="9" title="9" id="wows1_23"/></li>
</ul></div>
<div class="ws_bullets"><div>
<a href="#" title="1"><span><img src="plugins/template/wowslider/img/data1/tooltips/1.jpg" alt="1"/>1</span></a>
<a href="#" title="10"><span><img src="plugins/template/wowslider/img/data1/tooltips/10.jpg" alt="10"/>2</span></a>
<a href="#" title="11"><span><img src="plugins/template/wowslider/img/data1/tooltips/11.jpg" alt="11"/>3</span></a>
<a href="#" title="12"><span><img src="plugins/template/wowslider/img/data1/tooltips/12.jpg" alt="12"/>4</span></a>
<a href="#" title="13"><span><img src="plugins/template/wowslider/img/data1/tooltips/13.jpg" alt="13"/>5</span></a>
<a href="#" title="14"><span><img src="plugins/template/wowslider/img/data1/tooltips/14.jpg" alt="14"/>6</span></a>
<a href="#" title="15"><span><img src="plugins/template/wowslider/img/data1/tooltips/15.jpg" alt="15"/>7</span></a>
<a href="#" title="16"><span><img src="plugins/template/wowslider/img/data1/tooltips/16.jpg" alt="16"/>8</span></a>
<a href="#" title="17"><span><img src="plugins/template/wowslider/img/data1/tooltips/17.jpg" alt="17"/>9</span></a>
<a href="#" title="18"><span><img src="plugins/template/wowslider/img/data1/tooltips/18.jpg" alt="18"/>10</span></a>
<a href="#" title="19"><span><img src="plugins/template/wowslider/img/data1/tooltips/19.jpg" alt="19"/>11</span></a>
<a href="#" title="2"><span><img src="plugins/template/wowslider/img/data1/tooltips/2.jpg" alt="2"/>12</span></a>
<a href="#" title="20"><span><img src="plugins/template/wowslider/img/data1/tooltips/20.jpg" alt="20"/>13</span></a>
<a href="#" title="21"><span><img src="plugins/template/wowslider/img/data1/tooltips/21.jpg" alt="21"/>14</span></a>
<a href="#" title="22"><span><img src="plugins/template/wowslider/img/data1/tooltips/22.jpg" alt="22"/>15</span></a>
<a href="#" title="23"><span><img src="plugins/template/wowslider/img/data1/tooltips/23.jpg" alt="23"/>16</span></a>
<a href="#" title="24"><span><img src="plugins/template/wowslider/img/data1/tooltips/24.jpg" alt="24"/>17</span></a>
<a href="#" title="3"><span><img src="plugins/template/wowslider/img/data1/tooltips/3.jpg" alt="3"/>18</span></a>
<a href="#" title="4"><span><img src="plugins/template/wowslider/img/data1/tooltips/4.jpg" alt="4"/>19</span></a>
<a href="#" title="5"><span><img src="plugins/template/wowslider/img/data1/tooltips/5.jpg" alt="5"/>20</span></a>
<a href="#" title="6"><span><img src="plugins/template/wowslider/img/data1/tooltips/6.jpg" alt="6"/>21</span></a>
<a href="#" title="7"><span><img src="plugins/template/wowslider/img/data1/tooltips/7.jpg" alt="7"/>22</span></a>
<a href="#" title="8"><span><img src="plugins/template/wowslider/img/data1/tooltips/8.jpg" alt="8"/>23</span></a>
<a href="#" title="9"><span><img src="plugins/template/wowslider/img/data1/tooltips/9.jpg" alt="9"/>24</span></a>
</div></div><div class="ws_script" style="position:absolute;left:-99%"></div>
<div class="ws_shadow"></div>
<script type="text/javascript" src="plugins/template/wowslider/js/engine1/wowslider.js"></script>
<script type="text/javascript" src="plugins/template/wowslider/js/engine1/script.js"></script>
</div>
EOF;

        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $array = array(
            'id' => array (
                    'default' =>$html,
                    "help" => dcgettext("plugins",'WowSliders ID',LC_ALL),
            ),
            'HTML' => array (
                    'default' =>$html,
                    "help" => dcgettext("plugins",'WowSliders HTML',LC_ALL),
                    "type" => "textarea"
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
        $params = json_decode($params_json->wowslider);

        foreach ($params as $key=>$val)
        {
            if ($val)
                $ret[$key]=$val;
        }
        textdomain("messages");
        return $ret;
    }
    public function params_form_builder($params,$value=false)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");

        $html = "<br/><table style='border:0px transparent'>";
        $atleast_one_checkbox = false;
        foreach($params as $key => $val)
        {
            $type = $default = $possible_value = $help = false;
            if (isset($val['default'])) $default=$val['default'];
            if (isset($val['possible_value'])) $possible_value=$val['possible_value'];
            if (isset($val['type'])) $type=$val['type'];
            if (isset($val['help']))  { $help=$val['help']; } else { $help = $key; }
            $input = "";
            if ($type && $type == "textarea")
            {
                $input = "<textarea style='width: 60em; min-height: auto; height: 10em; max-height:10em;' name='".$key."'>". (isset($value->$key)?$value->$key:$default) ."</textarea>";
            } else {
                $input = "<input type='text' placeholder='". $default ."' value='". (isset($value->$key)?$value->$key:"") ."' name='".$key."'/>";
            }
            $html .= "<tr><td  style='border:0px transparent'>".$help."</td>";
            $html .= "<td style='border:0px transparent'>".$input."</td>";
            $html .= "\n";
        }
        $html .= "</table>";
        textdomain("messages");
        return $html;
    }
}
?>
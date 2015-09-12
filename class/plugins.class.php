<?php
class plugins
{
    protected $db;
    private $css;
    private $js;
    private $css_code;
    private $js_code;
    private $js_external;
    protected $name = "No Name";
    protected $version = "No version";
    protected $path = "nopath";

    public function __construct($db)
    {
        $this->db = $db;
        global $conf;
    }
    public function get_display_js()
    {
        return false;
    }
    public function get_display_css()
    {
        return false;
    }
    public function get_display_css_code()
    {
        return false;
    }
    public function get_display_js_code()
    {
        return false;
    }
    public function get_display_js_external()
    {
        return false;
    }
    public function get_css()
    {
        return $this->css;
    }
    public function get_css_code()
    {
        return $this->css_code;
    }
    public function get_js()
    {
        return $this->js;
    }
    public function get_js_code()
    {
        return $this->js_code;
    }
    public function get_js_external()
    {
        return $this->js_external;
    }
    public function set_css($css)
    {
        $this->css = array_merget($this->css,$css);
    }
    public function set_css_code($css_code)
    {
        $this->css_code .= $css_code;
    }
    public function set_js($js)
    {
        $this->js[] = array_merge($this->js,$js);
    }
    public function set_js_code($js_code)
    {
        $this->js_code .= $js_code;
    }
    public function set_js_external($js_external)
    {
        if (is_array($js_external))
            $this->js_external = array_merge($this->js_external,$js_external);
        else {
            $this->js_external[] = $js_external;
        }
    }
    public function make_form($url,$form_tab_num)
    {
        $html = "Plugin ".$this->name;
        $html .= " Version: ".$this->version;
        return $html;
    }
    public function get_full_name()
    {
        return $this->name. " ".$this->version;
    }
    public function params_form_builder($params,$value=false)
    {
        $html = "";
        $atleast_one_checkbox = false;
        foreach($params as $key => $val)
        {
            if (isset($val['formbreak']) && $val['formbreak']."x" != "x")
            {
                $html .= "<br/><hr style='clear:both;'/>";
                $html .= "<div style='vertical-align: middle; padding: 10px; margin: 3px;clear: both;'>";
                $html .= "<b>".$val['formbreak']."</b>";
                $html .= "</div>";
                $html .= "<hr  style='clear:both;'/>";
            }
            $type = $default = $possible_value = $help = false;
            if (isset($val['default'])) $default=$val['default'];
            if (isset($val['possible_value'])) $possible_value=$val['possible_value'];
            if (isset($val['type'])) $type=$val['type'];
            if (isset($val['help']))  { $help=$val['help']; } else { $help = $key; }
            $html .= "<div style='vertical-align: middle; padding: 10px; margin: 3px;clear: both;'>";
            $input = "";
            if ($possible_value)
            {
                if (preg_match('/^([0-9\.]*)\.\.([0-9\.]*)/',$possible_value,$arrMatch))
                {
                    //On fait un slider
                    $min = $arrMatch[1];
                    $max = $arrMatch[2];
                    $step=1;
                    if (preg_match('/\|([0-9\.]*)$/',$possible_value,$arrMatch1));
                        $step=$arrMatch1[1];
                    $input = "<input name='".$key."' id='range_".$key."' value='".(isset($value->$key)?$value->$key:"")."' max='".$max."' min='".$min."' step='".$step."' type='range' />";
                    $input .= '<output style="display: inline; float: right; margin-top:3px" name="'.$key.'" onforminput="value = '.$key.'.valueAsNumber;" for="range_'.$key.'">'.(isset($value->$key)?$value->$key:"").'</output>';
                    if (!$atleast_one_checkbox)
                    {
                        $atleast_one_checkbox = true;

                        $this->set_js_code('
                            jQuery(function() {
                             var el, newPoint, newPlace, offset;
                             jQuery("input[type=\"range\"]").change(function()
                             {
                               el = jQuery(this);
                               el.next("output")
                                 .text(el.val());
                             })
                             .trigger("change");
                            });');
                    }

                } else {
                    $possible = preg_split('/\|/', $possible_value);
                    $selected = false;
                    if (isset($value->$key)) $selected = $value->$key;
                    else  if (isset($default)) $selected = $default;
                    $input = "<SELECT name='".$key."'>";
                    foreach($possible as $key1 => $val1)
                    {
                        if (preg_match('/:/',$val1))
                        {
                            $tmp = preg_split('/:/',$val1);
                            if ($selected == $tmp[0])
                                $input .= "<option SELECTED value='".$tmp[0]."'>".$tmp[1]."</option>";
                            else
                                $input .= "<option value='".$tmp[0]."'>".$tmp[1]."</option>";
                        } else {
                            if ($val1 == $selected)
                                $input .= "<option SELECTED value='".$val1."'>".$val1."</option>";
                            else
                                $input .= "<option value='".$val1."'>".$val1."</option>";
                        }
                    }
                    $input .= "</SELECT>";
                }
            } else if ($type && $type == "textarea")
            {
                $input = "<textarea name='".$key."'>". (isset($value->$key)?$value->$key:$default) ."'</textarea>";
            } else {
                $input = "<input type='text' placeholder='". $default ."' value='". (isset($value->$key)?$value->$key:"") ."' name='".$key."'/>";
            }
            $html .= "<div style='width:200px; max-width:200px; min-width:200px; display:inline-table; float:left;'>".$help."</div>";
            $html .= "<div style='display:inline-table; float: left;'>".$input."</div>";
            $html .= "</div>";
            $html .= "\n";
        }
        return $html;
    }
}
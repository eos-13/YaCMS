<?php
class headerfooter extends common_object
{
    protected $db;
    private $pId;
    private $pContent;
    private $pJs;
    private $pJs_code;
    private $pCss;
    private $pCss_code;
    private $pDate_derniere_maj;
    private $pName;
    private $pLang;

    public $id;
    public $content;
    public $js;
    public $js_code;
    public $css;
    public $css_code;
    public $date_derniere_maj;
    public $name;
    public $lang;

    public $log;

    protected $table = "page_parts";

    public function __construct($db)
    {
        $this->db = $db;
        $this->set_id($this->get_id());
        global $log;
        $this->log = $log;
    }
    public function fetch()
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$this->id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->pContent = $res->content;
            $this->pName = $res->name;
            $this->pJs = json_decode($res->js);
            $this->pJs_code = $res->js_code;
            $this->pCss = json_decode($res->css);
            $this->pCss_code = $res->css_code;
            $this->pDate_derniere_maj = $res->date_derniere_maj;
            $this->pLang = $res->lang;
            return $res->id;
        } else {
            return false;
        }
    }
    public function get_all()
    {
        if ($this->pId > 0)
        {
            $this->content = $this->pContent;
            $this->js = $this->pJs;
            $this->name = $this->pName;
            $this->js_code = $this->pJs_code;
            $this->css = $this->pCss;
            $this->css_code = $this->pCss_code;
            $this->date_derniere_maj = $this->pDate_derniere_maj;
            $this->lang = $this->pLang;
            return $this;
        } else {
            return false;
        }
    }
    public function set_id($id)
    {
        $this->pId = $id;
        $this->id = $id;
    }
    public function get_id()
    {
        if ($this->pId > 0)
            return $this->pId;
        else
            return false;
    }
    public function get_lang()
    {
        if ($this->get_id() > 0)
            return $this->pLang;
        else
            return false;
    }
    public function get_date_derniere_maj()
    {
        if ($this->get_id() > 0)
            return $this->pDate_derniere_maj;
        else
            return false;
    }

    public function get_name()
    {
        if ($this->get_id() > 0)
            return $this->pName;
        else
            return false;
    }
    public function get_content()
    {
        if ($this->get_id() > 0 && !empty($this->pContent))
            return $this->pContent;
        else
            return false;
    }
    public function get_js()
    {
        if ($this->get_id() > 0)
            return $this->pJs;
        else
            return false;
    }
    public function get_js_code()
    {
        if ($this->get_id() > 0)
            return $this->pJs_code;
        else
            return false;
    }
    public function get_css()
    {
        if ($this->get_id() > 0)
            return $this->pCss;
        else
            return false;
    }
    public function get_css_code()
    {
        if ($this->get_id() > 0)
            return $this->pCss_code;
        else
            return false;
    }
    public function set_content($content)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('content',$content);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_js($js)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('js',$js);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_lang($lang)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('lang',$lang);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_js_code($js_code)
    {
        if ($this->get_id() > 0){
            $res =  $this->update_field('js_code',$js_code);
            return $res;
        } else {
            return false;
        }
    }
    public function set_css($css)
    {
        if ($this->get_id() > 0){
            $res =  $this->update_field('css',$css);
            return $res;
        } else {
            return false;
        }
    }
    public function set_css_code($css_code)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('css_code',$css_code);
            return $ret;
        } else {
            return false;
        }
    }
    public function list_properties()
    {
        return array(
                "js"                  => array("name" => "JS library",                "type" => "select",   "field" => "js", "source_dir" => "js", "option" => "multiple" , "optgroup" => 1, "postprocess" => "process_js"),
                "js_code"             => array("name" => "JS code",                   "type" => "textarea", "field" => "js_code"),
                "css"                 => array("name" => "CSS library",               "type" => "select",   "field" => "css", "source_dir"=>"css", "option" => "multiple" , "optgroup" => 1, "postprocess" => "process_css"),
                "css_code"            => array("name" => "CSS code",                  "type" => "textarea", "field" => "css_code"),
        );
    }
    public function get_property($field)
    {
        $fct = "get_".$field;
        return $this->$fct();
    }
    private function process_css($value)
    {
        $value = json_encode($value);
        return $value;
    }
    private function process_js($value)
    {
        $value = json_encode($value);
        return $value;
    }
    public function parse_POST_and_update($post)
    {
        $this->fetch($this->get_id());
        $model = $this->list_properties();
        $error = false;
        foreach($model as $key=>$val)
        {
            if ($val['type'] == 'checkbox' && !isset($post[$val['field']]))
            {
                $func = "set_".$val['field'];
                $ret = $this->$func(0);
                if (! $ret && !$error) $error = true;
            }
            foreach($post as $field => $value)
            {
                if ($val['field'] == $field)
                {
                    $func = "set_".$field;
                    switch($val['type'])
                    {
                        case 'text':
                        case 'textarea':
                        case 'select':
                        {
                            if (isset($val['postprocess']))
                            {
                                $pp = $val['postprocess'];
                                $value = $this->$pp($value);

                            }
                            $ret = $this->$func($value);
                            if (! $ret && !$error) $error = true;
                        }
                        break;
                        case 'slider':
                            if ($value < $val['min']) { $value = $min; }
                            if ($value > $val['max']) { $value = $max; }
                            if (isset($val['postprocess']))
                            {
                                $pp = $val['postprocess'];
                                $value = $this->$pp($value);

                            }
                            $ret = $this->$func($value);
                            if (! $ret && !$error) $error = true;
                        break;
                        case 'checkbox':
                        {
                            if (isset($value)){
                                $ret = $this->$func(1);
                                if (! $ret && !$error) $error = true;
                            } else {
                                $ret = $this->$func(0);
                                if (! $ret && !$error) $error = true;
                            }
                        }
                        break;
                    }
                    break;
                }
            }
        }
        if (!$error) return true;
        else return false;
    }
}
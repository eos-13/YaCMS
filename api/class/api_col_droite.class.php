<?php
load_alternative_class('class/common_soap_object.class.php');
class api_col_droite extends common_soap_object
{
    public $log;

    protected $table = "page_parts";


    /**
     * @return array $datas
     * @desc List all entry id
     */
    public function list_all()
    {
        global $conf;
        $requete = "SELECT id,
                           lang
                      FROM " . $this->table."
                    WHERE name = 'col_droite'";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]['id']=$res->id;
            if (empty($res->lang))
                $a[$res->id]['lang'] = $conf->default_lang;
            else
                $a[$res->id]['lang']=$res->lang;
        }
        return $a;
    }

    /**
     * @desc Get all datas
     * @return array $datas[]
     * @param int $id
     * @param string $lang
     */
    public function get_all($id,$lang=false)
    {
        $this->load($id,$lang);
        if ($this->obj->get_id() > 0 &&  $this->obj->get_name() == "col_droite")
        {
            $a = array();
            $a['id'] = $this->obj->get_id();
            $a['content'] = $this->obj->get_content();
            $a['js'] = $this->obj->get_js();
            $a['name'] = $this->obj->get_name();
            $a['lang'] = $this->obj->get_lang();
            $a['js_code'] = $this->obj->get_js_code();
            $a['css'] = $this->obj->get_css();
            $a['css_code'] = $this->obj->get_css_code();
            $a['date_derniere_maj'] = $this->obj->get_date_derniere_maj();
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @desc Set the id
     * @return bool $res
     * @param int $id
     */
    public function set_id($id)
    {
        return $this->obj->set_id($id);
    }
    /**
     * @desc Get the id
     * @return int $id
     * @param int $id
     * @param string $lang
     */
    public function get_id($id,$lang=false)
    {
        $this->load($id,$lang);
        return $this->obj->get_id();
    }
    /**
     * @desc Get last update date
     * @return datetime $date_derniere_maj
     * @param int $id
     * @param string $lang
     */
    public function get_date_derniere_maj($id,$lang=false)
    {
        $this->load($id,$lang);
        return $this->obj->get_date_derniere_maj();
    }
    /**
     * @desc Get the name
     * @return string $name
     * @param int $id
     * @param string $lang
     */
    public function get_name($id,$lang=false)
    {
        $this->load($id,$lang);
        return $this->obj->get_name();
    }
    /**
     * @desc Get the content
     * @return string $content
     * @param int $id
     * @param string $lang
     */
    public function get_content($id,$lang=false)
    {
        $this->load($id,$lang);
        return $this->obj->get_content();
    }
    /**
     * @desc Get js library list (json_encoded)
     * @return string $js
     * @param int $id
     * @param string $lang
     */
    public function get_js($id,$lang=false)
    {
        $this->load($id,$lang);
        return $this->obj->get_js();
    }
    /**
     * @desc Get the js code
     * @return string $js_code
     * @param int $id
     * @param string $lang
     */
    public function get_js_code($id,$lang=false)
    {
        $this->load($id,$lang);
        return $this->obj->get_js_code();
    }
    /**
     * @desc Get the css file list (json encoded)
     * @return string $css
     * @param int $id
     * @param string $lang
     */
    public function get_css($id,$lang=false)
    {
        $this->load($id,$lang);
        return $this->obj->get_css();
    }
    /**
     * @desc Get the css code
     * @return string $css_code
     * @param int $id
     * @param string $lang
     */
    public function get_css_code($id,$lang=false)
    {
        $this->load($id,$lang);
        return $this->obj->get_css_code();
    }
    /**
     * @desc Set the content
     * @return bool $res
     * @param int $id
     * @param string $lang
     * @param string $content
     */
    public function set_content($id,$lang=false,$content)
    {
        $this->load($id,$lang);
        return $this->obj->set_content($content);
    }
    /**
     * @desc Set the js file list (json encoded array)
     * @return bool $res
     * @param int $id
     * @param string $lang
     * @param string $js
     */
    public function set_js($id,$lang=false,$js)
    {
        $this->load($id,$lang);
        return $this->obj->set_js($js);
    }
    /**
     * @desc Set the js code
     * @return bool $res
     * @param int $id
     * @param string $lang
     * @param string $js_code
     */
    public function set_js_code($id,$lang=false,$js_code)
    {
        $this->load($id,$lang);
        return $this->obj->set_js_code($js_code);
    }
    /**
     * @desc Set the list of css file (json_encoded)
     * @return bool $res
     * @param int $id
     * @param string $lang
     * @param string $css
     */
    public function set_css($id,$lang=false,$css)
    {
        $this->load($id,$lang);
        return $this->obj->set_css($css);
    }
    /**
     * @desc Set the css code
     * @return bool $res
     * @param int $id
     * @param string $lang
     * @param string $css_code
     */
    public function set_css_code($id,$lang=false,$css_code)
    {
        $this->load($id,$lang);
        return $this->obj->set_css_code($css_code);
    }
    /**
     * @desc Create localized part/ Get the localized id of page part
     * @return int $id
     * @param string $lang
     */
    public function change_lang($lang)
    {
        return $this->obj->change_lang($lang);
    }
    /**
     * @desc Get the localized id of page part
     * @return int $id
     * @param string $lang
     */
    public function display_lang($lang)
    {
        return $this->obj->display_lang($lang);
    }
}
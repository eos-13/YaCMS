<?php
class model_col_droite extends admin_common
{
    public $message = false;

    public function run()
    {
        load_alternative_class('class/col_droite.class.php');
        $f = new col_droite($this->db);
        global $session,$conf;
        if ($session->get("editlang") && $session->get("editlang") != $conf->default_lang )
        {
            $f->change_lang($session->get("editlang"));
        }
        $res = $f->fetch();
        if (!$res) return false;
        $array = array();
        $array['content']=$f->get_content();
        $array['property']=$this->get_all_properties();
        return $array;
    }
    private function get_all_properties()
    {
        load_alternative_class('class/col_droite.class.php');
        $f = new col_droite($this->db);
        global $session,$conf;
        if ($session->get("editlang") && $session->get("editlang") != $conf->default_lang )
        {
            $f->change_lang($session->get("editlang"));
        }
        $f->fetch();
        $arr = $f->list_properties();
        foreach($arr as $key=>$val)
        {
            $value = $f->get_property($val['field']);
            $arr[$key]['value']=$value;
            $ret=$this->make_formpart($val,$value);
            if (is_array($ret))
            {
                $arr[$key]['formpart']=array('html' => $ret['html'],
                                             'js' => $ret['js'],
                                             'js_code' => $ret['js_code']);
            } else {
                $arr[$key]['formpart']=$ret;
            }
        }
        return $arr;
    }


    public function edit($post)
    {
        load_alternative_class('class/col_droite.class.php');
        $f = new col_droite($this->db);
        global $session,$conf;
        if ($session->get("editlang") && $session->get("editlang") != $conf->default_lang )
        {
            $f->change_lang($session->get("editlang"));
        }
        $res = $f->set_content($post['content']);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function edit_properties($post)
    {
        load_alternative_class("class/col_droite.class.php");
        $f = new col_droite($this->db);
        global $session,$conf;
        if ($session->get("editlang") && $session->get("editlang") != $conf->default_lang )
        {
            $f->change_lang($session->get("editlang"));
        }
        $res = $f->parse_POST_and_update($post);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
}
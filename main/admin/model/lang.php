<?php
class model_lang extends admin_common
{
    public $message = false;
    public $langs;

    public function run()
    {
        $this->langs = $this->get_langs();
        return true;
    }
    private function get_langs()
    {
        global $conf,$lang,$editlang;
        if (!$lang) $lang=$conf->default_lang;
        if (!$editlang) $editlang=$conf->default_lang;
        $a = array();
        foreach(preg_split('/,/',$conf->available_lang) as $key=>$val)
        {
            $short_name = strtolower(substr($val,3,2));
            $flag_png = false;
            if (is_file($conf->main_document_root."/img/flags/".$val.".png"))
            {
                $flag_png = $conf->main_base_path."/img/flags/".$val.".png";
            } elseif (is_file($conf->main_document_root."/img/flags/".$short_name.".png"))
            {
                $flag_png = $conf->main_base_path."/img/flags/".$short_name.".png";
            }
            $a[]=array(
                'name' => $val,
                'flag' => $flag_png,
                'short_name' => $short_name,
                'current_lang' => ($lang == $val?true:false),
                'current_editlang' => ($editlang == $val?true:false),
            );
        }
        return $a;
    }
}
<?php
class view_col_droite extends admin_common
{
    public $message = false;

    private function js_for_page($load=0)
    {
        $js = 'jQuery(document).ready(function(){
                    jQuery("#tabs").tabs( "option", "active", '.$load.' );
                })
        ';
        return $js;
    }
    private function set_tinymce_vars($p)
    {
        $tmp = 'var all_tinymce=" ';
        $tmpA = array('tinymce');
        $tmp .= join(',',$tmpA);
        $tmp .='";';

        global $conf;
        $tmp .= 'var base_root_path="'.$conf->main_base_path.'"';
        $this->set_js_code($tmp);
        $array=false;
        $array=json_decode($conf->site_css);
        if (is_array($p) && is_array($array))
            $array = array_merge($array,$p);
        if (is_array($p) && !is_array($array))
            $array = $p;
        if (!is_array($p) && !is_array($array))
            $array=array();
        $array = array_unique($array);
        foreach($array as $key=>$val)
        {
            $array[$key] = make_path('css', $val, 'css',false,true);
        }
        //$array[]=
        $this->set_js_code ( 'var content_css_set="' . join ( ',', $array ) . '";' );
    }
    public function run($content,$property,$load=0)
    {
        $this->set_title(_("Colonne de droite"));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min','jquery-ui-multiselect'));
        $this->set_js(array('tinymce_var',"jquery.growl",'admin', 'jquery','jquery-ui.min','tinymce/tinymce.min','tinymce/jquery.tinymce.min','jquery-ui-multiselect','headerfooter',$this->req));
        $this->set_js_code($this->js_for_page($load));
        $this->set_tinymce_vars($property['css']['value']);
        $this->set_js(array (
                'tinymce/themes/modern/theme.min',
                'tinymce/plugins/pagebreak/plugin.min',
                'tinymce/plugins/layer/plugin.min',
                'tinymce/plugins/table/plugin.min',
                'tinymce/plugins/save/plugin.min',
                'tinymce/plugins/emoticons/plugin.min',
                'tinymce/plugins/insertdatetime/plugin.min',
                'tinymce/plugins/preview/plugin.min',
                'tinymce/plugins/media/plugin.min',
                'tinymce/plugins/searchreplace/plugin.min',
                'tinymce/plugins/print/plugin.min',
                'tinymce/plugins/contextmenu/plugin.min',
                'tinymce/plugins/paste/plugin.min',
                'tinymce/plugins/directionality/plugin.min',
                'tinymce/plugins/fullscreen/plugin.min',
                'tinymce/plugins/hr/plugin.min',
                'tinymce/plugins/wordcount/plugin.min',
                'tinymce/plugins/noneditable/plugin.min',
                'tinymce/plugins/visualchars/plugin.min',
                'tinymce/plugins/nonbreaking/plugin.min',
                'tinymce/plugins/template/plugin.min',
                'tinymce/plugins/image/plugin.min',
                'tinymce/plugins/code/plugin.min'));

        global $lang,$conf;
        $tmpLang = substr($lang,0,2);
        if (is_file(make_path('js', "tinymce/langs/".$lang, "js")))
        {
            $this->set_js('tinymce/langs/'.$lang.'.js');
        } elseif (is_file(make_path('js', "tinymce/langs/".$tmpLang, "js")))
        {
            $this->set_js('tinymce/langs/'.$tmpLang.'.js');
        } else {
            $tmpLang = substr($conf->default_lang,0,2);
            if (is_file(make_path('js', "tinymce/langs/".$conf->default_lang, "js")))
            {
                $this->set_js('tinymce/langs/'.$conf->default_lang.'.js');
            } else {
                $this->set_js('tinymce/langs/'.$tmpLang.'.js');
            }
        }


        foreach($property as $key => $val)
        {
            if (is_array($val['formpart']))
            {
                $this->set_js_code($val['formpart']['js_code']);
                $this->set_js($val['formpart']['js']);
                $html = $val['formpart']['html'];
                $property[$key]['formpart']=$html;
            }
        }
        $this->set_main($content);
        $this->set_extra_render("property",$property);
        $langbar = $this->get_available_lang();
        $this->set_extra_render("available_lang", $langbar['html']);

        if ($this->message)
        {
            $this->set_js_code('
                    jQuery(document).ready(function(){
                        jQuery.growl({
                            title: "'._("Résultat").'",
                            message: "'.$this->message.'",
                            location: "tr",
                            duration: 3200
                        });
                    });
                ');
        }

        echo $this->gen_page();
    }
    public function return_result($result)
    {
        echo json_encode(array('result' => $result));
        exit;
    }
}
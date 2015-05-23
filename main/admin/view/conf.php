<?php
class view_conf extends admin_common
{
    public $message = false;

    private function set_tinymce_vars($section=array())
    {
        $tmp = 'var all_tinymce=" ';
        //$tmpA = array('tinymce');
        $tmp .='";';
        $tmp .= 'var content_css_set="'.make_path("css","admin","css",false,true).'";';
        global $conf;
        $tmp .= 'var main_base_path="'.$conf->main_base_path.'"';
        $this->set_js_code($tmp);
    }
    private function js_for_page($conf_data)
    {
        $js = "";
        global $conf;
        $this->set_tinymce_vars();
        $js .= <<<EOF


jQuery(document).ready(function(){
EOF;


        foreach($conf_data as $key=>$val)
        {
            if ($val['type'] == 'tinymce')
            {
                $js.= 'jQuery("#'. $val['key'] .'").editInPlace({
                        url: "conf",
                        show_buttons: true,
                        value_required: true,
                        field_type: "textarea",
                        params: "action=update",
                        delegate: delegateeip,
                        use_html: true
                }); '."\n";

            } else {
                $js .= 'jQuery("#'.$val['key'].'").editInPlace({
                            url: "conf",
                            show_buttons: true,
                            value_required: true,
                            field_type: "'.$val['type'].'",
                            params: "action=update",
                            delegate: delegatebutton,
                            use_html: true
                        });'."\n";
            }
            $js.= 'jQuery("#desc_'. $val['key'] .'").editInPlace({
                        url: "conf",
                        show_buttons: true,
                        value_required: true,
                        field_type: "textarea",
                        params: "action=update_description",
                        delegate: delegateeip
                }); '."\n";
        }

        $js.='});';

        return $js;
    }
    public function display_result($result)
    {
        echo $result;
    }
    public function run($conf_data)
    {
        $this->set_title(_("Configuration"));
        $title=$this->req;
        $this->set_css_code('table { border-collapse: collapse;}');
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min'));
        $this->set_js(array('tinymce_var',"jquery.growl",'admin','jquery','jquery-ui.min','jquery-migrate-1.2.1','tinymce/tinymce.min','jquery.editinplace','tinymce/jquery.tinymce.min','validation/jquery.validate.min','validation/additional-methods.min',$this->req,));
        $this->set_js_code($this->js_for_page($conf_data));
        $this->set_extra_render('conf_data', $conf_data);
        global $lang,$conf;
        $tmpLang = substr($lang,0,2);
        if ($tmpLang != "en")
        {
            if (is_file(make_path('js', "validation/localization/messages_".$lang, "js")))
            {
                $this->set_js('validation/localization/messages_'.$lang.'.js');
            } elseif (is_file(make_path('js', "validation/localization/messages_".$tmpLang, "js")))
            {
                $this->set_js('validation/localization/messages_'.$tmpLang.'.js');
            } else {
                $tmpLang = substr($conf->default_lang,0,2);
                if (is_file(make_path('js', "validation/localization/messages_".$conf->default_lang, "js")))
                {
                    $this->set_js('validation/localization/messages_'.$conf->default_lang.'.js');
                } else {
                    $this->set_js('validation/localization/messages_'.$tmpLang.'.js');
                }
            }
        }

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
}
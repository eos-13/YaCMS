<?php

class view_user extends common
{
    public $id;
    public $user;
    public $editable;
    public $edit_mode;
    public function run()
    {
        global $conf;
        $this->set_main(_("Changement de votre fiche"));
        if ($this->edit_mode)
        {
            $this->set_js(array('jquery',
                                'jquery-ui.min',
                                'jquery.growl',
                                'validation/jquery.validate.min',
                                'jquery.validate.password',
                                'tinymce/jquery.tinymce.min',
                                'tinymce/tinymce.min',
                                'tinymce/tinymce_user',
                                'tinymce/plugins/emoticons/plugin.min',
                                'tinymce/plugins/hr/plugin.min',
                                'tinymce/plugins/visualchars/plugin.min',
                                'tinymce/plugins/nonbreaking/plugin.min',
                                $this->req));
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
            $this->set_css(array("jquery.validate.password",'jquery-ui.structure','jquery-ui.min','jquery-ui.theme.min',$this->req));
            $this->set_js_code('var base_root_path="'.$conf->main_base_path.'";');
            $this->set_js_code('var content_css_set="'.join(',',$this->get_css()).'";');
            $this->set_js_code('var max_char_user="'.(isset($conf->userdesc_max_length) && $conf->userdesc_max_length > 0?$conf->userdesc_max_length:5000).'";');
            $this->set_js_code('jQuery(document).ready(function(){ load_tiny_mce_user(content_css_set,base_root_path,max_char_user); })');
            if ($conf->reCaptcha_key."x" != "x")
            {
                $this->set_js_external('https://www.google.com/recaptcha/api.js');
                $this->set_extra_render('captcha_bool', true);
            } else {
                $this->set_extra_render('captcha_bool', false);
            }
        } else {
            $this->set_js(array('jquery','jquery-ui.min','jquery.growl',$this->req));
            $this->set_css(array('jquery-ui.structure','jquery-ui.min','jquery-ui.theme.min',$this->req));
        }
        $this->set_extra_render('editable', $this->editable);
        $this->user->get_all();
        $this->set_extra_render('user', $this->user);
        $this->set_extra_render('edit_mode', $this->edit_mode);
        if ($this->action == "update")
        {
            $this->set_js("jquery.growl");
            $this->set_css('jquery.growl');
            if ($this->success)
            {
                $this->set_js_code('
                    jQuery(document).ready(function(){
                        jQuery.growl({
                            title: "'._("Résultat").'",
                            message: "'.$this->message.'",
                            static: false,
                            location: "tl",
                        });
                    });
                ');
            } else {
                $this->set_js_code('
                    jQuery(document).ready(function(){
                        jQuery.growl.error({
                            title: "'._("Résultat").'",
                            message: "'.$this->message.'",
                            static: false,
                            location: "tl",
                        });
                    });
                ');
            }
        }

        echo $this->gen_page();
    }
    public function send_json($json)
    {
        header ('application/json');
        echo json_encode($json);
        exit;
    }
}


?>
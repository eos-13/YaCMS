<?php

class view_lost_pass extends common
{
    public $id;
    public $token;
    public $email;
    public $success;
    public function run()
    {
        global $conf;
        $this->set_title(_("Lost Pass"));

        $this->set_js(array('jquery','jquery-ui.min','validation/jquery.validate.min','jquery.validate.password',$this->req));
        $this->set_css(array('jquery-ui.structure','jquery-ui.min','jquery-ui.theme.min','jquery.validate.password',$this->req));
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

        $this->set_extra_render('token', $this->token);
        $this->set_extra_render('email', $this->email);
        $this->set_extra_render('success', $this->success);
        echo $this->gen_page();
    }
}
?>
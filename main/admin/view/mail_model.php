<?php
class view_mail_model extends admin_common
{
    public $list_mail_model=false;
    public $id;
    public $message = false;

    public function run()
    {
        //var_dump($this->list_mail_model);
        $this->set_title(_("Modele de mail"));
        $this->set_css(array('jquery-ui.min',"jquery.growl",$this->req));
        $this->set_js(array('jquery',"jquery.growl",'jquery-ui.min','validation/jquery.validate.min',$this->req));
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
        $this->set_extra_render('id', $this->id);
        $this->set_extra_render('list_mail_model', $this->list_mail_model);
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
    public function send_html($html)
    {
        echo $html;
        exit;
    }
    public function send_json($json)
    {
        header ('application/json');
        echo json_encode($json);
        exit;
    }
    public function send_raw($raw)
    {
        echo $raw;
        exit;
    }
}

<?php
class view_edit extends admin_common
{
    public $loadedTab;
    public $message = false;


    public function run()
    {
        global $conf;
        $this->set_title(_('Edition js/css'));
        $this->set_css(array('jquery-ui.min',"jqueryFileTree","jquery.growl",$this->req));
        $this->set_js(array('jquery',"jquery.growl","jqueryFileTree",'jquery-ui.min','validation/jquery.validate.min',$this->req));
        $this->set_js_code('var base_path="'.$conf->main_base_path.'"');
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
        $this->set_extra_render('css_list', $this->datas_list['css']);
        $this->set_extra_render('js_list', $this->datas_list['js']);
        if (isset($this->loadedTab) && $this->loadedTab && $this->loadedTab > 0)
        {
            $this->set_js_code('var loadedTab='.$this->loadedTab.";");
        } else {
            $this->set_js_code('var loadedTab=false;');
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
    public function return_raw($ret)
    {
        if ($ret."x" == " x") echo "";
        else  echo $ret;
    }

}
<?php

class view_template extends admin_common
{
    public $message = false;

    public function run($t_obj)
    {
        $this->set_js(array('admin',$this->req, 'jquery','jquery-ui.min','validation/jquery.validate.min',"jquery.growl",'jquery-ui-multiselect','jquery.fancybox'));
        $this->set_css(array("admin",$this->req,"jquery-ui.structure.min","jquery-ui.theme.min","jquery-ui.min","jquery.growl","jquery-ui-multiselect"));
        $this->set_title(_('Modèle'));

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

        $this->set_js_code("var loadedid = ".(isset($this->id)?"'".$this->id."'":"false").";");
        $this->set_extra_render('template', $t_obj->get_all());
        $this->set_extra_render('list_tpl_disk_main' , $t_obj->hd_data_main);
        $this->set_extra_render('list_tpl_disk_custom' , $t_obj->hd_data_custom);
        $this->set_extra_render('list_tpl_bdd' , $t_obj->bdd_data);
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
        if (isset($this->id))
            $this->set_extra_render('id' , $this->id);
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
        if ($this->message){ $json['message'] = urlencode($this->message); }
        echo json_encode($json);
        exit;
    }
}
<?php

class view_image extends admin_common
{
    public $message=false;

    public function run()
    {
        global $conf;
        $this->set_title(_("Management des images"));
        $this->set_css(array('admin',"jquery.growl","jqueryFileTree",$this->req,'jquery-ui.min'));
        $this->set_js(array('admin',"jquery.growl", "jqueryFileTree",'jquery','jquery-ui.min',$this->req));
        $this->set_js_code('var base_path = "'.$conf->main_base_path.'";');
        $main = "";
        $this->set_main($main);
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
    public function send_json($result)
    {
        header('application/json');
        echo $result;
        exit;
    }
    public function send_html($result)
    {
        header('text/html');
        echo $result;
        exit;
    }
}

<?php

class view_import_xml extends admin_common
{
    public $message = false;
    public $data;

    public function run()
    {
        $this->set_title(_("Import XML"));
        $this->set_css(array('admin',"jquery.growl",'jquery-ui.min',$this->req));
        $this->set_js(array('admin',"jquery.growl",'jquery', 'jquery-ui.min',$this->req));
        $main = "";
        $this->set_main($main);
        $this->set_extra_render('data', $this->data);
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
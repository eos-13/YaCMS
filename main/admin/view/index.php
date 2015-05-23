<?php

class view_index extends admin_common
{

    public $message = false;

    public function run()
    {
        $this->set_title(_('Admin'));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min'));
        $this->set_js(array('admin',"jquery.growl",$this->req, 'jquery','jquery-ui.min','jquery-migrate-1.2.1'));
        $this->set_main(_("Lorem ipsum dolor si amet <h1>test</h1>"));
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

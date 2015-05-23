<?php
class view_manage extends admin_common
{
    public $offline_val=false;
    public $message = false;

    public function run()
    {
        $this->set_title(_("Gestion"));
        $this->set_css(array('jquery-ui.min',$this->req,"jquery.growl"));
        $this->set_js(array('jquery','jquery-ui.min',"jquery.growl",$this->req));
        $this->set_extra_render('offline', $this->offline_val);
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
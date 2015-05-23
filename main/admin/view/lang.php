<?php
class view_lang extends admin_common
{
    public $message = false;
    public $langs;

    public function run()
    {
        $this->set_title(_("Langue"));
        $this->set_css(array('jquery-ui.min',$this->req,"jquery.growl"));
        $this->set_js(array('jquery','jquery-ui.min',"jquery.growl",$this->req));

        $this->set_extra_render('langs', $this->langs);
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
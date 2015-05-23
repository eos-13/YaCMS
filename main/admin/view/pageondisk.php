<?php
class view_pageondisk extends admin_common
{
    public $allpages;
    public $message = false;

    public function run()
    {
        $this->set_title(_('Gestion des page-on-disk'));
        $this->set_css(array('jquery-ui.min',"jquery.growl",$this->req));
        $this->set_js(array('jquery','jquery-ui.min',$this->req,"jquery.growl"));
        $this->set_extra_render("allpages", $this->allpages['allpages']);
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
    public function send_raw($raw)
    {
        echo $raw;
    }
}

?>
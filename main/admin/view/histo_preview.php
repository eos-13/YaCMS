<?php
class view_histo_preview extends admin_common
{
    public $all_previews;
    public $page_refid;
    public $message = false;

    public function run()
    {
        $this->set_title(_('Visualisation des previews'));
        $this->set_css(array($this->req,'jquery-ui.min.css',"jquery.growl"));
        $this->set_js(array("jquery",'jquery-ui.min',"jquery.growl",$this->req));
        $this->set_main("Visualisation des previews:");
        $this->set_extra_render('all_previews', $this->all_previews);
        $this->set_extra_render('page_refid', $this->page_refid);

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
    public function send_json($a)
    {
        echo json_encode($a);
    }
    public function send_html($a)
    {
        echo $a;
    }
}
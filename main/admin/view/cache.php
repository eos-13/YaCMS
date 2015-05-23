<?php
class view_cache extends admin_common
{
    public $id;
    public $csssize;
    public $jssize;
    public $message = false;
    public $cache_page;

    public function run()
    {
        //$this->no_use_cache=true;
        $this->set_title(_("Gestion des caches"));
        $this->set_css(array($this->req,'jquery-ui.min.css',"jquery.growl"));
        $this->set_js(array("jquery",'jquery-ui.min',"jquery.growl",$this->req));
        $this->set_extra_render('csssize', $this->csssize);
        $this->set_extra_render('jssize', $this->jssize);
        $this->set_extra_render('cache_page', $this->cache_page);
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
        echo json_encode($a['json']);
    }
}
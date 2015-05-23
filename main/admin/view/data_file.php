<?php
class view_data_file extends admin_common
{
    public $id;
    public $message = false;

    public function run()
    {
        global $conf;
        $this->set_title(_('Gestion des métadonnées'));
        $this->set_css(array($this->req,'jquery-ui.min.css',"jquery.growl","jqueryFileTree"));
        $this->set_js(array("jquery",'jquery-ui.min',"jquery.growl",'jqueryFileTree',$this->req));
        $this->set_js_code(" var base_path='".$conf->main_base_path."' ");
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
        header('application/json');
        echo json_encode($a['json']);
    }
}
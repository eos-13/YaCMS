<?php

class view_group_rights extends admin_common
{
    public $rights;
    public $allusers;
    public $id;
    public $message = false;

    public function run()
    {
        $this->set_title(_('Droits de groupe'));
        $this->set_css(array($this->req,'jquery-ui.min',"jquery.growl","icheck/line/_all","icheck/line/green"));
        $this->set_js(array("jquery",'jquery-ui.min',"jquery.growl","icheck.min",$this->req));

        $this->set_js_code("jQuery(document).ready(function(){ jQuery('button').button(); });");
        $this->set_extra_render('rights', $this->rights);
        $this->set_extra_render('id', $this->id);
        $this->set_extra_render('allgroups', $this->allgroups);
        $this->set_main("");
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
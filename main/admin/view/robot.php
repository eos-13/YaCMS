<?php

class view_robot extends admin_common
{
    public $robot;
    public $message = false;

    public function run()
    {
        $this->set_title(_("Edit robot.txt"));
        $this->set_css(array($this->req,'jquery-ui.min.css',"jquery.growl"));
        $this->set_js(array("jquery",'jquery-ui.min',"jquery.growl",$this->req));
        $this->set_js_code("jQuery(document).ready(function(){ jQuery('button').button(); });");

        $helper = "<button id='disallow'>"._("Disallow All")."</button>";
        $helper .= "<button id='allow'>"._("Allow All")."</button>";
        $this->set_main("<form method='post' action='robot?action=edit'><textarea cols=80 rows=25 id='robot' name='robot'>".$this->robot."</textarea><br/><button>OK</button>".$helper."</form>");
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
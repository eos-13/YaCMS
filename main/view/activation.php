<?php

class view_activation extends common
{
    public $id;
    public function run()
    {
        global $conf;
        $this->set_main(_("Activation"));

        $this->set_title(_("Activation"));
        $this->set_js(array('jquery','jquery-ui.min',$this->req));
        $this->set_css(array('jquery-ui.structure','jquery-ui.min','jquery-ui.theme.min',$this->req));

        echo $this->gen_page();
    }
}


?>
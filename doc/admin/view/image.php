<?php

class view_image extends admin_common
{
    public $message=false;

    public function run()
    {
        $this->set_title("Management des images");
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min'));
        $this->set_js(array('admin',"jquery.growl", 'jquery','jquery-ui.min',$this->req));
        $main = "";
        $this->set_main($main);
        echo $this->gen_page();
    }
    public function send_json($result)
    {
        header('application/json');
        echo $result;
        exit;
    }
    public function send_html($result)
    {
        header('text/html');
        echo $result;
        exit;
    }
}

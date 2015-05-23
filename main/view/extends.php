<?php

class view_extends extends common
{
    public function run(){

        $title=$this->req;
        $this->set_template_file(make_path('template',$this->req,'html'));
        $this->set_main("Lorem ipsum dolor si amet <h1>test</h1>");
        echo $this->gen_page();
    }
}

?>
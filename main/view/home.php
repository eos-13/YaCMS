<?php

class view_home extends common
{

    public function run(){
        $this->set_title(_('Home'));
        $this->load_page_properties();
        $this->set_template_file(make_path('template',$this->req,'html'));
        $this->set_css($this->req);
        $this->set_js($this->req);
        $this->set_main(_("Welcome Here"));
        echo $this->gen_page();
    }

}

?>
<?php
class view_php_info extends admin_common
{


    public function run()
    {
        $this->set_css_code('.sidr-class-nav a:link { background: transparent;}');
        $this->set_css(array('admin','jquery-ui.min',$this->req));
        $this->set_template_file(make_path('template',$this->req,'html'));
        ob_start();
        phpinfo();
        $result = ob_get_clean();
        $this->set_main($result);
        echo $this->gen_page();
    }
}
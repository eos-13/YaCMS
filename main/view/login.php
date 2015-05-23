<?php

class view_login extends common
{
    public $failed_login;
    public function js_for_page()
    {
        $js = <<<EOF

EOF;
        return $js;
    }

    public function run()
    {
        $this->set_title(_("Login"));
        $this->load_page_properties();
        $this->set_template_file(make_path('template',$this->req,'html'));
        $this->set_css(array($this->req,'jquery-ui.min'));
        $this->set_js(array($this->req,'jquery-ui.min','jquery-md5.js'));
        $this->set_js_code("jQuery(document).ready(function(){ jQuery('button').button(); }); ");
        if (isset($_REQUEST['url']))
        {
            $this->set_extra_render('url', $_REQUEST['url']);
        }
        $this->set_js_code($this->js_for_page());
        $main = "";
        if ($this->failed_login == true)
        {
            global $session;
            $msg = $session->get('feedback_negative');
            $msg = $msg[count($msg) - 1];
            $this->set_extra_render('msg', $msg );
            $main = _("Login failed ! ");
        }
        $this->set_main($main);
        echo $this->gen_page();
    }
    public function redirect($url)
    {
        header("Location:".$url);
        exit;
    }

}

?>

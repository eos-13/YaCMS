<?php

class view_search extends common
{

    public function run()
    {
        global $conf;
        $this->set_css(array('jquery-ui.structure.min.css', 'jquery-ui.theme.css','jquery-ui.min.css'));
        $this->set_js("jquery","jquery-ui","jquery.growl");
        $this->set_main("<form id='search_form' method='post' action='lr'><input id='search' name='s' placeholder='Search'><button>"._("Go")."</button></input></form>");
        echo $this->gen_page();
    }
    public function send_json($json)
    {
        header('application/json');
        echo json_encode($json);
        exit;
    }
}

?>
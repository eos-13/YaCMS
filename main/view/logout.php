<?php

class view_logout extends common
{


    public function run(){
        global $conf;
        header('Location: '.$conf->main_url_root );
    }

}

?>
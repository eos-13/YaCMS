<?php
class model_logout extends common
{
    public function run()
    {
        global $session;
        $this->delete_cookie();
        $session->destroy();
    }
    public function delete_cookie()
    {
        global $conf;
        setcookie('remember_me', false, time() - (3600 * 24 * 3650), $conf->cookie_path);
    }
}
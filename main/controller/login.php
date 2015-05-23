<?php

class controller_login extends common
{
    private $model;
    public function run(){
        // On demande les 5 derniers billets (modèle)
        $file = make_path('model', $this->req, 'php');
        if ($file){
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false,$this->search_mode);
        $failed_login=false;
        $cur_user_id=false;
        if (isset($_REQUEST['action']))
            $cur_user_id = $this->action($_REQUEST['action'],$_REQUEST);
        if ($cur_user_id)
        {
            global $conf;
            header('Location:'.$conf->main_url_root );

        } else {
            $failed_login=true;
        }
        // On affiche la page (vue)
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $view = new view_login($this->db,$this->req,false,false,$this->search_mode);
            $view->failed_login = $failed_login;
            $view->run();

        }
    }
    private function action($action,$post)
    {
        switch ($action)
        {
            case "login":
            {
                global $current_user;
                $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret =  $this->model->login($post);
                if ($ret && $post['url']."x" != "x")
                    header('Location:'.urldecode($post['url']) );
                else
                    return $ret;
            }
            break;
        }
    }
}
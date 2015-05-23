<?php

class controller_logout extends common
{
    private $model;
    public function run()
    {
        global $current_user;
        $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). " Action logout  Params: ".print_r($_REQUEST,1),LOG_DEBUG);
        $file = make_path('model', $this->req, 'php');
        if ($file){
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false,$this->search_mode);
        $sql = $this->model->run();
        // On affiche la page (vue)
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $view = new view_logout($this->db,$this->req,false,false,$this->search_mode);
            $view->run();

        }
    }
}
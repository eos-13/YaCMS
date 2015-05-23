<?php
class controller_register extends common
{
    private $model;
    private $message = false;
    private $action = false;

    public function run()
    {
        global $current_user;
        // On demande les 5 derniers billets (modèle)
        $file = make_path('model', $this->req, 'php');
        if ($file){
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req);
        $ret = false;
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
            $this->action = $_REQUEST['action'];
        }
        $sql = $this->model->run();
        // On affiche la page (vue)
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $view = new view_register($this->db,$this->req,false,false,$this->search_mode);
            if ($ret && isset($ret['json']))
            {
                $view->send_json($ret['json']);
            } else {
                $view->message = $this->message;
                $view->action =$this->action;
                $view->success = $ret;
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch ($action)
        {
            case "add":
            {
                $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->add($post);
                $this->message = $this->model->message;
                return $ret;
            }
            break;
            case "validate":
            {
                $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->validate($post);
                return array('json' => $ret);
            }
            break;
        }
    }
}
<?php
class controller_user extends common
{
    private $model;
    private $id;
    private $user;
    private $edit_mode;
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
        if (isset($_REQUEST['id']))
        {
            $this->id = $_REQUEST['id'];
            $u = new user($this->db);
            $u->fetch_by_md5($this->id);
            $this->user = $u;
        } else {
            $this->id = $current_user->get_md5();
            $this->user = $current_user;
        }
        $this->model->id = $this->id;
        $this->model->user = $this->user;
        $this->editable = $this->model->editable($_REQUEST);
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
            $view = new view_user($this->db,$this->req,false,false,$this->search_mode);
            if ($ret && isset($ret['json']))
            {
                $view->send_json($ret['json']);
            } else {
                $view->user = $this->user;
                $view->id = $this->id;
                $view->editable = $this->editable;
                $view->edit_mode = $this->edit_mode;
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
            case "update":
            {
                $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->update($post);
                $this->message = $this->model->message;
                $this->user->fetch($this->user->id);
                return $ret;
            }
            break;
            case "edit":
            {
                $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                if ($this->model->editable($post))
                    $this->edit_mode=true;
                else
                    $this->edit_mode=false;
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
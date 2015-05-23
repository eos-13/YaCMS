<?php
class controller_lost_pass extends common
{
    private $model;
    private $id;
    private $user;
    private $message = false;
    private $action = false;
    private $token;
    private $email;
    private $success = false;

    public function run()
    {
        global $current_user;
        // On demande les 5 derniers billets (modèle)
        $file = make_path('model', $this->req, 'php');
        if ($file){
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false,$this->search_mode);

        $ret = false;
        if (isset($_REQUEST['email']))
        {
            $this->email = $_REQUEST['email'];
            $this->model->email = $this->email;
        }
        if (isset($_REQUEST['token']))
        {
            $this->token = $_REQUEST['token'];
            $this->model->token = $this->token;
        }
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
            $this->action = $_REQUEST['action'];
            $this->model->action = $this->action;
        }

        $sql = $this->model->run();
        // On affiche la page (vue)
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $view = new view_lost_pass($this->db,$this->req,false,false,$this->search_mode);
            $view->token = $this->token;
            $view->email = $this->email;
            $view->success = $this->success;
            if ($ret && isset($ret['json']))
            {
                $view->send_json($ret['json']);
            } else {
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch ($action)
        {
            case "valid_token":
            {
                $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->valid_token($post);
                $this->success = $this->model->success;
            }
            break;
            case "gen_tmp_token":
            {
                $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $u = $this->model->gen_tmp_token();
                if ($u)
                {
                    global $trigger;
                    $trigger->run_trigger("SEND_MAIL_TMPTOKEN",$u,$u);
                }

            }
            break;
        }
    }
}
<?php
class controller_akismet extends admin_common
{
    private $id;
    private $model;
    private $check_key = false;
    private $message = false;


    public function run()
    {
        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        if (isset($_REQUEST['id']))
            $this->id = $_REQUEST['id'];
        $this->model->id = $this->id;
        $data = false;
        if (isset($_REQUEST['action']))
        {
            $data = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $tmp = $this->model->run();
        $this->check_key = $this->model->check_key;
        if( ! $tmp) {
            include_once 'errors/404.php';
            exit;
        }

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            if ($data && isset($data['json']) )
            {
                $view->json($data['json']);
            } else if ($data && isset($data['html']))
            {
                $view->html($data['html']);
            } else {
                $view->message = $this->message;
                $view->check_key = $this->check_key;
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "list":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                return array('json' => $this->model->list_comment());
            }
            break;
            case "edit":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                return array('json' => $this->model->edit($post));
            }
            break;
            case "spam":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                return array('json' => $this->model->edit($post));
            }
            break;
            case "detail":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                return array('html' => $this->model->detail($post));
            }
            break;
        }
    }
}


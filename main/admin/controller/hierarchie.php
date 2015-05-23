<?php
class controller_hierarchie extends admin_common
{
    private $id;
    private $model;
    private $message = false;
    private $all_page;
    private $all_page_meta;
    private $root = 1;

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
        $tmp = false;
        if (!isset($_REQUEST['action']))
            $tmp = $this->model->run();
        else
            $tmp = true;
        $this->all_page = $this->model->all_page;
        $this->all_page_meta = $this->model->all_page_meta;
        $this->root = $this->model->root;
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
                $view->send_json($data['json']);
            } else if ($data && isset($data['html']))
            {
                $view->send_html($data['html']);
            } else {
                $view->message = $this->message;
                $view->all_page = $this->all_page;
                $view->all_page_meta = $this->all_page_meta;
                $view->root = $this->root;
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "newConnection":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->newConnection($post);
                $this->message = $this->model->message;
                return array('json' => array('message' => $this->message));
            }
            break;
            case "delConnection":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->delConnection($post);
                $this->message = $this->model->message;
                return array('json' => array('message' => $this->message));
            }
            break;
            case "moveConnection":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->delConnection($post);
                $this->message = $this->model->message;
                return array('json' => array('message' => $this->message));
            }
            break;
            case "storePos":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->storePos($post);
                $this->message = $this->model->message;
                return array('json' => array('message' => $this->message));
            }
            break;
        }
    }
}
<?php
class controller_edit extends admin_common
{

    private $id;
    private $model;
    public $loadedTab=false;
    private $type;
    private $dir;
    private $message = false;

    public function run(){
        load_alternative_class('class/page.class.php');

        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        if (isset($_REQUEST['id']))
            $this->id = $_REQUEST['id'];
        if (isset($_REQUEST['type']))
            $this->type = $_REQUEST['type'];
        if (isset($_REQUEST['dir']))
            $this->dir = $_REQUEST['dir'];
        $this->model->id = $this->id;
        $this->model->type = $this->type;
        $this->model->dir = $this->dir;
        $ret = false;
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }

        $tmp = $this->model->run();
        if ($this->model->loadedTab) {
            $this->loadedTab = $this->model->loadedTab;
        }

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false,$this->search_mode);
            $view->datas_list=$tmp;
            $view->loadedTab = $this->loadedTab;
            if ($ret)
            {
                $view->return_raw($ret);
            } else {
                $view->message = $this->message;
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "get_content":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->get_content($post);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "save":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->save($post);
                $this->message = $this->model->message;
            }
            break;
            case "validate":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                return $this->model->validate($post);
            }
            break;
            case "clone":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->clone_file($post);
                $this->message = $this->model->message;
            }
            break;
            case "delete":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->delete_file($post);
                $this->message = $this->model->message;
            }
            break;
            case "new":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->new_file($post);
                $this->message = $this->model->message;
            }
            break;
        }
    }
}


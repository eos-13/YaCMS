<?php
class controller_group extends admin_common
{
    private $id;
    private $model;
    private $message = false;

    public function run()
    {
        load_alternative_class('class/group.class.php');

        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        if (isset($_REQUEST['id']))
        {
            $this->id = $_REQUEST['id'];
            $this->model->id = $this->id;
        }
        $json = false;
        if (isset($_REQUEST['action']))
        {
            $json = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $datas = $this->model->run();
        if( ! $datas) {
            include_once 'errors/404.php';
            exit;
        }

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            if ($json){
                $view->json($json);
            } else {
                $view->message = $this->message;
                $view->datas= $datas;
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
                $res =  $this->model->list_group();
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "edit":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->edit_group($post);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "valid":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->valid_group($post);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "add_members":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->add_members($post);
                $this->message = $this->model->message;
                return $ret;
            }
            break;
            case "rem_members":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->rem_members($post);
                $this->message = $this->model->message;
                return $ret;
            }
            break;
            case "list_members":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = json_encode($this->model->list_members($post['id']));
                $this->message = $this->model->message;
                return $ret;
            }
            break;
        }
    }
}
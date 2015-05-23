<?php
class controller_mail_model extends admin_common
{
    private $model;
    private $id;
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
        $ret = false;
        if (isset($_REQUEST['id']))
        {
            $this->id = $_REQUEST['id'];
            $this->model->id = $this->id;
        }

        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }
        $tmp=false;
        $tmp = $this->model->run();

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            $view->id = $this->id;
            if ($tmp)
                $view->list_mail_model = $tmp;
            if($ret && $_REQUEST['action'] == "get_content")
                $view->send_html($ret);
            elseif ($ret)
                $view->send_json($ret);
            else{
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
            case "add":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $json_id = $this->model->add();
                $this->message = $this->model->message;
                return $json_id;
            };
            break;
            case "clone_mail_model":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $json_id = $this->model->clone_mail_model($post);
                $this->message = $this->model->message;
                return $json_id;
            };
            break;
            case "del":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->del($post);
                $this->message = $this->model->message;
                header('Location:mail_model'.($this->message?"?message=".urlencode($this->message):""));
                exit;
            }
            break;
            case "validate":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->validate($post);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "update":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $json_id = $this->model->update($post);
                $this->message = $this->model->message;
                header('Location:mail_model?id='.$post['id'].($this->message?"&message=".urlencode($this->message):""));
            };
            break;
            case "get_content":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->get_content($post);
                $this->message = $this->model->message;
                return $res;
            }
            break;
        }
    }
}
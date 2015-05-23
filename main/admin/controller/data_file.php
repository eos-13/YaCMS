<?php
class controller_data_file extends admin_common
{
    private $model;
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
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $tmp = $this->model->run();

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            if ($ret && isset($ret['json']))
            {
                $view->send_json($ret);
            }elseif ($ret){
                $view->return_result($ret);
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
            case "edit":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                //$this->model->edit($post);
                $this->model->edit($post);
                $this->message = $this->model->message;
                return array('json' => array("result" => $this->message));
            }
            break;
            case "get_info":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                return array('json' => $this->model->get_info($post));
            }
            break;
            case 'add_key':
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->add_key($post);
                $this->message = $this->model->message;
                return array('json' => array("result" => $this->message));
            }
            break;
            case 'del':
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->del($post);
                $this->message = $this->model->message;
                return array('json' => array("result" => $this->message));
            }
            break;
        }
    }
}


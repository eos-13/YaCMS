<?php
class controller_manage extends admin_common
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
        $result = false;
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $array = $this->model->run();

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            $view->offline_val = $array['offline'];
            $view->message = $this->message;
            $view->run();
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "set_offline":
                {
                    $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                    $res = $this->model->set_offline($post);
                    $this->message = $this->model->message;
                    return $res;
                }
                break;
            case "unset_offline":
                {
                    $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                    $res = $this->model->unset_offline($post);
                    $this->message = $this->model->message;
                    return $res;
                }
                break;
            case "gen_dot":
                {
                    $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                    $res = $this->model->gen_dot();
                    $this->message = $this->model->message;
                    return $res;
                }
                break;
            case "backup":
                {
                    $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                    $res = $this->model->backup($post);
                    $this->message = $this->model->message;
                    return $res;
                }
                break;
        }
    }
}
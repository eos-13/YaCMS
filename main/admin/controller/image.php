<?php
class controller_image extends admin_common
{
    private $model;
    private $message;

    public function run()
    {
        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        $data = false;
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        if (isset($_REQUEST['action']))
        {
            $data = $this->action($_REQUEST['action'],$_REQUEST);
        }
        $this->message = $this->model->message;
        $tmp = $this->model->run();

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
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "redim_path":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->message = $this->model->message;
                return array('json' => $this->model->redim_path($post));
            }
            break;
            case "make_thumb":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->message = $this->model->message;
                return array('json' => $this->model->make_thumb($post));
            }
            break;
        }
    }
}


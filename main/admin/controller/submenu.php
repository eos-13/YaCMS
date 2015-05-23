<?php
class controller_submenu extends admin_common
{
    private $model;
    private $message = false;
    private $submenu = array();

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
        $this->submenu = $this->model->submenu;

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            if ($ret)
            {
                $view->return_result($ret);
            } else {
                $view->message = $this->message;
                $view->submenu = $this->submenu;
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "save":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->save($post);
                $this->message = $this->model->message;
            }
            break;
        }
    }
}
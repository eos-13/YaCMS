<?php
class controller_robot extends admin_common
{
    private $model;
    private $message = false;

    public function run()
    {
        // On demande les 5 derniers billets (modèle)
        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        if (isset($_REQUEST['action']))
        {
            $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }
        $robot = $this->model->run();

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $class = 'view_'.$this->req;
            $view = new $class($this->db,$this->req,false,false);
            $view->robot = $robot;
            $view->message = $this->message;
            $view->run();
        }
    }
    protected function action($action,$post)
    {
        global $current_user;
        switch($action)
        {

            case "edit":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->set_robot($post['robot']);
                $this->message = $this->model->message;
            }
            break;
        }
    }
}
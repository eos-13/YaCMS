<?php
class controller_message_publication extends admin_common
{
    private $model;
    private $id;
    private $message = false;

    public function run()
    {
        load_alternative_class('class/message_publication.class.php');

        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        $ret = false;
        if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0)
        {
            $id=$_REQUEST['id'];
            $this->id = $id;
            $this->model->id = $id;
        }
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
            if ($this->id) $view->id = $this->id;
            if ($ret){
                $view->return_result($ret);
            } else {
                $load = (isset($_REQUEST['load'])?$_REQUEST['load']:0);
                $view->message = $this->message;
                $view->run((isset($tmp['content'])?$tmp['content']:false),
                           (isset($tmp['property'])?$tmp['property']:false),
                           (isset($tmp['allgroup_publi'])?$tmp['allgroup_publi']:false),
                           $load);
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
                $this->model->edit($post);
                $this->message = $this->model->message;
            }
            break;
            case "edit_properties":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->edit_properties($post);
                $this->message = $this->model->message;
            }
            break;
        }
    }
}
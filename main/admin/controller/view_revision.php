<?php
class controller_view_revision extends admin_common
{
    private $id;
    private $model;
    private $can_edit = false;
    private $message = false;


    public function run()
    {
        load_alternative_class('class/revision.class.php');

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
        $this->can_edit = $this->model->can_edit;

        if( ! $tmp && isset($_REQUEST['action']) && $_REQUEST['action'] != "del_page") {
            include_once 'errors/404.php';
            exit;
        }

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            $page = new revision($this->db);
            $page->fetch($this->id);
            if ($ret){
                $view->send_json($ret['json']);
            } else {
                $load = (isset($_REQUEST['load'])?$_REQUEST['load']:0);
                $view->can_edit = $this->can_edit;
                $view->message = $this->message;
                $view->run($tmp['content'],$tmp['section'],$tmp['property'],$tmp['last_edit'],$this->id,$page,$load);
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "replace":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->replace($post);
                if ($ret)
                    return (array('json' => array('message' => $this->message)));
                else
                    return (array('json' => array('message' => $this->message)));
            }
            break;
        }
    }
}


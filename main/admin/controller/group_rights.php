<?php
class controller_group_rights extends admin_common
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
        $a = false;
        if (isset($_REQUEST['action']))
        {
            $a = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['id']))
        {
            $this->model->id = $_REQUEST['id'];
            $this->id = $_REQUEST['id'];
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }
        $ret = $this->model->run();

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $class = 'view_'.$this->req;
            $view = new $class($this->db,$this->req,false,false);
            if ($ret)
            {
                $view->allgroups = $ret['allgroups'];
                $view->rights = $ret['rights'];
            }
            if ($a)
            {
                $view->send_json($a);
            } else {
                if (isset($_REQUEST['id']))
                {
                    $view->id = $_REQUEST['id'];
                } else {

                    $view->id = $this->model->id;
                }
                $view->message = $this->message;
                $view->run();
            }
        }
    }
    protected function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "get_rights":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret =  array('json' => $this->model->get_rights($post['id']));
                $this->message = $this->model->message;
                return $ret;
            }
            break;
            case "update":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->update($post);
                $this->message = $this->model->message;
                header('Location: group_rights?id='.$post['id'].($this->message?"&message=".urlencode($this->message):""));
            }
            break;
        }
    }
}
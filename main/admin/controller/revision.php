<?php
class controller_revision extends admin_common
{
    private $model;
    private $page_refid;
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
        if (isset($_REQUEST['page_refid']))
        {
            $this->page_refid = $_REQUEST['page_refid'];
            $this->model->page_refid = $this->page_refid;
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $data = false;
        if (isset($_REQUEST['action']))
        {
            $data = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (!isset($_REQUEST['action']) || ( isset($_REQUEST['action']) && $_REQUEST['action'] != "view" && $_REQUEST['action'] != "delete" ))
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
                $view->page_refid = $this->page_refid;
                $view->all_revisions = $tmp['all_revisions'];
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
            case "delete":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->delete($post);
                $this->message = $this->model->message;
                if ($ret)
                    return (array('json' => array('message' => "Opération effectuée")));
                else
                    return (array('json' => array('message' => "KO")));
            }
            break;
        }
    }
}


<?php
class controller_result_forms extends admin_common
{
    private $id;
    private $model;
    private $message = false;
    private $forms_list;
    private $display_jgGrid = false;
    private $action;
    private $stats;

    public function run()
    {
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
        $data = false;
        if (isset($_REQUEST['action']))
        {
            $data = $this->action($_REQUEST['action'],$_REQUEST);
            $this->action = $_REQUEST['action'];
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $tmp = $this->model->run();
        if( ! $tmp) {
            include_once 'errors/404.php';
            exit;
        }
        $this->forms_list = $this->model->forms_list;
        $this->stats = $this->model->stats;
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            if ($data && isset($data['json']) )
            {
                $view->json($data['json']);
            } else if ($data && isset($data['html']))
            {
                $view->html($data['html']);
            } else {
                $view->id = $this->id;
                $view->message = $this->message;
                $view->forms_list = $this->forms_list;
                $view->model_return = $data;
                $view->display_jgGrid = $this->display_jgGrid;
                $view->action = $this->action;
                $view->stats = $this->stats;
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "list":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->list_results_jqGrid($post);
                return array('json' => $res);
            }
            break;
            case "make_excel":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->make_excel($post);
            }
            break;
            case "make_jqgrid":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->make_jqgrid($post);
                $this->display_jgGrid = true;
                return $res;
            }
            break;
            case "del":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->del($post);
                $this->message = $this->model->message;
                return array('json' => json_encode($this->message));
            }
            break;
        }
    }
}


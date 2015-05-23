<?php
class controller_import_xml extends admin_common
{
    private $model;
    private $message = false;
    private $data;


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
        if (isset($_REQUEST['action']))
        {
            $data = $this->action($_REQUEST['action'],$_REQUEST);
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
                if ($this->data) $view->data = $this->data;
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
            case "import_file":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->import_file($post);
                $this->message = $this->model->message;
                $this->data = $this->model->data;
                return false;
            }
            break;
            case "process_file":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $pid = $this->model->process_file($post);
                $this->message = $this->model->message;
                $this->data = $this->model->data;
                if ($pid) header('Location: edit_page?id='.$pid."&message=".urlencode($this->message));
                return false;
            }
        }
    }
}


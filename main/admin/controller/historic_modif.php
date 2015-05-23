<?php
class controller_historic_modif extends admin_common
{
    private $id;
    private $model;
    private $page_refid;
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
        if (isset($_REQUEST['id']))
            $this->id = $_REQUEST['id'];
        if (isset($_REQUEST['page_refid']))
            $this->page_refid = $_REQUEST['page_refid'];
        $this->model->id = $this->id;
        $this->model->page_refid = $this->page_refid;

        $json = false;
        if (isset($_REQUEST['action']))
        {
            $json = $this->action($_REQUEST['action'],$_REQUEST);
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
            $view->page_refid = $this->page_refid;
            if ($json){
                $view->json($json);
            } else {
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
                return $this->model->list_historic_modif();
            }
            break;
            case "extra_datas":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                return json_encode($this->model->extra_datas($post['id']));
            }
            break;
        }
    }
}


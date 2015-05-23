<?php
class controller_map extends admin_common
{
    private $model;
    private $message = false;
    private $root;

    public function run(){
        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        $array = false;
        if (isset($_REQUEST['action']))
        {
            $array = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }
        if (!$array)
            $array = $this->model->run();

        $this->root = $this->model->root;

        $file = make_path('view', $this->req, 'php');
        if ($file){
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            if (isset($_REQUEST['action']) && $_REQUEST['action'] == "data")
            {
                $view->json($array['json']);
            } else {
                load_alternative_class('class/page.class.php');
                $noparent=array();
                foreach($array['noparent'] as $key=>$val)
                {
                    $page = new page($this->db);
                    $page->fetch($val);
                    $noparent[]=array("title"=>$page->get_title(), "id" => $page->get_id());
                }
                $allpage = array();
                foreach($array['allpage'] as $key=>$val)
                {
                    $page = new page($this->db);
                    $page->fetch($val);
                    $allpage[]=array("title"=>$page->get_title(), "id" => $page->get_id());
                }
                $view->root = $this->root;
                $view->run($noparent,$allpage);
            }

        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "data":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->json();
                $this->message = $this->model->message;
                return $res;
            }
            break;
        }
    }
}

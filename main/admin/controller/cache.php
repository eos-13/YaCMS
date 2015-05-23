<?php
class controller_cache extends admin_common
{
    private $model;
    private $message = false;

    public function run()
    {
        load_alternative_class('class/comment.class.php');

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
                $view->csssize = convert_size($tmp['css']);
                $view->jssize = convert_size($tmp['js']);
                $view->cache_page = $tmp['cache_page'];
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
            case "clean_cache":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->clean_cache($post);
                $this->message = "Clean Cache OK";
            }
            break;
        }
    }
}


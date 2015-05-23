<?php
class controller_pageondisk extends admin_common
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
        $ret = false;
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }
        $id = false;
        if (isset($_REQUEST['id']))
        {
            $id = $this->model->transform_id($_REQUEST['id']);
            $this->id = $id;
            $this->model->id =$id;
        }
        $allpages = $this->model->run();
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            $view->allpages = $allpages;
            $view->id = $id;
            if ($ret)
                $view->send_raw($ret);
            else
                $view->run();
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "get":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->get_data($post);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "update":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->update($post);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "reference":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->reference($post);
                $this->message = $this->model->message;
                header('Location:pageondisk?id='.$ret.($this->message?"&message=".urlencode($this->message):""));
                exit;
            }
            break;
            case "reindex":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->reindex($post);
                $this->message = $this->model->message;
                header('Location:pageondisk?id='.$post['id'].($this->message?"&message=".urlencode($this->message):""));
                exit;
            }
            break;
            case "del":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->del($post);
                $this->message = $this->model->message;
                header('Location:pageondisk'.($this->message?"?message=".urlencode($this->message):""));
                exit;
            }
        }
    }
}

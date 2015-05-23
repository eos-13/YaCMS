<?php
class controller_lang extends admin_common
{
    private $model;
    private $message = false;
    public $langs;

    public function run()
    {
        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        $result = false;
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $array = $this->model->run();
        $this->langs = $this->model->langs;
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            $view->message = $this->message;
            $view->langs = $this->langs;
            $view->run();
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "lang":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                header('location:?lang='.$post['lang']."&message="._("Langue changée"));
                return $res;
            }
            break;
            case "editlang":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                header('location:?editlang='.$post['editlang']."&message="._("Langue changée"));
                return $res;
            }
           break;
        }
    }
}
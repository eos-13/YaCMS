<?php
class controller_forms extends admin_common
{
    private $id;
    private $message = false;
    private $list;
    private $form;
    private $all_page;


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
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $tmp = $this->model->run();
        $this->list = $this->model->list;
        $this->form = $this->model->form;
        $this->all_page = $this->model->all_page;
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
                $view->message = $this->message;
                $view->list = $this->list;
                $view->form = $this->form;
                $view->all_page = $this->all_page;
                $view->id = $this->id;
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "save":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $a = $this->model->save($post);
                $this->message = $this->model->message;
                return array('json' => $a);
            }
            break;
            case "publish":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $a = $this->model->publish($post);
                $this->message = $this->model->message;
                return array('json' => $a);
            }
            break;
            case "add":
            {
                    $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                    $a = $this->model->add($post);
                    $this->message = $this->model->message;
                    return array('json' => $a);
            }
            break;
            case "del":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $a = $this->model->del($post);
                $this->message = $this->model->message;
                return array('json' => $a);
            }
            break;
            case "clone":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $a = $this->model->clone_form($post);
                $this->message = $this->model->message;
                return array('json' => $a);
            }
            break;
            case "unpubli":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $a = $this->model->unpubli($post);
                $this->message = $this->model->message;
                return array('json' => $a);
            }
            break;
        }
    }
}


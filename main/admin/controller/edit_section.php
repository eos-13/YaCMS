<?php
class controller_edit_section extends admin_common
{
    private $id;
    private $model;
    private $can_unlock = false;
    private $can_edit = false;
    private $message = false;

    public function run()
    {
        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        if (isset($_REQUEST['id'])) $this->id = $_REQUEST['id'];
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        $this->model->id = $this->id;
        $ret=false;
        if (isset($_REQUEST['action']))
            $ret = $this->action($_REQUEST['action'],$_REQUEST);

        $section = $this->model->run();
        $this->can_unlock = $this->model->can_unlock;
        $this->can_edit = $this->model->can_edit;

        if( ! $section && $_REQUEST['action'] != 'delete')
        {
            include_once 'errors/404.php';
            exit;
        }
        $all_page = $this->model->all_page($section->get_page_refid());
        $info_page = $this->model->info_page($section->get_page_refid());

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            $view->id = $this->id;
            if ($ret) {
                $view->json(array('result' => $ret));
            } else {
                $view->can_unlock = $this->can_unlock;
                $view->can_edit = $this->can_edit;
                $view->message = $this->message;
                $view->run($section,$all_page,$info_page);
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch($action)
        {
            case "edit":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->update($post);
                $this->message = $this->model->message;
            }
            break;
            case "delete":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->delete();
                $this->message = $this->model->message;
            }
            break;
            case "desactivate":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->deactivate();
                $this->message = $this->model->message;
                exit;
            }
            break;
            case "activate":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->activate();
                $this->message = $this->model->message;
                exit;
            }
            break;
            case "clone":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res =  $this->model->section_clone();
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "clone_to_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->clone_to_page($post['new_page']);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "move_to_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res=$this->model->change_page($post['new_page']);
                $this->message = $this->model->message;
                if ($res) return $post['new_page'];
            }
            break;
            case "unlock_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->unlock_page($post);
                $this->message = $this->model->message;
                return true;
            }
            break;
            case "lock_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->lock_page($post);
                $this->message = $this->model->message;
                return true;
            }
            break;
        }
    }
}


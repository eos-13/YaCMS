<?php

ini_set('max_execution_time',3600);
class controller_edit_page extends admin_common
{
    private $id;
    private $model;
    private $can_unlock = false;
    private $can_edit = false;
    private $message = false;
    private $pages_lang;


    public function run()
    {
        load_alternative_class('class/page.class.php');

        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false);
        if (isset($_REQUEST['id']))
        {
            if (!is_numeric($_REQUEST['id']) && !$_REQUEST['action'] == "switch_lang")
            {
                include_once 'errors/500.php';
                exit;
            } else {
                $this->id = $_REQUEST['id'];
                $this->model->id = $this->id;
                $this->model->set_page();
            }
        }
        $ret = false;
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message = $_REQUEST['message'];
        }

        $tmp = $this->model->run();
        $this->pages_lang = $this->model->pages_lang;
        $this->can_unlock = $this->model->can_unlock;
        $this->can_edit = $this->model->can_edit;

        if( ! $tmp && isset($_REQUEST['action']) && $_REQUEST['action'] != "del_page") {
            include_once 'errors/404.php';
            exit;
        }

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            $page = new page($this->db);
            $page->fetch($this->id);
            if ($ret && $_REQUEST['action'] == "validate")
            {
                $view->return_raw($ret);
            } elseif ($ret  && $_REQUEST['action'] != "edit_form" ){
                $view->return_result($ret);
            } else {
                $load = (isset($_REQUEST['load'])?$_REQUEST['load']:0);
                $view->can_unlock = $this->can_unlock;
                $view->can_edit = $this->can_edit;
                $view->message = $this->message;
                $view->pages_lang = $this->pages_lang;
                $view->run($tmp['content'],$tmp['section'],$tmp['property'],$tmp['group_publi'],$tmp['has_draft'],$tmp['can_approuve'],$tmp['last_edit'],$this->id,$page,$load,$this->model->get_all_page());
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
                $this->model->edit($post);
                $this->message = $this->model->message;
            }
            break;
            case "edit_sections":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->edit_sections($post);
                $this->message = $this->model->message;
            }
            break;
            case "edit_properties":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->edit_properties($post);
                $this->message = $this->model->message;
            }
            break;
            case "add_section":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                return $this->model->add_section();
                $this->message = $this->model->message;
            }
            break;
            case "del_section":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->del_section($post['section_id']);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "deactivate_section":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->deactivate_section($post['section_id']);
                $this->message = $this->model->message;
                return 1;
            }
            break;
            case "activate_section":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->activate_section($post['section_id']);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "clone_section":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->clone_section($post['section_id']);
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "add_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $r = $this->model->add_page();
                $this->message = $this->model->message;
                return $r;
            }
            break;
            case "del_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->del_page();
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "deactivate_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->deactivate_page();
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "activate_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->activate_page();
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "clone_page":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->clone_page();
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "make_a_draft":
            {
                    $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                    $res = $this->model->make_a_draft();
                    $this->message = $this->model->message;
                    return $res;
            }
            break;
            case "clone_to_section":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->clone_to_section($post['section_id'],$post['new_page_id']);
                $res = $post['new_page_id'];
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "move_section":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->move_section($post['section_id'],$post['new_page_id']);
                $res = $post['new_page_id'];
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "export_xml":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->export_xml();
                $this->message = $this->model->message;
                return $res;
            }
            break;
            case "validate":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->validate($post);
                return $res;
            }
            break;
            case "save_extra_params":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->save_extra_params($post);
                $this->message = $this->model->message;
                header('Location: edit_page?id='.$post['id']."&load=".$post["load"].($this->message?"&message=".urlencode($this->message):""));
            }
            break;
            case "set_group_publi":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->set_group_publi($post);
                $this->message = $this->model->message;
                return false;
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
            case "save_plugin":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $this->model->save_plugin($post);
                $this->message = $this->model->message;
                header('Location: edit_page?id='.$post['id']."&load=".$post["load"].($this->message?"&message=".urlencode($this->message):""));
            }
            break;
            case "publish_a_draft":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->save_plugin($post);
                $this->message = $this->model->message;
                header('Location: edit_page?id='.$ret.($this->message?"&message=".urlencode($this->message):""));
            }
            break;
            case "publish":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->publish($post);
                $this->message = $this->model->message;
                return $post['id'];
            }
            break;
            case "make_a_rev":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->make_a_rev($post);
                $this->message = $this->model->message;
                return $ret;
            }
            break;
            case "edit_form":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->edit_form($post);
                $this->message = $this->model->message;
                return $ret;
            }
            break;
            case "switch_lang":
            {
                if (!$this->id)
                {
                    $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                    $ret = $this->model->switch_lang($post);
                    $this->message = $this->model->message;
                    if ($ret) header('location:edit_page?id='.$ret.'&message='.urlencode($this->message));
                    else header('location:edit_page?id='.$post['from_id'].'&editlang='.$post['current_edit_lang'].'&message='.urlencode($this->message));
                    return $ret;
                }
            }
            break;
        }
    }
}
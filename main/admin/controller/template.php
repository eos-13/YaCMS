<?php
class controller_template extends admin_common
{
    private $message;
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
        {
            $this->id = $_REQUEST['id'];
            $this->model->id = $this->id;
        }
        $ret = false;
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }
        if (isset($_REQUEST['message']))
        {
            $this->message=urldecode($_REQUEST['message']);
        }
        $t_obj = $this->model->run();

        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $viewclass="view_".$this->req;
            $view = new $viewclass($this->db,$this->req,false,false);
            $view->message = $this->message;
            if (isset($_REQUEST['id']))
                $view->id = $this->id;
            if ($ret && ($_REQUEST['action'] == "validate" || $_REQUEST['action'] == "add" || $_REQUEST['action'] == "clone_template" || $_REQUEST['action'] == "del" ))
            {
                $view->send_json($ret);
            } elseif ($ret){
                $view->send_html($ret);
            } else {
                $view->message = $this->message;
                $view->run($t_obj);
            }

        }
    }
    public function action($action,$post)
    {
        global $current_user;

        switch ($action)
        {
            case "edit_file_hd":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $content = $this->model->get_template_file_content($post['type'],$post['file']);
                $content = $this->make_form_edit_file(array('content' => $content),$post['type'],$post['file']);
                $this->message = $this->model->message;
                return $content;
            }
            break;
            case "save_file_hd":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $content = $post['content'];
                $file = $post['file'];
                $type = $post['type'];
                switch($type)
                {
                    case "disk_main":
                    {
                        $type="customer/template";
                    }
                    break;
                    case "disk_custom":
                    {
                        $type="customer/template";
                    }
                    break;
                    default:
                    {
                        $type="customer/template";
                    }
                }
                global $conf;
                if (!is_dir($conf->main_document_root."/".$type))
                {
                    mkdir ($conf->main_document_root."/".$type);
                }
                $ret = $this->model->set_file_content($type.'/'.$file, html_entity_decode($content));
                //$this->message = "Enregistrement effectué";
                if ($ret) $this->message = _("Opération effectuée");
                else $this->message = _("Opération echouée");
                return false;
            }
            break;
            case "save_bdd":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $ret = $this->model->save_bdd($post);
                if ($ret) $this->message = _("Opération effectuée");
                else $this->message = _("Opération echouée");
            }
            break;
            case "edit_bdd":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $content_array = $this->model->get_bdd_content();
                $content = $this->make_form_edit_file($content_array,'bdd',false);
                $this->message = $this->model->message;
                return $content;
            }
            break;
            case "add":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $json_id = $this->model->add();
                if ($json_id) $this->message = _("Opération effectuée");
                else $this->message = _("Opération echouée");
                return $json_id;
            };
            break;
            case "clone_template":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $json_id = $this->model->clone_template($post);
                if ($json_id) $this->message = _("Opération effectuée");
                else $this->message = _("Opération echouée");
                return $json_id;
            };
            break;
            case "del":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                if ($post['type'] == 'disk_custom')
                {
                    $this->model->del_file();
                } else {
                    $this->model->del();
                }
                $this->message = $this->model->message;
                $message = urlencode($this->message);
                header('Location:template?message='.$message);
                exit;
            };
            break;
            case "validate":
            {
                $this->log->log(get_class($this). ' Uid: ' .$current_user->get_id(). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->validate($post);
                $this->message = $this->model->message;
                return $res;
            }
            break;
        }
    }
    private function make_form_edit_file($content,$type='bdd',$file=false)
    {
        $html = "";
        global $conf;
        if ($type == 'bdd')
        {
            $html .= "<input name='action' type='hidden' id ='action' value='save_bdd' >";
            $html .= "<input name='id' type='hidden' id ='id' value='".$this->id."' >";
            $html .= "<input name='type' type='hidden' id ='type' value='".$type."' >";
            $html .= "<table class='.table' style='border-collapse: collapse;' CELLPADDING='10'>";
            $html .= "    <tr class='background-td-even'>";
            $html .= "        <th class='form-element-title'>".('Name')."</th>";
            $html .= "        <td class='form-element'><input name='name' type='text' required id ='name' value='".$content['name']."' >";
            $html .= "        </td>";
            $html .= "        <td rowspan='4'>";
            if (is_file($conf->main_document_root."/img/model_img/".md5($content['name']).".png"))
            {
                $html .= "<a  class='fancy fancybox' href='".$conf->main_base_path."/img/model_img/".md5($content['name']).".png'><img height='128' src='".$conf->main_base_path."/img/model_img/".md5($content['name']).".png' /></a>";
            } else {
                $html .= "<a  class='fancy fancybox' href='".$conf->main_base_path."/img/model_img/No_Image.png'><img height='128' src='".$conf->main_base_path."/img/model_img/No_Image.png"."'/></a>";
            }
            $html .= "        </td>";
            $html .= "    </tr>";
            $html .= "    <tr class='background-td-odd'>";
            $html .= "        <th class='form-element-title'>".('Path')."</th>";
            $html .= "        <td class='form-element'><input name='file' type='text' id ='file' value='".$content['path']."' ></td>";
            $html .= "    </tr>";
            $html .= "    <tr class='background-td-even'>";
            $html .= "        <th class='form-element-title'>".('Extra params')."</th>";
            $html .= "        <td class='form-element'>";
            $html .= "              <select id='extra_params' name='extra_params[]' size='5' multiple>";
            load_alternative_class('class/template_extra.class.php');
            $mep = new template_extra($this->db,false);
            {
                foreach( $mep->available_extra_params() as $key => $val)
                {
                    $html .= "            <option ".(is_array($content['extra_params'])  && in_array($key,$content['extra_params'])?"SELECTED":"")." value='".$key."'>".$val['name']."</option>";
                }
            }
            $html .= "              </select>";
            $html .= "        </td>";
            $html .= "    </tr>";
            $html .= "    <tr class='background-td-odd'>";
            $html .= "        <th class='form-element-title'>".('Plugins')."</th>";
            $html .= "        <td class='form-element'>";
            $html .= "            <select id='plugins' name='plugins[]' size='5' multiple>";
            $plugin_path="plugins/template";
            foreach (new DirectoryIterator($plugin_path) as $fileInfo)
            {
                if($fileInfo->isDot()) continue;
                if (!$fileInfo->isDir()) continue;
                $html .= "            <option ".(is_array($content['plugins']) && in_array($fileInfo->getFilename(),$content['plugins'])?"SELECTED":"")." value='".$fileInfo->getFilename()."'>".$fileInfo->getFilename()."</option>";
            }
            $html .= "            </select>";
            $html .= "        </td>";
            $html .= "    </tr>";
            $html .= "</table>";
        } else {
            $html .= "<input name='action' type='hidden' id ='action' value='save_file_hd' >";
            $html .= "<input name='file' type='hidden' id ='file' value='".$file."' >";
        }
        $html .= "<br/><textarea name='content'>". htmlentities( $content['content'])."</textarea>";
        return $html;
    }

}

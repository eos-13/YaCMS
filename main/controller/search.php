<?php
class controller_search extends common
{
    private $model;
    public function run()
    {
        $file = make_path('model', $this->req, 'php');
        if ($file)
        {
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req,false,false,$this->search_mode);
        if (isset($_REQUEST['action']))
        {
            $ret = $this->action($_REQUEST['action'],$_REQUEST);
        }
        $this->model->run();
        // On affiche la page (vue)
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $view = new view_search($this->db,$this->req,false,false,$this->search_mode);
            if ($ret && $ret['json'])
            {
                $view->send_json($ret['json']);
            } else {
                $view->run();
            }
        }
    }
    private function action($action,$post)
    {
        global $current_user;
        switch ($action)
        {
            case "autocomplete":
            {
                $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                $res = $this->model->search("*".$_REQUEST['search_term']."*");
                $res = $this->model->enrich_response($res['result'],$_REQUEST['search_term']);
                return (array('json' => $res));
            }
            break;
        }
    }
}
<?php
class controller_lr extends common
{
    private $model;
    public function run()
    {
        $file = make_path('model', $this->req, 'php');
        if ($file){
            require_once($file);
        }
        $class = "model_".$this->req;
        $this->model = new $class($this->db,$this->req);
        $ret = false;
        if (isset($_REQUEST['s']) && preg_replace('/\s/','',$_REQUEST['s'])."x" != "x")
            $ret = $this->action("search",$_REQUEST);
        // On affiche la page (vue)
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $view = new view_lr($this->db,$this->req,false,false,$this->search_mode);
            $view->lr = $ret;
            $view->run();
        }
    }
    public function set_lr($ret,$post)
    {
        global $conf;
        $limit = $conf->liste_limit;
        $results = $this->model->enrich_response($ret['result'],$post['s']);

        $cur_page = (isset($post['p'])?$post['p']:1);
        $lr = array();
        $lr['cur_page'] = $cur_page;
        $lr['tot_page'] = ceil($ret['total'] / $limit);
        $lr['total']    = $ret['total'];
        $lr['prev_page'] = $cur_page - 1;
        $lr['next_page'] = $cur_page + 1;
        $lr['c'] = $limit;
        $lr['s'] = $post['s'];
        $lr['lr'] = $results;
        return $lr;
    }
    private function action($action,$post)
    {
        global $current_user;
        switch ($action)
        {
            case "search":
                {
                    $this->log->log(get_class($this). ' Uid: ' .($current_user && $current_user->get_id()>0?$current_user->get_id():"Anonymous" ). 'IP: ' .(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']). ' Action '.$action. " Params: ".print_r($_REQUEST,1),LOG_DEBUG);
                    $ret = $this->model->search($post);
                    return $this->set_lr($ret,$post);
                }
                break;
        }
    }
}
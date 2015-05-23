<?php
class controller_annuaire extends common
{
    private $model;
    private $user_list;
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
        $ret = $this->model->run();
        $this->list_user = $ret;
        // On affiche la page (vue)
        $file = make_path('view', $this->req, 'php');
        if ($file)
        {
            include_once($file);
            $view = new view_annuaire($this->db,$this->req,false,false,$this->search_mode);
            $view->list_user = $this->list_user;
            $view->run();
        }
    }
    private function action($action,$post)
    {
        switch ($action)
        {

        }
    }
}
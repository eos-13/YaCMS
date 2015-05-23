<?php

class preview extends common
{
    public function __construct($db,$req,$page=false,$run=true,$search_mode=false)
    {
        global $log;
        $this->log = $log;
        $this->no_use_cache=false;
        $this->db = $db;
        global $current_user;
        $this->access_rights();
        $granted = $this->is_granted();
        if (!$current_user->get_id() > 0 )
        {
            global $conf;
            header('location:'.$conf->main_url_root.'/login?url='.urlencode($conf->main_url_root.'/admin/preview?id='.$_REQUEST['id']));
        } else if (!$granted)
        {
            global $conf;
            include_once('errors/401.php');
            exit;
        }
        $this->get_menu();
        $this->req=$req;
        $this->search_mode = $search_mode;
        $this->access_rights();
        if ($this->search_mode) $this->no_use_cache = true;
        $this->title = $this->req;
        if ($page)
            $this->page = $page;
        else {
            load_alternative_class('class/page.class.php');
            $p = new page($this->db);
            $p->fetch_by_url($req);
            if ($p->get_id()>0)
                $this->page = $p;

        }
        $this->no_use_cache = true;

        global $conf;
        $conf->admin_mode = false;
        $this->set_template_file(make_path('template',$this->req,'html'));

        if ($conf->site_js && $conf->site_js."x" != "x")
        {
            $this->set_js(json_decode($conf->site_js));
        }
        if ($conf->site_css && $conf->site_css."x" != "x")
        {
            $this->set_css(json_decode($conf->site_css));
        }
        $this->set_css($req);
        $this->set_js($req);

        if (function_exists('apc_add')) { $this->set_cache('apc'); }
        elseif (function_exists( 'memcache_set' ) ) { $this->set_cache('memcache'); }

        if ($run)
            $this->run();
    }
}
?>
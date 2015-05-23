<?php
class model_cache extends admin_common
{
    public $message = false;

    public function run()
    {
        $a['css'] = $this->dirSize('cache/css');
        $a['js'] = $this->dirSize('cache/js');
        $a['cache_page'] = $this->get_info_cache();
        return $a;
    }
    private function dirSize($directory)
    {
        $size = 0;
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file)
        {
            if (!preg_match('/^\./',$file->getFilename()))
                $size+=$file->getSize();
        }
        return $size;
    }
    public function clean_cache($post)
    {
        global $conf;
        if (isset($post['clean_css']) && $post['clean_css'] == "on")
        {
            array_map('unlink', glob($conf->main_document_root.'/cache/css/*css'));
        }
        if (isset($post['clean_js']) && $post['clean_js'] == "on")
        {
            array_map('unlink', glob($conf->main_document_root.'/cache/js/*js'));
        }
        $this->clean_cache_image($post['clean_cache_image']);
    }
    private function get_info_cache()
    {
        $requete = "SELECT cache.id,
                           page.title,
                           cache.name
                      FROM cache,
                           page
                     WHERE page.id = cache.page_refid
                  ORDER BY page.title";
        $sql = $this->db->query($requete);
        $a = false;
        while ($res = $this->db->fetch_object($sql))
        {
            $a[] = array('id' => $res->id, 'name' => $res->name, 'page' => $res->title);
        }
        return $a;
    }
    public function clean_cache_image($post)
    {
        global $conf;
        load_alternative_class('class/cache.class.php');
        $c = new cache($this->db);
        foreach($post as $key => $val)
        {
            //clean
            $c->fetch($val);
            $page_refid = $c->get_page_refid();
            $name = $c->get_name();
            $c->del();
            //rebuild
            $func = "build_".$name."_cache";
            load_alternative_class('class/page.class.php');
            $p = new page($this->db);
            $p->fetch($page_refid);
            $ep = $p->get_extra_params();
            $ep = json_decode($ep);
            if (isset($ep->$name))
            {
                $path = $ep->$name;
                $pref = preg_replace('/_path$/','_prefix',$name);
                $prefix=false;
                if (isset($ep->$pref))
                {
                    $prefix = $ep->$pref;
                }
                $res = $c->$func($path, $page_refid,$prefix);
                //met à jour params dans page
                $cache_name=$name.
                $p->set_extra_params($name.'_cache_id', $res);
            }
        }
    }


}
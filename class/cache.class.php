<?php
class cache extends common_object
{
    protected $db;
    public $log;

    private $pName;
    private $pId;
    private $pCached_data;
    private $pPage_refid;

    public $id;
    public $name;
    public $cached_data;
    public $page_refid;

    protected $table = "cache";

    /**
     * @param int $id
     * @return object $obj
     * @desc Return the cache datas
     */

    public function fetch($id)
    {
        $this->set_id($id);
        if ($id > 0)
        {
            $requete = "SELECT *
                          FROM ".$this->table."
                         WHERE id = ".$id;
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $res = $this->db->fetch_object($sql);
                $this->pName = $res->name;
                $this->pCached_data = $res->cached_data;
                $this->pPage_refid = $res->page_refid;
                return $this;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * @param string $name
     * @param int $page_refid
     * @return object $obj
     * @desc Return the cache datas for a page by its name
     */
    public function fetch_by_name($name,$page_refid)
    {
        if ($name)
        {
            $requete = "SELECT *
                          FROM ".$this->table."
                         WHERE name = '".addslashes($name)."'
                           AND page_refid = ".$page_refid;
            $sql = $this->db->query($requete);
            if ($sql && $this->db->num_rows($sql) > 0)
            {
                $res = $this->db->fetch_object($sql);
                $this->set_id($res->id);
                $this->pName = $res->name;
                $this->pCached_data = $res->cached_data;
                $this->pPage_refid = $res->page_refid;
                return $this;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * @param string $name
     * @param int $page_refid
     * @return object $obj
     * @desc Return the cache datas for a page by its name
     */
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->pId;
            $this->name = $this->pName;
            $this->cached_data = $this->pCached_data;
            $this->page_refid = $this->pPage_refid;
            return $this;
        } else {
            return false;
        }
    }
    /**
     * @return int $id
     * @desc Return the id of the cache
     */
    public function get_id()
    {
        if ($this->pId > 0)
        {
            $this->id = $this->pId;
            return $this->pId;
        } else {
            return false;
        }
    }
    /**
     * @return string $name
     * @desc Return the name of the cache
     */

    public function get_name()
    {
        if ($this->get_id() > 0)
        {
            return $this->pName;
        } else {
            return false;
        }
    }
    /**
     * @return int $data
     * @desc Return the data of the cache
     */

    public function get_cached_data()
    {
        if ($this->get_id() > 0)
        {
            return $this->pCached_data;
        } else {
            return false;
        }
    }
    /**
     * @return int $page_refid
     * @desc Return the page of the current cache
     */

    public function get_page_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->pPage_refid;
        } else {
            return false;
        }
    }

    public function set_id($id)
    {
        $this->pId = $id;
        $this->id = $id;
    }
    /**
     * @param string $name
     * @return bool $res
     * @desc Set the cache name
     */
    public function set_name($name)
    {
        if ($this->get_id()>0)
        {
            return $this->update_field('name', $name);
        } else {
            return false;
        }
    }
    /**
     * @param string $cached_data
     * @return bool $res
     * @desc Set the cache datas
     */
    public function set_cached_data($cached_data)
    {
        if ($this->get_id()>0)
        {
            return $this->update_field('cached_data', $cached_data);
        } else {
            return false;
        }
    }
    /**
     * @param string $page_refid
     * @return bool $res
     * @desc Set the page_refid
     */
    public function set_page_refid($page_refid)
    {
        if ($this->get_id()>0)
        {
            return $this->update_field('page_refid', $page_refid);
        } else {
            return false;
        }
    }
    /**
     * @param string $name
     * @return int $res
     * @desc Set a new cache, return its id or false on failure
     */
    public function create($name)
    {
        $requete = "INSERT INTO ".$this->table."
                                (name)
                         VALUES ('".addslashes($name)."')";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            return $this->db->last_insert_id($this->table);
        } else {
            return false;
        }
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
    }
    /**
     * @return bool $res
     * @desc Delete the cache
     */
    public function del()
    {
        if ($this->get_id()>0)
        {
            $requete = "DELETE FROM ".$this->table ."
                              WHERE id = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            return $sql;
        }
    }

    /**
     * @param string $path
     * @param string $page_refid
     * @param string $prefix
     * @return int $id
     * @desc Build the image cache for a page. Return the id of the cache
     */
    public function build_image_path_cache($path,$page_refid,$prefix=false)
    {
        $datas = $this->build_cache($path,"^.+\.(png|jpeg|gif|jpg)$",$prefix);
        if ($this->fetch_by_name('image_path',$page_refid))
        {
            $this->set_cached_data($datas);
            $this->set_page_refid($page_refid);
        } else {
            $id = $this->create('image_path');
            $this->fetch($id);
            $this->set_cached_data($datas);
            $this->set_page_refid($page_refid);
        }
        return ($this->get_id());
    }
    /**
     * @param string $path
     * @param string $page_refid
     * @param string $prefix
     * @return int $id
     * @desc Build the thumbnail image cache for a page. Return the id of the cache
     */
    public function build_thb_path_cache($path,$page_refid,$prefix=false)
    {
        $datas = $this->build_cache($path,"^.+\.(png|jpeg|gif|jpg)$",$prefix);
        if ($this->fetch_by_name('thb_path',$page_refid))
        {
            $this->set_cached_data($datas);
            $this->set_page_refid($page_refid);
        } else {
            $id = $this->create('thb_path');
            $this->fetch($id);
            $this->set_cached_data($datas);
            $this->set_page_refid($page_refid);
        }
        return ($this->get_id());
    }
    /**
     * @param string $path
     * @param string $page_refid
     * @param string $prefix
     * @return int $id
     * @desc Build the video cache for a page. Return the id of the cache
     */
    public function build_video_path_cache($path,$page_refid,$prefix=false)
    {
        $datas = $this->build_cache($path,"^.+\.(mp4|ogg|flv)$",$prefix);
        if ($this->fetch_by_name('video_path',$page_refid))
        {
            $this->set_cached_data($datas);
            $this->set_page_refid($page_refid);
        } else {
            $id = $this->create('video_path',$page_refid);
            $this->fetch($id);
            $this->set_cached_data($datas);
            $this->set_page_refid($page_refid);
        }
        return ($this->get_id());
    }
    /**
     * @param string $path
     * @param string $page_refid
     * @param string $prefix
     * @return int $id
     * @desc Build the file cache for a page. Return the id of the cache
     */
    public function build_file_path_cache($path,$page_refid,$prefix=false)
    {
        $datas = $this->build_cache($path,"^[^\.].+$",$prefix);
        if ($this->fetch_by_name('file_path',$page_refid))
        {
            $this->set_cached_data($datas);
            $this->set_page_refid($page_refid);
        } else {
            $id = $this->create('file_path',$page_refid);
            $this->fetch($id);
            $this->set_cached_data($datas);
            $this->set_page_refid($page_refid);
        }
        return ($this->get_id());
    }
    /**
     * @param string $path
     * @param string $regex
     * @param string $prefix
     * @return string $json_encode
     * @desc Build a cache
     */
    private function build_cache($path,$regex,$prefix=false)
    {
        global $conf;

        $Directory = new RecursiveDirectoryIterator($conf->main_document_root."/files/".$path);
        $Iterator = new RecursiveIteratorIterator($Directory);
        $files = array();
        foreach ($Iterator as $info)
        {
            if (preg_match('/^\./', $info->getFilename()))continue;
            if (preg_match('/\/\.tmb\//',$info->getPathName ())) continue;
            if (preg_match('/'.$regex.'/i',$info->getFilename()))
            {
                if ($prefix) {
                    if (!preg_match('/^'.$prefix.'/',$info->getFilename())) continue;
                }
                $f = $info->getPathname();
                $l = strlen($conf->main_document_root."/files/");
                $f = substr($f, $l);
                load_alternative_class('class/data_file.class.php');
                $df = new data_file($this->db);
                $a = $df->fetch_by_file_path(preg_replace('/^\//',"",$f));
                $array = array( 'path' => $f,'name' =>$info->getFilename());
                foreach($a as $key=>$val)
                {
                    $array[$val->data_name]=$val->data_value;
                }
                $files[] = $array;
            }
        }
        return json_encode($files);
    }
}
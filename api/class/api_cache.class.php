<?php
load_alternative_class('class/common_soap_object.class.php');
class api_cache extends common_soap_object
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
     * @return array $datas
     * @desc List all entry id
     */
    public function list_all()
    {
        $requete = "SELECT *
                      FROM " . $this->table;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]=$res->id;
        }
        return $a;
    }


    /**
     * @param int $id
     * @return array $datas
     * @desc Return the cache datas
     */
    public function get_all($id)
    {
        $this->load($id);
        if ($this->obj->get_id() > 0)
        {
            $a['name'] = $this->obj->get_name();
            $a['cached_data'] = $this->obj->get_cached_data();
            $a['page_refid'] = $this->obj->get_page_refid();
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @param int $id
     * @return int $id
     * @desc Return the cache id
     */
    public function get_id($id)
    {
        $this->load($id);
        return $this->obj->get_id();
    }
    /**
     * @param int $id
     * @return string $name
     * @desc Return the cache name
     */
    public function get_name($id)
    {
        $this->load($id);
        return $this->obj->get_name();
    }
    /**
     * @param int $id
     * @return string $datas
     * @desc Return the cache datas
     */
    public function get_cached_data($id)
    {
        $this->load($id);
        return $this->obj->get_cached_data();
    }
    /**
     * @param int $id
     * @return int $page_refid
     * @desc Return the cache page refid
     */
    public function get_page_refid($id)
    {
        $this->load($id);
        return $this->obj->get_page_refid();
    }
    /**
     * @param int $id
     * @return array $datas
     * @desc Set the cache id
     */
    public function set_id($id)
    {
        return $this->obj->set_id($id);
    }
    /**
     * @param int $id
     * @param string $name
     * @return bool $res
     * @desc Set the cache name
     */
    public function set_name($id,$name)
    {
        $this->load($id);
        return $this->obj->set_name($name);
    }
    /**
     * @param int $id
     * @param string $cached_data
     * @return bool $res
     * @desc Set the cache datas
     */
    public function set_cached_data($id,$cached_data)
    {
        $this->load($id);
        return $this->obj->set_cached_data($cached_data);
    }
    /**
     * @param int $id
     * @param int $page_refid
     * @return bool $res
     * @desc Set the cache page id
     */
    public function set_page_refid($id,$page_refid)
    {
        $this->load($id);
        return $this->obj->set_page_refid($page_refid);
    }

}
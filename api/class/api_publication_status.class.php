<?php
load_alternative_class('class/common_soap_object.class.php');
class api_publication_status extends common_soap_object
{
    protected $table = 'publication_status';

    private $pId;
    private $pName;
    private $pOrder;
    private $pIs_public;
    private $pIn_search_engine;

    public $id;
    public $name;
    public $order;
    public $is_public;
    public $in_search_engine;

    public function list_all()
    {
        $requete = "SELECT *
                      FROM ".$this->table;
        $a = array();
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id] = array(
                    'id' => $res->id,
                    'name' => $res->name
            );
        }
        return $a;
    }

    /**
     * @desc Get all datas for a publication status
     * @param int $id
     * @return array $datas
     */
    public function get_all($id)
    {
        $this->load($id);
        $a = array();
        if ($this->obj->get_id() > 0 )
        {
            $a['id'] = $this->obj->get_id();
            $a['name'] = $this->obj->get_name();
            $a['order'] = $this->obj->get_order();
            $a['is_public'] = $this->obj->get_is_public();
            $a['in_search_engine'] = $this->obj->get_in_search_engine();
        }
        return $a;
    }
    /**
     * @desc Get name
     * @param int $id
     * @return string $name
     */
    public function get_name($id)
    {
        $this->load($id);
        return $this->obj->get_name();
    }
    /**
     * @desc Get order
     * @param int $id
     * @return string $order
     */
    public function get_order($id)
    {
        $this->load($id);
        return $this->obj->get_order();
    }
    /**
     * @desc Get id
     * @param int $id
     * @return string $id
     */
    public function get_id($id)
    {
        $this->load($id);
        return $this->obj->get_id();
    }
    /**
     * @desc Get in search_engine
     * @param int $id
     * @return string $in_search_engine
     */
    public function get_in_search_engine($id)
    {
        $this->load($id);
        return $this->obj->get_in_search_engine();
    }
    /**
     * @desc Set name
     * @param int $id
     * @param string $name
     * @return bool $res
     */
    public function set_name($id,$name)
    {
        $this->load($id);
        return $this->obj->set_name($name);
    }
    /**
     * @desc Set order
     * @param int $id
     * @param string $order
     * @return bool $res
     */
    public function set_order($id,$order)
    {
        $this->load($id);
        return $this->obj->set_order($order);
    }
    /**
     * @desc Set in search_engine
     * @param int $id
     * @param string $in_search_engine
     * @return bool $res
     */
    public function set_in_search_engine($id,$in_search_engine)
    {
        $this->load($id);
        return $this->obj->set_in_search_engine($in_search_engine);
    }

}
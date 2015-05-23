<?php
class publication_status extends common_object
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

    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res= $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->id = $res->id;
            $this->pName = $res->name;
            $this->pOrder = $res->order;
            $this->pIs_public = $res->is_public;
            $this->pIn_search_engine = $res->in_search_engine;
            return $this;
        } else {
            return false;
        }
    }
    public function get_all()
    {
        if ($this->get_id() > 0 )
        {
            $this->name = $this->pName;
            $this->order = $this->pOrder;
            $this->is_public = $this->pIs_public;
            $this->in_search_engine = $this->pIn_search_engine;
        }
    }
    public function get_name()
    {
        if ($this->get_id() > 0)
        {
            return $this->pName;
        } else {
            return false;
        }
    }
    public function get_is_public()
    {
        if ($this->get_id() > 0)
        {
            return $this->pIs_public;
        } else {
            return false;
        }
    }
    public function get_order()
    {
        if ($this->get_id() > 0)
        {
            return $this->pOrder;
        } else {
            return false;
        }
    }
    public function get_id()
    {
        $this->id = $this->pId;
        return $this->pId;
    }
    public function get_in_search_engine()
    {
        if ($this->get_id() > 0)
        {
            return $this->pIn_search_engine;
        } else {
            return false;
        }
    }
    public function set_name($name)
    {
        if ($this->get_id() > 0)
        {
            $ret = $this->update_field('name',$name);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_order($order)
    {
        if ($this->get_id() > 0)
        {
            $ret = $this->update_field('order',$order);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_is_public($is_public)
    {
        if ($this->get_id() > 0)
        {
            if (empty($is_public) || is_null($is_public) || !$is_public)
            {
                $is_public = "0";
            }
            $ret = $this->update_field('is_public',$is_public);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_in_search_engine($in_search_engine)
    {
        if ($this->get_id() > 0)
        {
            if (empty($in_search_engine) || is_null($in_search_engine) || !$in_search_engine)
            {
                $in_search_engine = "0";
            }
            $ret = $this->update_field('in_search_engine',$in_search_engine);
            return $ret;
        } else {
            return false;
        }
    }
}
<?php
class data_file extends common_object
{
    protected $db;
    private $pId;
    private $pFile_path;
    private $pData_name;
    private $pData_value;

    public $id;
    public $file_path;
    public $data_name;
    public $data_value;

    protected $table = "data_file";

    public function __construct($db)
    {
        global $log;
        $this->log = $log;
        $this->db = $db;
    }
    public function fetch($id)
    {
        $this->pId = $id;
        $this->id = $id;
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id =".$id;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $res = $this->db->fetch_object($sql);
            $this->pData_name = $res->data_name;
            $this->pData_value = $res->data_value;
            $this->pFile_path = $res->file_path;
            return $this;
        } else {
            return false;
        }
    }
    public function fetch_by_file_path($file_path)
    {
        $requete = "SELECT id
                      FROM ".$this->table."
                     WHERE file_path ='".addslashes($file_path)."'";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $r = array();
            while ($res = $this->db->fetch_object($sql))
            {
                $dm = new data_file($this->db);
                $dm = $dm->fetch($res->id);
                $dm->get_all();
                $r[]=$dm;
            }
            return $r;
        } else {
            return false;
        }
    }

    public function get_all()
    {
        if ($this->get_id()>0)
        {
            $this->id = $this->pId;
            $this->data_name = $this->pData_name;
            $this->data_value = $this->pData_value;
            $this->file_path = $this->pFile_path;
        }
    }
    public function del()
    {
        if ($this->get_id() > 0)
        {
            $requete = "DELETE FROM ".$this->table."
                              WHERE id= ".$this->get_id();
            $sql = $this->db->query($requete);
            return ($sql);
        }
    }
    public function add($path,$key,$val)
    {
        $requete = "SELECT *
                      FROM ". $this->table ."
                     WHERE file_path='".addslashes($path)."'
                       AND data_name = '".addslashes($key)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0) return -1;
        $requete = "INSERT INTO ".$this->table."
                                (data_value, data_name, file_path)
                         VALUES ('".addslashes($val)."','".addslashes($key)."','".addslashes($path)."')";
        $sql = $this->db->query($requete);
        if ($sql)
            return $this->db->last_insert_id($this->table);
        else
            return false;
    }
    public function get_id()
    {
        if ($this->pId > 0) return $this->pId;
        else return false;
    }
    public function get_file_path()
    {
        if ($this->get_id() > 0)
            return $this->pFile_path;
        else
            return false;
    }
    public function get_data_name()
    {
        if ($this->get_id() > 0)
            return $this->pData_name;
        else
            return false;
    }
    public function get_data_value()
    {
        if ($this->get_id() > 0)
            return $this->pData_value;
        else
            return false;
    }
    public function set_file_path($file_path)
    {
        if ($this->get_id() > 0)
        {
            $ret = $this->update_field('file_path', $file_path);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_data_name($data_name)
    {
        if ($this->get_id() > 0)
        {
            $ret = $this->update_field('data_name', $data_name);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_data_value($data_value)
    {
        if ($this->get_id() > 0)
        {
            $ret = $this->update_field('data_value', $data_value);
            return $ret;
        } else {
            return false;
        }
    }
}
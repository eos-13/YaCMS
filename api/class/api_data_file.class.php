<?php
load_alternative_class('class/common_soap_object.class.php');
class api_data_file extends common_soap_object
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

    /**
     * @desc Get all datas
     * @return array $datas[]
     * @param int $id
     */
    public function get_all($id)
    {
        $this->load($id);
        if ($this->obj->get_id()>0)
        {
            $a['id'] = $this->obj->get_id();
            $a['file_path'] = $this->obj->get_file_path();
            $a['data_name'] = $this->obj->get_data_name();
            $a['data_value'] = $this->obj->get_data_value();
            return $a;
        }
    }
    /**
     * @return array $datas
     * @desc List all entry id
     */
    public function list_all()
    {
        global $conf;
        $requete = "SELECT id,
                           file_path
                      FROM " . $this->table;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]['id']=$res->id;
            $a[$res->id]['file_path']=$res->file_path;
        }
        return $a;
    }
    /**
     * @param string $data_name
     * @return array $datas
     * @desc List all entry id for a data name
     */
    public function list_all_by_data_name($data_name)
    {
        global $conf;
        $requete = "SELECT id,
                           file_path
                      FROM " . $this->table."
                     WHERE data_name = '".addslashes($data_name)."' ";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]['id']=$res->id;
            $a[$res->id]['file_path']=$res->file_path;
        }
        return $a;
    }
    /**
     * @desc Get id
     * @param int $id
     * @return int $id
     */
    public function get_id($id)
    {
        $this->load($id);
        return $this->obj->get_id();
    }
    /**
     * @desc Get file path
     * @param int $id
     * @return string $file_path
     */
    public function get_file_path($id)
    {
        $this->load($id);
        return $this->obj->get_file_path();
    }
    /**
     * @desc Get data name
     * @param int $id
     * @return string $data_name
     */
    public function get_data_name($id)
    {
        $this->load($id);
        return $this->obj->get_data_name();
    }
    /**
     * @desc Get data value
     * @param int $id
     * @return string $data_value
     */
    public function get_data_value($id)
    {
        $this->load($id);
        return $this->obj->get_data_value();
    }
    /**
     * @desc Set file path
     * @param int $id
     * @param string $file_path
     * @return bool $res
     */
    public function set_file_path($id,$file_path)
    {
        $this->load($id);
        return $this->obj->set_file_path($file_path);
    }
    /**
     * @desc Set data name
     * @param int $id
     * @param string $data_name
     * @return bool $res
     */
    public function set_data_name($id,$data_name)
    {
        $this->load($id);
        return $this->obj->set_data_name($data_name);
    }
    /**
     * @desc Set data value
     * @param int $id
     * @param string $data_value
     * @return bool $res
     */
    public function set_data_value($id,$data_value)
    {
        $this->load($id);
        return $this->obj->set_data_value($data_value);
    }
    /**
     * @desc Add datas for a file
     * @param string $path
     * @param string $key
     * @param string $value
     * @return bool $res
     */
    public function add($path,$key,$val)
    {
        return $this->obj->add($path,$key,$val);
    }
    /**
     * @desc Del data for a file
     * @param int $id
     * @return bool $res
     */
    public function del($id)
    {
        $this->load($id);
        return $this->obj->del();
    }
}
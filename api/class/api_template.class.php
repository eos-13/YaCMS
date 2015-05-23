<?php
load_alternative_class('class/common_soap_object.class.php');
class api_template extends common_soap_object
{
    protected $db;
    private $pId;
    private $pName;
    private $pPath;
    private $pContent;
    private $pExtra_params;
    private $pPlugins;

    public $id;
    public $name;
    public $path;
    public $content;
    public $type;
    public $extra_params;
    public $plugins;


    protected $table = "model";

    /**
     * @param int $id
     * @desc Delete a template
     * @return bool $res
     */
    public function del($id)
    {
        $this->load($id);
        return $this->obj->del();
    }
    /**
     * @desc Create a new template
     * @return int $id
     */
    public function create($id)
    {
        return $this->obj->create();
    }

    /**
     * @desc List all template
     * @return array $datas[]
     */
    public function list_all()
    {
        $requete = "SELECT *
                      FROM ".$this->table;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id] = array(
                'id' => $res->id,
                'name' => $res->name,
                'path' => $res->path
            );
        }
        return $a;
    }
    /**
     * @desc Get all data about a template
     * @param int $id
     * @return array $datas[]
     */
    public function get_all($id)
    {
        $this->load($id);
        if ($this->obj->get_id() > 0)
        {
            $a = array();
            $a['id'] = $this->obj->get_id();
            $a['name'] = $this->obj->get_name();
            $a['path'] = $this->obj->get_path();
            $a['content'] = $this->obj->get_content();
            $a['extra_params'] = $this->obj->get_extra_params();
            $a['plugins'] = $this->obj->get_plugins();
            return $a;
        } else {
            return false;
        }
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
     * @desc Get path
     * @param int $id
     * @return string $path
     */
    public function get_path($id)
    {
        $this->load($id);
        return $this->obj->get_path();
    }
    /**
     * @desc Get content
     * @param int $id
     * @return string $content
     */
    public function get_content($id)
    {
        $this->load($id);
        return $this->obj->get_content();
    }
    /**
     * @desc Get extra params
     * @param int $id
     * @return string $extra_params
     */
    public function get_extra_params($id)
    {
        $this->load($id);
        return $this->obj->get_extra_params();
    }
    /**
     * @desc Get plugins
     * @param int $id
     * @return string $plugins
     */
    public function get_plugins($id)
    {
        $this->load($id);
        return $this->obj->get_plugins();
    }
    /**
     * @desc Set content
     * @param int $id
     * @param string $content
     * @return bool $res
     */
    public function set_content($id,$content)
    {
        $this->load($id);
        return $this->obj->set_content($content);
    }
    /**
     * @desc Set path
     * @param int $id
     * @param string $path
     * @return bool $res
     */
    public function set_path($id,$path)
    {
        $this->load($id);
        return $this->obj->set_path($path);
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
     * @desc Set extra params
     * @param int $id
     * @param string $extra_params
     * @return bool $res
     */
    public function set_extra_params($id,$extra_params)
    {
        $this->load($id);
        return $this->obj->set_extra_params($extra_params);
    }
    /**
     * @desc Set plugins
     * @param int $id
     * @param string $plugins
     * @return bool $res
     */
    public function set_plugins($id,$plugins)
    {
        $this->load($id);
        return $this->obj->set_plugins($plugins);
    }

}
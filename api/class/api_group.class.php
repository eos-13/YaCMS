<?php
load_alternative_class('class/common_soap_object.class.php');
class api_group extends common_soap_object
{
    protected $db;
    private $pId;
    private $pName;
    private $pEmail;
    private $pDescription;
    private $pAvatar_path;
    private $pActive;
    private $pMd5;


    public $id;
    public $name;
    public $email;
    public $description;
    public $avatar_path;
    public $active;
    public $md5;

    protected $table = "group";

    /**
     * @desc List all user group
     * @return array $datas[]
     */
    public function list_all()
    {
        $requete = "SELECT *
                      FROM `".$this->table."`";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]=array(
                'id' => $res->id,
                'name' => $res->name,
                'md5' => $res->md5
            );
        }
        return $a;
    }

    /**
     * @desc Get all data of a user group
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
            $a['md5'] = $this->obj->get_md5();
            $a['name'] = $this->obj->get_name();
            $a['email'] = $this->obj->get_email();
            $a['description'] = $this->obj->get_description();
            $a['avatar_path'] = $this->obj->get_avatar_path();
            $a['active'] = $this->obj->get_active();

            return $a;
        } else {
            return false;
        }
    }
    /**
     * @desc Create a new user group
     * @return int $id
     */
    public function create()
    {
        return $this->obj->create();
    }
    /**
     * @desc Delete a user group
     * @return bool $res
     */
    public function del()
    {
        return $this->obj->del();
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
     * @desc Get email
     * @param int $id
     * @return string $email
     */
    public function get_email($id)
    {
        $this->load($id);
        return $this->obj->get_email();
    }
    /**
     * @desc Get description
     * @param int $id
     * @return string $description
     */
    public function get_description($id)
    {
        $this->load($id);
        return $this->obj->get_description();
    }
    /**
     * @desc Get avatar path
     * @param int $id
     * @return string $avatar_path
     */
    public function get_avatar_path($id)
    {
        $this->load($id);
        return $this->obj->get_avatar_path();
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
     * @desc Get active
     * @param int $id
     * @return bool $active
     */
    public function get_active($id)
    {
        $this->load($id);
        return $this->obj->get_active();
    }
    /**
     * @desc Get md5
     * @param int $id
     * @return string $md5
     */
    public function get_md5($id)
    {
        $this->load($id);
        return $this->obj->get_md5();
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
     * @desc Set email
     * @param int $id
     * @param string $email
     * @return bool $res
     */
    public function set_email($id,$email)
    {
        $this->load($id);
        return $this->obj->set_email($email);
    }
    /**
     * @desc Set description
     * @param int $id
     * @param string $description
     * @return bool $res
     */
    public function set_description($id,$description)
    {
        $this->load($id);
        return $this->obj->set_description($description);
    }
    /**
     * @desc Set avatar path
     * @param int $id
     * @param string $avatar_path
     * @return bool $res
     */
    public function set_avatar_path($id,$avatar_path)
    {
        $this->load($id);
        return $this->obj->set_avatar_path($avatar_path);
    }
    /**
     * @desc Set active
     * @param int $id
     * @param string $active
     * @return bool $res
     */
    public function set_active($id,$active)
    {
        $this->load($id);
        return $this->obj->set_active($active);
    }
}
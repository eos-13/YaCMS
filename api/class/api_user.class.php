<?php
load_alternative_class('class/common_soap_object.class.php');
class api_user extends common_soap_object
{
    protected $db;
    private $pId;
    private $pName;
    private $pFirstname;
    private $pLogin;
    private $pEmail;
    private $pDescription;
    private $pAvatar_path;
    private $pPass;
    private $pActive;
    private $pMd5;
    private $pLast_login;
    private $pRemember_me;
    private $pLast_failed_login;
    private $pFailed_logins;
    private $pRights;
    private $pPublic_profile;
    private $pTmp_token;
    private $pDate_tmp_token;
    private $pIs_locked;

    public $id;
    public $name;
    public $firstname;
    public $login;
    public $email;
    public $description;
    public $avatar_path;
    public $pass;
    public $active;
    public $md5;
    public $last_login;
    public $remember_me;
    public $last_failed_login;
    public $failed_logins;
    public $public_profile;
    public $tmp_token;
    public $date_tmp_token;
    public $is_locked;


    protected $table = "user";

    /**
     * @desc List all user
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
            $a[$res->id]=array(
                'id' => $res->id,
                'name' => $res->name,
                'firstname' => $res->firstname,
                'email' => $res->email,
                'login' => $res->login
            );
        }
        return $a;
    }

    /**
     * @param int $md5
     * @return string $datas[]
     * @desc load user datas by its md5
     */
    public function fetch_by_md5($md5)
    {
        $a = array();
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE `md5` = '".addslashes($md5)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $a['id'] = $res->id;
            $a['pMd5'] = $res->md5;
            $a['pName'] = $res->name;
            $a['pFirstname'] = $res->firstname;
            $a['pLogin'] = $res->login;
            $a['pPass'] = $res->pass;
            $a['pEmail'] = $res->email;
            $a['pDescription'] = $res->description;
            $a['pAvatar_path'] = $res->avatar_path;
            $a['pActive'] = $res->active;
            $a['pLast_login'] = $res->last_login;
            $a['pRemember_me'] = $res->remember_me;
            $a['pLast_failed_login'] = $res->last_failed_login;
            $a['pFailed_logins'] = $res->failed_logins;
            $a['pPublic_profile'] = $res->public_profile;
            $a['pTmp_token'] = $res->tmp_token;
            $a['pDate_tmp_token'] = $res->date_tmp_token;
            $a['pIs_locked'] = $res->is_locked;
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @param string $username
     * @return string $datas[]
     * @desc load user datas by its username
     */
    public function fetch_by_username($username)
    {
        $a = array();
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE `login` = '".addslashes($username)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $a['id'] = $res->id;
            $a['pMd5'] = $res->md5;
            $a['pName'] = $res->name;
            $a['pFirstname'] = $res->firstname;
            $a['pLogin'] = $res->login;
            $a['pPass'] = $res->pass;
            $a['pEmail'] = $res->email;
            $a['pDescription'] = $res->description;
            $a['pAvatar_path'] = $res->avatar_path;
            $a['pActive'] = $res->active;
            $a['pLast_login'] = $res->last_login;
            $a['pRemember_me'] = $res->remember_me;
            $a['pLast_failed_login'] = $res->last_failed_login;
            $a['pFailed_logins'] = $res->failed_logins;
            $a['pPublic_profile'] = $res->public_profile;
            $a['pTmp_token'] = $res->tmp_token;
            $a['pDate_tmp_token'] = $res->date_tmp_token;
            $a['pIs_locked'] = $res->is_locked;
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @param string $md5
     * @param string $token
     * @return string $datas[]
     * @desc load user datas by its token
     */
    public function fetch_user_with_token($md5, $token)
    {
        $a = array();
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE `md5` = '".addslashes($md5)."'
                       AND `remember_me` = '".addslashes($token)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $a['id'] = $res->id;
            $a['pMd5'] = $res->md5;
            $a['pName'] = $res->name;
            $a['pFirstname'] = $res->firstname;
            $a['pLogin'] = $res->login;
            $a['pPass'] = $res->pass;
            $a['pEmail'] = $res->email;
            $a['pDescription'] = $res->description;
            $a['pAvatar_path'] = $res->avatar_path;
            $a['pActive'] = $res->active;
            $a['pLast_login'] = $res->last_login;
            $a['pRemember_me'] = $res->remember_me;
            $a['pLast_failed_login'] = $res->last_failed_login;
            $a['pFailed_logins'] = $res->failed_logins;
            $a['pPublic_profile'] = $res->public_profile;
            $a['pTmp_token'] = $res->tmp_token;
            $a['pDate_tmp_token'] = $res->date_tmp_token;
            $a['pIs_locked'] = $res->is_locked;
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @desc load user rights
     * @return array $rights[]
     * @param int $id
     */
    public function fetch_right($id)
    {
        $r = new rights($this->db);
        $r->fetch($id);
        $this->pRights=$r;
        return $this->pRights;
    }
    /**
     * @param int $id
     * @param string $right
     * @return int $right_level
     * @desc Check if user has particular right (0, no, 1 yes, 2 inherited)
     */
    public function has_right($id,$right)
    {
        $this->fetch_right($id);
        return $this->pRights->$right;
    }
    /**
     * @param int $id
     * @return string $datas[]
     * @desc Check if user have some admin rights
     */
    public function has_some_admin_right($id)
    {
        $this->fetch_right($id);
        return ($this->pRights->has_some_admin_rights());
    }
    /**
     * @param int $id
     * @return string $datas[]
     * @desc Make all private data public
     */
    public function get_all($id)
    {
        $a = array();
        $this->load($id);
        if ($this->obj->get_id() > 0)
        {
            global $conf;
            $a['id'] = $this->obj->get_id();
            $a['pMd5'] = $this->obj->get_md5();
            $a['pName'] = $this->obj->get_name();
            $a['pFirstname'] = $this->obj->get_firstname();
            $a['pLogin'] = $this->obj->get_login();
            $a['pPass'] = $this->obj->get_pass();
            $a['pEmail'] = $this->obj->get_email();
            $a['pDescription'] = $this->obj->get_description();
            $a['pAvatar_path'] = $this->obj->get_avatar_path();
            $a['pActive'] = $this->obj->get_active();
            $a['pLast_login'] = $this->obj->get_last_login();
            $a['pRemember_me'] = $this->obj->get_remember_me();
            $a['pLast_failed_login'] = $this->obj->get_last_failed_login();
            $a['pFailed_logins'] = $this->obj->get_failed_logins();
            $a['pPublic_profile'] = $this->obj->get_public_profile();
            $a['pTmp_token'] = $this->obj->get_tmp_token();
            $a['pDate_tmp_token'] = $this->obj->get_date_tmp_token();
            $a['pIs_locked'] = $this->obj->get_is_locked();
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @return int $user_id
     * @desc Create an user
     */
    public function create()
    {
        return $this->obj->create();
    }
    /**
     * @return bool $result
     * @desc del user
     * @param int $id
     */
    public function unsecure_del($id)
    {
        $this->load($id);
        return $this->obj->unsecure_del();
    }
    /**
     * @return bool $result
     * @desc Delete user securely
     * @param int $id
     */
    public function del($id)
    {
        $this->load($id);
        return $this->obj->del();
    }

    /**
     * @param string $email
     * @param string $token
     * @param string $pass
     * @return bool result
     * @desc Valid if token is correct and update pass
     */
    public function valid_token($email,$token,$pass)
    {
        return $this->obj->valid_token($email,$token,$pass);
    }
    /**
     * @desc Get rights
     * @param int $id
     * @return string $rights
     */
    public function get_rights($id)
    {
        $this->load($id);
        return $this->obj->get_rights();
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
     * @desc Get firstname
     * @param int $id
     * @return string $firstname
     */
    public function get_firstname($id)
    {
        $this->load($id);
        return $this->obj->get_firstname();
    }
    /**
     * @desc Get login
     * @param int $id
     * @return string $login
     */
    public function get_login($id)
    {
        $this->load($id);
        return $this->obj->get_login();
    }
    /**
     * @desc Get pass
     * @param int $id
     * @return string $pass
     */
    public function get_pass($id)
    {
        $this->load($id);
        return $this->obj->get_pass();
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
        return $this->obj->get_avatar_path($with_default=false);
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
     * @return int $active
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
     * @desc Get last login
     * @param int $id
     * @return datetime $last_login
     */
    public function get_last_login($id)
    {
        $this->load($id);
        return $this->obj->get_last_login();
    }
    /**
     * @desc Get remember me
     * @param int $id
     * @return string $remember_me
     */
    public function get_remember_me($id)
    {
        $this->load($id);
        return $this->obj->get_remember_me();
    }
    /**
     * @desc Get last failed_login
     * @param int $id
     * @return datetime $last_failed_login
     */
    public function get_last_failed_login($id)
    {
        $this->load($id);
        return $this->obj->get_last_failed_login();
    }
    /**
     * @desc Get failed logins
     * @param int $id
     * @return int $failed_logins
     */
    public function get_failed_logins($id)
    {
        $this->load($id);
        return $this->obj->get_failed_logins();
    }
    /**
     * @desc Get tmp token
     * @param int $id
     * @return string $tmp_token
     */
    public function get_tmp_token($id)
    {
        $this->load($id);
        return $this->obj->get_tmp_token();
    }
    /**
     * @desc Get date tmp_token
     * @param int $id
     * @return datetime $date_tmp_token
     */
    public function get_date_tmp_token($id)
    {
        $this->load($id);
        return $this->obj->get_date_tmp_token();
    }
    /**
     * @desc Get is locked
     * @param int $id
     * @return int $is_locked
     */
    public function get_is_locked($id)
    {
        $this->load($id);
        return $this->obj->get_is_locked();
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
     * @desc Set firstname
     * @param int $id
     * @param string $firstname
     * @return bool $res
     */
    public function set_firstname($id,$firstname)
    {
        $this->load($id);
        return $this->obj->set_firstname($firstname);
    }
    /**
     * @desc Set login
     * @param int $id
     * @param string $login
     * @return bool $res
     */
    public function set_login($id,$login)
    {
        $this->load($id);
        return $this->obj->set_login($login);
    }
    /**
     * @desc Set pass
     * @param int $id
     * @param string $pass
     * @return bool $res
     */
    public function set_pass($id,$pass)
    {
        $this->load($id);
        return $this->obj->set_pass($pass);
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
     * @param int $active
     * @return bool $res
     */
    public function set_active($id,$active)
    {
        $this->load($id);
        return $this->obj->set_active($active);
    }
    /**
     * @desc Set last login
     * @param int $id
     * @param datetime $last_login=false
     * @return bool $res
     */
    public function set_last_login($id,$last_login=false)
    {
        $this->load($id);
        return $this->obj->set_last_login($last_login=false);
    }
    /**
     * @desc Set failed logins
     * @param int $id
     * @param int $failed_logins
     * @return bool $res
     */
    public function set_failed_logins($id,$failed_logins)
    {
        $this->load($id);
        return $this->obj->set_failed_logins($failed_logins);
    }
    /**
     * @desc Set remember me
     * @param int $id
     * @param string $random
     * @return bool $res
     */
    public function set_remember_me($id,$random)
    {
        $this->load($id);
        return $this->obj->set_remember_me($random);
    }
    /**
     * @desc Set last failed_login to now
     * @param int $id
     * @return bool $res
     */
    public function set_last_failed_login($id)
    {
        $this->load($id);
        return $this->obj->set_last_failed_login();
    }
    /**
     * @desc Set tmp token
     * @param int $id
     * @param string $tmp_token
     * @return bool $res
     */
    public function set_tmp_token($id,$tmp_token)
    {
        $this->load($id);
        return $this->obj->set_tmp_token($tmp_token);
    }
    /**
     * @desc Set is locked
     * @param int $id
     * @param int $is_locked
     * @return bool $res
     */
    public function set_is_locked($id,$is_locked)
    {
        $this->load($id);
        return $this->obj->set_is_locked($is_locked);
    }
    /**
     * @desc Set date tmp_token
     * @param int $id
     * @param datetime $date_tmp_token
     * @return bool $res
     */
    public function set_date_tmp_token($id,$date_tmp_token)
    {
        $this->load($id);
        return $this->obj->set_date_tmp_token($date_tmp_token);
    }
    /**
     * @desc Set temporary token
     * @param int $id
     * @param string $email
     * @return bool $res
     */
    public function set_temporary_token($id,$email)
    {
        $this->load($id);
        return $this->obj->set_temporary_token($email);
    }
}
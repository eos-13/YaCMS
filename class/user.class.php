<?php
class user extends common_object
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
     * @param int $id
     * @return objects $datas[]
     * @desc load user datas
     */
    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->id = $res->id;
            $this->pMd5 = $res->md5;
            $this->pName = $res->name;
            $this->pFirstname = $res->firstname;
            $this->pLogin = $res->login;
            $this->pPass = $res->pass;
            $this->pEmail = $res->email;
            $this->pDescription = $res->description;
            $this->pAvatar_path = $res->avatar_path;
            $this->pActive = $res->active;
            $this->pLast_login = $res->last_login;
            $this->pRemember_me = $res->remember_me;
            $this->pLast_failed_login = $res->last_failed_login;
            $this->pFailed_logins = $res->failed_logins;
            $this->pPublic_profile = $res->public_profile;
            $this->pTmp_token = $res->tmp_token;
            $this->pDate_tmp_token = $res->date_tmp_token;
            $this->pIs_locked = $res->is_locked;
            return $this;
        } else {
            return false;
        }
    }
    /**
     * @param int $md5
     * @return string $datas[]
     * @desc load user datas by its md5
     */
    public function fetch_by_md5($md5)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE `md5` = '".addslashes($md5)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->id = $res->id;
            $this->pMd5 = $res->md5;
            $this->pName = $res->name;
            $this->pFirstname = $res->firstname;
            $this->pLogin = $res->login;
            $this->pPass = $res->pass;
            $this->pEmail = $res->email;
            $this->pDescription = $res->description;
            $this->pAvatar_path = $res->avatar_path;
            $this->pActive = $res->active;
            $this->pLast_login = $res->last_login;
            $this->pRemember_me = $res->remember_me;
            $this->pLast_failed_login = $res->last_failed_login;
            $this->pFailed_logins = $res->failed_logins;
            $this->pPublic_profile = $res->public_profile;
            $this->pTmp_token = $res->tmp_token;
            $this->pDate_tmp_token = $res->date_tmp_token;
            $this->pIs_locked = $res->is_locked;
            return $this;
        } else {
            return false;
        }
    }
    /**
     * @param int $id
     * @return string $datas[]
     * @desc load user datas by its username
     */
    public function fetch_by_username($username)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE `login` = '".addslashes($username)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->id = $res->id;
            $this->pMd5 = $res->md5;
            $this->pName = $res->name;
            $this->pFirstname = $res->firstname;
            $this->pLogin = $res->login;
            $this->pPass = $res->pass;
            $this->pEmail = $res->email;
            $this->pDescription = $res->description;
            $this->pAvatar_path = $res->avatar_path;
            $this->pActive = $res->active;
            $this->pLast_login = $res->last_login;
            $this->pRemember_me = $res->remember_me;
            $this->pLast_failed_login = $res->last_failed_login;
            $this->pFailed_logins = $res->failed_logins;
            $this->pPublic_profile = $res->public_profile;
            $this->pTmp_token = $res->tmp_token;
            $this->pDate_tmp_token = $res->date_tmp_token;
            $this->pIs_locked = $res->is_locked;
            return $this;
        } else {
            return false;
        }
    }
    /**
     * @param int $id
     * @return string $datas[]
     * @desc load user datas by its token
     */
    public function fetch_user_with_token($md5, $token)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE `md5` = '".addslashes($md5)."'
                       AND `remember_me` = '".addslashes($token)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->id = $res->id;
            $this->pMd5 = $res->md5;
            $this->pName = $res->name;
            $this->pFirstname = $res->firstname;
            $this->pLogin = $res->login;
            $this->pPass = $res->pass;
            $this->pEmail = $res->email;
            $this->pDescription = $res->description;
            $this->pAvatar_path = $res->avatar_path;
            $this->pActive = $res->active;
            $this->pLast_login = $res->last_login;
            $this->pRemember_me = $res->remember_me;
            $this->pLast_failed_login = $res->last_failed_login;
            $this->pFailed_logins = $res->failed_logins;
            $this->pPublic_profile = $res->public_profile;
            $this->pTmp_token = $res->tmp_token;
            $this->pDate_tmp_token = $res->date_tmp_token;
            $this->pIs_locked = $res->is_locked;
            return $this;
        } else {
            return false;
        }
    }
    /**
     * @desc load user rights
     */
    public function fetch_right()
    {
        $r = new rights($this->db);
        $r->fetch($this->id);
        $this->pRights=$r;
    }
    /**
     * @return string $rights[]
     * @desc get user rights
     */
    public function get_rights()
    {
        if ($this->get_id()>0)
            return $this->pRights;
        else
            return false;
    }
    /**
     * @param string $right
     * @return string $rights
     * @desc Check if user has particular right (0, no, 1 yes, 2 inherited)
     */
    public function has_right($right)
    {
        return $this->pRights->$right;
    }
    /**
     * @param int $id
     * @return string $datas[]
     * @desc Check if user have some admin rights
     */
    public function has_some_admin_right()
    {
        return ($this->pRights->has_some_admin_rights());
    }
    /**
     * @return string $datas[]
     * @desc Make all private data public
     */
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            global $conf;
            $this->id = $this->pId;
            $this->md5 = $this->pMd5;
            $this->name = $this->pName;
            $this->firstname = $this->pFirstname;
            $this->login = $this->pLogin;
            $this->pass = $this->pPass;
            $this->email = $this->pEmail;
            $this->description = $this->pDescription;
            $this->avatar_path = $this->pAvatar_path;
            if ($this->avatar_path."x"=="x") $this->avatar_path=$conf->default_avatar;
            $this->active = $this->pActive;
            $this->last_login = $this->pLast_login;
            $this->remember_me = $this->pRemember_me;
            $this->last_failed_login = $this->pLast_failed_login;
            $this->failed_login = $this->pFailed_logins;
            $this->public_profile = $this->pPublic_profile;
            $this->tmp_token = $this->pTmp_token;
            $this->date_tmp_token = $this->pDate_tmp_token;
            $this->is_locked = $this->pIs_locked;

            return $this;
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
        $requete = "INSERT INTO ".$this->table."
                                (`md5`)
                         VALUES (md5(now()))";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            global $trigger,$current_user;
            $trigger->run_trigger("ADD_USER", $this,$current_user);
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        if (!$sql) return false;
        return $this->db->last_insert_id($this->table);
    }
    /**
     * @return bool $result
     * @desc del user
     */
    public function unsecure_del()
    {
        if ($this->get_id() > 0)
        {
            $requete = "DELETE FROM ".$this->table."
                              WHERE id = ".$this->get_id();
            if ($sql)
            {
                global $trigger,$current_user;
                $trigger->run_trigger("DEL_USER", $this,$current_user);
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            $sql = $this->db->query($requete);
            return ($sql);
        } else {
            return false;
        }
    }
    /**
     * @return bool $result
     * @desc Delete user securely
     */
    public function del()
    {
        if ($this->get_id()>0)
        {
            global $conf;
            //On supprime pas les pages => on cree un nouvelle utilisateur anonyme
            $id = $this->create();
            $u = new user($this->db);
            $u->fetch($id);
            $u->set_active("0");
            $u->set_name('Anonyme');
            $u->set_login('Anonyme_'.$this->get_id());
            $u->set_email('Anonyme_'.$this->get_id());
            $u->set_description('Deleted user');
            if ($conf->user_delete_delete_comment == "on")
            {
                $requete = "DELETE FROM commentaire
                                  WHERE author_id = ".$this->get_id();
                $sql = $this->db->query($requete);
                if ($sql)
                {
                    $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
                } else {
                    $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
                }
            } else {
                $requete = "UPDATE commentaire
                               SET author_id = ".$id."
                             WHERE author_id = ".$this->get_id();
                $sql = $this->db->query($requete);
                if ($sql)
                {
                    $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
                } else {
                    $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
                }
            }
            $requete = "UPDATE page
                           SET user_refid = ".$id."
                         WHERE user_refid = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }

            $requete = "UPDATE section
                           SET user_refid = ".$id."
                         WHERE user_refid = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }

            $requete = "DELETE FROM user_rights
                              WHERE user_refid = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }

            $requete = "DELETE FROM group_user
                              WHERE user_refid = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }

            $requete = "DELETE FROM group_publication_user
                              WHERE user_refid = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }

            $requete = "DELETE FROM ".$this->table."
                              WHERE id = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            global $trigger,$current_user;
            $trigger->run_trigger("DEL_USER", $this,$current_user);
            return ($sql);
        } else {
            return false;
        }
    }
    /**
     * @return string $name
     * @desc get user name
     */
    public function get_name()
    {
        if ($this->get_id() > 0)
            return $this->pName;
        else
            return false;
    }
    /**
     * @return string $firstname
     * @desc get user firstname
     */

    public function get_firstname()
    {
        if ($this->get_id() > 0)
            return $this->pFirstname;
        else
            return false;
    }
    /**
     * @return string $login
     * @desc get user login
     */
    public function get_login()
    {
        if ($this->get_id() > 0)
            return $this->pLogin;
        else
            return false;
    }
    /**
     * @return string $pass
     * @desc get user encoded pass
     */
    public function get_pass()
    {
        if ($this->get_id() > 0)
            return $this->pPass;
        else
            return false;
    }
    /**
     * @return string $email
     * @desc get user email
     */

    public function get_email()
    {
        if ($this->get_id() > 0)
            return $this->pEmail;
        else
            return false;
    }
    /**
     * @return string $description
     * @desc get user description
     */

    public function get_description()
    {
        if ($this->get_id() > 0)
            return $this->pDescription;
        else
            return false;
    }
    /**
     * @return string $avatar_path
     * @desc get user avatar path
     */

    public function get_avatar_path($with_default=false)
    {
        if ($this->get_id() > 0)
        {
            global $conf;
            $avatar_path = $this->pAvatar_path;
            if ($with_default && $avatar_path."x"=="x") $avatar_path=$conf->default_avatar;
            return $avatar_path;
        }
        else
            return false;
    }
    /**
     * @return string $id
     * @desc get user id
     */

    public function get_id()
    {
        if ($this->pId > 0)
            return $this->pId;
        else
            return false;
    }
    /**
     * @return bool $active
     * @desc Is user active?
     */

    public function get_active()
    {
        if ($this->get_id() > 0)
            return $this->pActive;
        else
            return false;
    }
    /**
     * @return string $md5
     * @desc get user md5
     */

    public function get_md5()
    {
        if ($this->get_id() > 0)
            return $this->pMd5;
        else
            return false;
    }
    /**
     * @return string $last_login
     * @desc get user last login date
     */

    public function get_last_login()
    {
        if ($this->get_id() > 0)
            return $this->pLast_login;
        else
            return false;
    }
    /**
     * @return bool $remember_me
     * @desc Is user used remeber me on login
     */

    public function get_remember_me()
    {
        if ($this->get_id() > 0)
            return $this->pRemember_me;
        else
            return false;
    }
    /**
     * @return string $last_failed_login
     * @desc get user last failed login date
     */

    public function get_last_failed_login()
    {
        if ($this->get_id() > 0)
            return $this->pLast_failed_login;
        else
            return false;
    }
    /**
     * @return string $failed_login
     * @desc get user quantity of failed login since last successfull connection
     */
    public function get_failed_logins()
    {
        if ($this->get_id() > 0)
            return $this->pFailed_logins;
        else
            return false;
    }
    /**
     * @return int $public_profile
     * @desc Get user profile publication privacy
     */

    public function get_public_profile()
    {
        if ($this->get_id() > 0)
            return $this->pPublic_profile;
        else
            return false;
    }
    /**
     * @return string $tmp_token
     * @desc Get user tmp token
     */
    public function get_tmp_token()
    {
        if ($this->get_id() > 0)
            return $this->pTmp_token;
        else
            return false;
    }
    /**
     * @return datetime $date_tmp_token
     * @desc Get user tmp token date of creation
     */
    public function get_date_tmp_token()
    {
        if ($this->get_id() > 0)
            return $this->pDate_tmp_token;
        else
            return false;
    }
    /**
     * @return bool $is_locked
     * @desc Does user account is lock?
     */
    public function get_is_locked()
    {
        if ($this->get_id() > 0)
            return $this->pIs_locked;
        else
            return false;
    }
    /**
     * @param string $name
     * @return bool result
     * @desc Set user name
     */
    public function set_name($name)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            return $this->update_field('name',$name);
        } else {
            return false;
        }
    }
    /**
     * @param string $firstname
     * @return bool result
     * @desc Set user firstname
     */

    public function set_firstname($firstname)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            return $this->update_field('firstname',$firstname);
        } else {
            return false;
        }
    }
    /**
     * @param string $login
     * @return bool result
     * @desc Set user login
     */

    public function set_login($login)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            return $this->update_field('login',$login);
        } else {
            return false;
        }
    }
    /**
     * @param string $pass
     * @return bool result
     * @desc Set user password (encoded)
     */

    public function set_pass($pass)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            return $this->update_field('pass',md5($pass));
        } else {
            return false;
        }
    }
    /**
     * @param string $email
     * @return bool result
     * @desc Set user email
     */

    public function set_email($email)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            return $this->update_field('email',$email);
        } else {
            return false;
        }
    }
    /**
     * @param string $description
     * @return bool result
     * @desc Set user description
     */

    public function set_description($description)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            return $this->update_field('description',$description);
        } else {
            return false;
        }
    }
    /**
     * @param string $avatar_path
     * @return bool result
     * @desc Set user avatar path
     */

    public function set_avatar_path($avatar_path)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            return $this->update_field('avatar_path',$avatar_path);
        } else {
            return false;
        }
    }
    /**
     * @param bool $active
     * @return bool result
     * @desc Is user account active ?
     */
    public function set_active($active)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            return $this->update_field('active',$active);
        } else {
            return false;
        }
    }
    /**
     * @param string $last_login
     * @return bool result
     * @desc Set user last login date
     */
    public function set_last_login($last_login=false)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("last_login", "now");
        }
    }
    /**
     * @param string $failed_logins
     * @return bool result
     * @desc Set user quantity of failed login (or reset if 0)
     */
    public function set_failed_logins($failed_logins)
    {
        if ($failed_logins>0 && $this->get_id() > 0)
        {
            $requete = "UPDATE ".$this->table."
                           SET failed_logins = failed_logins + 1
                         WHERE id = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            return ($sql);
        } else {
            $requete = "UPDATE ".$this->table."
                           SET failed_logins = 0
                         WHERE id = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            return ($sql);
        }
    }
    /**
     * @param string $random
     * @return bool result
     * @desc Set remember_me random number
     */
    public function set_remember_me($random)
    {
        if ($this->get_id()>0)
        {
            $this->update_field('remember_me', $random);
        }
    }
    /**
     * @return bool result
     * @desc Set user last failed login date to now
     */
    public function set_last_failed_login()
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("last_failed_login", "now");
        }
    }
    /**
     * @param string $public_profile
     * @return bool result
     * @desc Set user public profile privacy policy
     */
    public function set_public_profile($public_profile)
    {
        if ($this->get_id()>0)
        {
            if (empty($public_profile) || !$public_profile ||is_null($public_profile))
                $public_profile = "0";
            return $this->update_field('public_profile', $public_profile);
        }
    }
    /**
     * @param string $tmp_token
     * @return bool result
     * @desc Set user tmp token
     */
    public function set_tmp_token($tmp_token)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("tmp_token", $tmp_token);
        }
    }
    /**
     * @param bool $is_locked
     * @return bool result
     * @desc Set user locked
     */
    public function set_is_locked($is_locked)
    {
        if ($this->get_id() > 0)
        {
            if (empty($is_locked) || !$is_locked ||is_null($is_locked))
                $is_locked = "0";
            if ($is_locked > 0)
            {
                $this->set_active("0");
            }
            return $this->update_field("is_locked", $is_locked);
        }
    }
    /**
     * @param datetime $date_tmp_token
     * @return bool result
     * @desc Set date to now on tmp token
     */
    public function set_date_tmp_token($date_tmp_token)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("date_tmp_token", "now");
        }
    }
    /**
     * @param string $email
     * @return bool result
     * @desc Create temporary token
     */
    public function set_temporary_token($email)
    {
        //1 on génére un temp token + db
        $requete = "UPDATE ".$this->table."
                       SET tmp_token=CONCAT(MD5(CONCAT(email,now())),'-',CONCAT(MD5(CONCAT(login,now())))),
                           date_tmp_token = now()
                     WHERE email = '".addslashes($email)."' ";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        //2 trouve le user
        if ($sql)
        {
            $requete = "SELECT id
                          FROM ".$this->table."
                         WHERE email = '".addslashes($email)."'";
            $sql = $this->db->query($requete);
            if ($sql && $this->db->num_rows($sql) > 0 )
            {
                $res = $this->db->fetch_object($sql);
                $u = new user($this->db);
                $u->fetch($res->id);
                return $u;
            } else {
                return false;
            }
        } else {
            return false;
        }
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
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE tmp_token = '".addslashes($token)."'
                       AND email = '".addslashes($email)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->fetch($res->id);
            $this->set_pass(md5($pass));
            $requete = "UPDATE ".$this->table."
                          SET tmp_token = null,
                              date_tmp_token = null
                        WHERE id = ".$res->id;
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            return true;
        } else {
            return false;
        }
    }
}
class rights extends common_object
{
    protected $db;
    private $table = "user_rights";
    private $has_some_rights = false;

    /**
     * @param string $user_id
     * @return bool result
     * @desc Fetch user rights
     */
    public function fetch($user_id)
    {
        $all_group = false;

        $requete = "SELECT *
                     FROM `group`,
                          group_user
                    WHERE user_refid = ".$user_id;
        $sql = $this->db->query($requete);
        $a = array();
        while ($res = $this->db->fetch_object($sql))
        {
            $a[]=$res->id;
        }
        if (count($a) > 0) $all_group = join(',',$a);

        if ($all_group)
        {
            $requete = "SELECT rights_def.name,
                               group_rights.group_refid
                          FROM rights_def
                     LEFT JOIN group_rights ON rights_def.id = group_rights.rights_def_refid
                           AND (group_rights.group_refid IN (".$all_group.") OR group_refid is null)";
            $sql = $this->db->query($requete);
            if ($sql)
            {
                while ($res = $this->db->fetch_object($sql))
                {
                    $name = $res->name;
                    $this->$name = (isset($res->group_refid)?2:false);
                    if (isset($res->group_refid))
                    {
                        $this->has_some_rights = true;
                    }
                }
            }
        }

        $requete = "SELECT rights_def.name, user_rights.user_refid
                      FROM rights_def
                 LEFT JOIN user_rights ON rights_def.id = user_rights.rights_def_refid
                       AND (user_rights.user_refid = ".$user_id." OR user_refid is null)";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            while ($res = $this->db->fetch_object($sql))
            {
                $name = $res->name;
                $this->$name = (isset($this->$name) && $this->$name == 2?2:(isset($res->user_refid)?1:false));
                if (isset($res->user_refid))
                {
                    $this->has_some_rights = true;
                }
            }
        } else {
            return false;
        }
        unset ($this->log);
        return $this;
    }
    /**
     * @return bool result
     * @desc Tell if user have any admin rights
     */
    public function has_some_admin_rights()
    {
        return $this->has_some_rights;
    }
}
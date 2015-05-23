<?php
class group_publication extends common_object
{
    protected $db;
    private $pId;
    private $pName;
    private $pEmail;
    private $pDescription;
    private $pAvatar_path;
    private $pActive;
    private $pMd5;
    private $pIs_public;
    private $pIs_admin;


    public $id;
    public $name;
    public $email;
    public $description;
    public $avatar_path;
    public $active;
    public $md5;
    private $is_public;
    private $is_admin;

    public $log;

    protected $table = "group_publication";

    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM `".$this->table."`
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->pMd5 = $res->md5;
            $this->pName = $res->name;
            $this->pEmail = $res->email;
            $this->pDescription = $res->description;
            $this->pAvatar_path = $res->avatar_path;
            $this->pActive = $res->active;
            $this->pIs_admin = $res->is_admin;
            $this->pIs_public = $res->is_public;

            return $this;
        } else {
            return false;
        }
    }
    public function fetch_by_md5($md5)
    {
        $requete = "SELECT *
                      FROM `".$this->table."`
                     WHERE `md5` = '".addslashes($md5)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->pMd5 = $res->md5;
            $this->pName = $res->name;
            $this->pEmail = $res->email;
            $this->pDescription = $res->description;
            $this->pAvatar_path = $res->avatar_path;
            $this->pActive = $res->active;
            $this->pIs_admin = $res->is_admin;
            $this->pIs_public = $res->is_public;

            return $this;
        } else {
            return false;
        }
    }
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->pId;
            $this->md5 = $this->pMd5;
            $this->name = $this->pName;
            $this->email = $this->pEmail;
            $this->description = $this->pDescription;
            $this->avatar_path = $this->pAvatar_path;
            $this->active = $this->pActive;
            $this->is_admin = $this->pIs_admin;
            $this->is_public = $this->pIs_public;

            return $this;
        } else {
            return false;
        }
    }
    public function create()
    {
        $requete = "INSERT INTO `".$this->table."` (`md5`) VALUES (md5(now()))";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            global $trigger,$current_user;
            $trigger->run_trigger("ADD_GROUP_PUBLI", $this,$current_user);
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        if (!$sql) return false;
        return $this->db->last_insert_id($this->table);
    }
    public function del()
    {
        if ($this->get_is_admin() > 0 || $this->get_is_public() > 0) return false;
        if ($this->get_id()>0)
        {
            $requete = "DELETE FROM group_publication_user
                              WHERE group_publication_refid = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            $requete = "DELETE FROM group_publication_page
                              WHERE group_publication_refid = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            $requete = "DELETE FROM `".$this->table."`
                              WHERE id = ".$this->pId;
            $sql = $this->db->query($requete);
            if ($sql)
            {
                global $trigger,$current_user;
                $trigger->run_trigger("DEL_GROUP", $this,$current_user);
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }

            return ($sql);
        } else {
            return false;
        }
    }
    public function get_name()
    {
        if ($this->get_id() > 0)
            return $this->pName;
        else
            return false;
    }
    public function get_is_admin()
    {
        if ($this->get_id() > 0)
            return $this->pIs_admin;
        else
            return false;
    }
    public function get_is_public()
    {
        if ($this->get_id() > 0)
            return $this->pIs_public;
        else
            return false;
    }
    public function get_email()
    {
        if ($this->get_id() > 0)
            return $this->pEmail;
        else
            return false;
    }
    public function get_description()
    {
        if ($this->get_id() > 0)
            return $this->pDescription;
        else
            return false;
    }
    public function get_avatar_path()
    {
        if ($this->get_id() > 0)
            return $this->pAvatar_path;
        else
            return false;
    }
    public function get_id()
    {
        if ($this->pId > 0)
            return $this->pId;
        else
            return false;
    }
    public function get_active()
    {
        if ($this->get_id() > 0)
            return $this->pActive;
        else
            return false;
    }
    public function get_md5()
    {
        if ($this->get_id() > 0)
            return $this->pMd5;
        else
            return false;
    }
    public function set_name($name)
    {
        if ($this->get_id() > 0){
            $this->id = $this->pId;
            return $this->update_field('name',$name);
        } else {
            return false;
        }
    }
    public function set_email($email)
    {
        if ($this->get_id() > 0){
            $this->id = $this->pId;
            return $this->update_field('email',$email);
        } else {
            return false;
        }
    }
    public function set_description($description)
    {
        if ($this->get_id() > 0){
            $this->id = $this->pId;
            return $this->update_field('description',$description);
        } else {
            return false;
        }
    }
    public function set_avatar_path($avatar_path)
    {
        if ($this->get_id() > 0){
            $this->id = $this->pId;
            return $this->update_field('avatar_path',$avatar_path);
        } else {
            return false;
        }
    }
    public function set_active($active)
    {
        if ($this->get_id() > 0)
        {
            if (empty($action) || !$active || is_null($active)) $active="0";
            $this->id = $this->pId;
            return $this->update_field('active',$active);
        } else {
            return false;
        }
    }
    public function set_is_public($is_public)
    {
        if ($this->get_id() > 0)
        {
            if (empty($is_public) || !$is_public || is_null($is_public)) $is_public="0";
            return $this->update_field('is_public',$is_public);
        } else {
            return false;
        }
    }
    public function set_is_admin($is_admin)
    {
        if ($this->get_id() > 0)
        {
            if (empty($is_admin) || !$is_admin || is_null($is_admin)) $is_admin="0";
            return $this->update_field('is_admin',$is_admin);
        } else {
            return false;
        }
    }

}
<?php
class template extends common_object
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

    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $res = $this->db->fetch_object($sql);
            $this->pId = $id;
            $this->pName = $res->name;
            $this->pPath = $res->path;
            $this->pContent = $res->content;
            $this->pExtra_params = $res->extra_params;
            $this->pPlugins = $res->plugins;
        } else {
            return false;
        }
    }
    public function del()
    {
        $requete = "DELETE FROM ".$this->table."
                          WHERE id = ".$this->pId;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            global $trigger,$current_user;
            $trigger->run_trigger("DEL_TEMPLATE", $this,$current_user);
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        return $sql;
    }
    public function create()
    {
        $name="New Template";
        $requete = "SELECT *
                      FROM ".$this->table."
                      WHERE name = '".addslashes($name)."'";
        $sql = $this->db->query($requete);
        if ($this->db->num_rows($sql) > 0)
        {
            $requete = "SELECT count(*) + 1 as c
                          FROM ".$this->table."
                         WHERE name LIKE '".addslashes($name)."%'";
            $sql = $this->db->query($requete);
            $res = $this->db->fetch_object($sql);
            $count = $res->c;
            $name = $name." (".$count.")";
        }
        $requete = "INSERT INTO ".$this->table." (name) VALUES ('".$name."') ";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            global $trigger,$current_user;
            $trigger->run_trigger("ADD_TEMPLATE", $this,$current_user);
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        return $this->db->last_insert_id($this->table);
    }
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->pId;
            $this->name = $this->pName;
            $this->path = $this->pPath;
            $this->content = $this->pContent;
            $this->extra_params = $this->pExtra_params;
            $this->plugins = $this->pPlugins;
        } else {
            return false;
        }
    }
    public function get_id()
    {
        if ($this->pId > 0)
        {
            $this->id = $this->pId;
            return $this->pId;
        }
        else
            return false;
    }
    public function get_name()
    {
        if ($this->get_id() > 0)
            return $this->pName;
        else
            return false;
    }
    public function get_path()
    {
        if ($this->get_id() > 0 && !empty($this->pPath))
            return $this->pPath;
        else
            return false;
    }
    public function get_content()
    {
        if ($this->get_id() > 0 && !empty($this->pContent))
            return $this->pContent;
        else
            return false;
    }
    public function get_extra_params()
    {
        if ($this->get_id() > 0)
            return $this->pExtra_params;
        else
            return false;
    }
    public function get_plugins()
    {
        if ($this->get_id() > 0)
            return $this->pPlugins;
        else
            return false;
    }
    public function set_type($type)
    {
        $this->type = $type;
    }
    public function set_content($content)
    {
        if ($this->get_id() > 0){
            $this->id = $this->pId;
            return $this->update_field('content',$content);
        } else {
            return false;
        }
    }
    public function set_path($path)
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->pId;
            return $this->update_field('path', $path);
        } else {
            return false;
        }
    }
    public function set_name($name)
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->pId;
            return $this->update_field('name', $name);
        } else {
            return false;
        }
    }
    public function set_extra_params($extra_params)
    {
        if ($this->get_id() > 0)
        {
            if (is_array($extra_params)) $extra_params = json_encode($extra_params);
            return $this->update_field('extra_params', $extra_params);
        } else {
            return false;
        }
    }
    public function set_plugins($plugins)
    {
        if ($this->get_id() > 0)
        {
            if (is_array($plugins)) $plugins = json_encode($plugins);
            return $this->update_field('plugins', $plugins);
        } else {
            return false;
        }
    }

}
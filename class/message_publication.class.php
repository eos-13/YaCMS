<?php
load_alternative_class('class/headerfooter.class.php');
class message_publication extends headerfooter
{
    protected $table = "message_publication";
    private $pGroup_publication_refid;
    public $group_publication_refid;
    public $log;

    public function __construct($db)
    {
        $this->db = $db;
        global $log;
        $this->log = $log;
    }
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            parent::get_all();
            $this->group_publication_refid=$this->pGroup_publication_refid;
            return $this;
        } else {
            return false;
        }
    }
    public function fetch()
    {
        parent::fetch();
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$this->get_id();

        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res=$this->db->fetch_object($sql);
            $this->pDate_derniere_maj = $res->date_derniere_maj;
            $this->pGroup_publication_refid = $res->group_publication_refid;
            return $res->id;
        } else {
            return false;
        }
    }
    public function get_group_publication_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->pGroup_publication_refid;
        } else {
            return false;
        }
    }
    public function set_group_publication_refid($group_publication_refid)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field('group_publication_refid', $group_publication_refid);
        }
    }
    public function create()
    {
        if ($this->get_id()>0)
        {
            $requete = "SELECT *
                          FROM ".$this->table."
                         WHERE id = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql)
            {
                if ($this->db->num_rows($sql) > 0)
                {
                    return $this->id;
                } else {
                    $requete = "SELECT name
                                  FROM group_publication
                                 WHERE id = ".$this->id;

                    $sql = $this->db->query($requete);
                    $a=array();
                    $res = $this->db->fetch_object($sql);

                    $requete = "INSERT INTO ".$this->table."
                                            (id,name)
                                     VALUES (".$this->get_id().",'".addslashes($res->name)."')";
                    $sql = $this->db->query($requete);
                    if ($sql)
                    {
                        $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
                    } else {
                        $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
                    }
                    return $this->db->last_insert_id($this->table);
                }
            } else {
                return false;
            }
        }
    }
}
<?php

class internal_msg extends common_object
{
    //! Handler de la base de donnée
    protected $db;
    //! Handler syslog
    public $log;
    //! Nom de la table contenant le dictionnaire. Referencer dans la table dictionnaires
    protected $table='internal_msg';

    //! Id du type de internal_msg
    private $pInternal_msg_type_refid;
    //! Nom du type de internal_msg
    private $pInternal_msg_type;
    //! status du message (id)
    private $pInternal_msg_status_refid;
    //! status du message
    private $pInternal_msg_status;
    //! Le internal_msg
    private $pInternal_msg;
    //! Titre du internal_msg
    private $pTitle;
    //! Id du internal_msg parent
    private $pParents_refid;
    //! internal_msg parent
    private $pParents;
    //! Id utilisareur de l'auteur
    private $puser_refid;
    //! Auteur du internal_msg
    private $pAuthor;
    //!Flag d'effacement du internal_msg par l'utilisateur
    private $puser_deleted;
    //! ID internal msg
    private $pId;
    //! Date de creation du message
    private $pDate_create;

    //! Id du type de internal_msg
    public $internal_msg_type_refid;
    //! Nom du type de internal_msg
    public $internal_msg_type;
    //! status du message (id)
    public $internal_msg_status_refid;
    //! status du message
    public $internal_msg_status;
    //! Le internal_msg
    public $internal_msg;
    //! Titre du internal_msg
    public $title;
    //! Id du internal_msg parent
    public $parents_refid;
    //! internal_msg parent
    public $parents;
    //! Id utilisareur de l'auteur
    public $user_refid;
    //! Auteur du internal_msg
    public $author;
    //!Flag d'effacement du internal_msg par l'utilisateur
    public $user_deleted;
    //! ID internal msg
    public $id;
    //! Date de creation du message
    public $date_create;

    /**
     \brief        Insert un nouveau internal_msg
     \param        val          Valeur à inserer
     \return       bool         New SQL id  si OK; false si KO
     */
    public function insert($user_refid,$internal_msg_type_refid,$internal_msg,$title,$parents_refid = false)
    {
        if (!$user_refid || ! $user_refid > 0) $user_refid = "null";
        $requete = "INSERT INTO ".$this->table. " (`user_refid`, `internal_msg_type_refid`, `internal_msg`, `title`,`date_create`,`internal_msg_status_refid` ".($parents_refid?',parents_refid':'').")
                         VALUES ( ".$user_refid.", '".$internal_msg_type_refid."', '".addslashes($internal_msg)."', '".addslashes($title)."', now(),1".($parents_refid?','.$parents_refid:'').")";
        $sql = $this->db->query($requete);
        $this->log->log("Insert Author:".$user_refid." internal_msg type:".$internal_msg_type_refid." internal_msg:".$internal_msg." Title:".$title." into ".$this->table,LOG_DEBUG);
        if ($sql){
            $this->log->log('Success',LOG_DEBUG);
            $id = $this->db->last_insert_id($this->table);
            global $conf,$trigger,$current_user;
            $that=$this;
            $that->fetch($id);
            $trigger->run_trigger('INSERT_INTERNALMSG',$that,$current_user);
            if ($this->fetch_type_mantis($internal_msg_type_refid) == 1 && $conf->use_mantis == "on" )
            {
                load_alternative_class('class/mantis.class.php');
                $mantis=new mantis($this->db);
                $mantis->insert($id,$title,$internal_msg);
            }
            return ($id) ;
        } else {
            $this->log->log('Failure',LOG_DEBUG);
            return false;
        }
    }
    /**
     \brief        Récupère les informations d'un internal_msg
     \param        id           Id MySQL du internal_msg
     \return       bool         this si OK; false si KO
     */
    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table . "
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->pInternal_msg_type_refid = $res->internal_msg_type_refid;
            $this->pInternal_msg_type = $this->fetch_type($res->internal_msg_type_refid);
            $this->pInternal_msg_status_refid = $res->internal_msg_status_refid;
            $this->pInternal_msg_status = $this->fetch_status($res->internal_msg_status_refid);
            $this->pInternal_msg = $res->internal_msg;
            $this->pTitle = $res->title;
            $this->pDate_create = $res->date_create;
            $this->puser_deleted = $res->user_deleted;
            $this->pParents_refid = $res->parents_refid;
            $this->pParents = new internal_msg($this->db);
            $this->pParents->fetch($res->parents_refid);
            $this->puser_refid = $res->user_refid;
            $this->pAuthor = new user($this->db);
            $this->pAuthor->fetch($res->user_refid);
            return $this;
        } else {
            return false;
        }
    }

    /**
     \brief        Récupère les informations d'un internal_msg pour un utilisateur du site
     \param        id           Id MySQL du internal_msg
     \return       bool         this si OK; false si KO
     */
    public function fetch_for_users($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table . "
                     WHERE id = ".$id."
                       AND user_deleted = 0";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->pInternal_msg_type_refid = $res->internal_msg_type_refid;
            $this->pInternal_msg_type = $this->fetch_type($res->internal_msg_type_refid);
            $this->pInternal_msg = $res->internal_msg;
            $this->pInternal_msg_status_refid = $res->internal_msg_status_refid;
            $this->pInternal_msg_status = $this->fetch_status($res->internal_msg_status_refid);
            $this->pTitle = $res->title;
            $this->pDate_create = $res->date_create;
            $this->puser_deleted = $res->user_deleted;
            $this->pParents_refid = $res->parents_refid;
            $this->pParents = new internal_msg($this->db);
            $this->pParents->fetch($res->parents_refid);
            $this->puser_refid = $res->user_refid;
            $this->pAuthor = new users($this->db);
            $this->pAuthor->fetch($res->user_refid);
            return $this;
        } else {
            return false;
        }
    }
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            $this->internal_msg_type_refid = $this->pInternal_msg_type_refid;
            $this->internal_msg_type = $this->pInternal_msg_type;
            $this->internal_msg_status_refid = $this->pInternal_msg_status_refid;
            $this->internal_msg_status = $this->pInternal_msg_status;
            $this->internal_msg = $this->pInternal_msg;
            $this->title = $this->pTitle;
            $this->parents_refid = $this->pParents_refid;
            $this->parents = $this->pParents;
            $this->user_refid = $this->puser_refid;
            $this->author = $this->pAuthor;
            $this->user_deleted = $this->puser_deleted;
            $this->id = $this->pId;
            $this->date_create = $this->pDate_create;
            return $this;
        } else {
            return false;
        }
    }
    /**
     \brief        Récupère le type d'un internal_msg
     \param        id           Id MySQL du type de internal_msg
     \return       bool         name si OK; false si KO
     */
    private function fetch_type($type_id)
    {
        $requete = "SELECT *
                      FROM internal_msg_type
                     WHERE id = ".$type_id;
        $sql = $this->db->query($requete);
        $res = $this->db->fetch_object($sql);
        return ($res->name);
    }
    /**
     \brief        Récupère le statut d'un internal_msg
     \param        id           Id MySQL du type de internal_msg
     \return       bool         name si OK; false si KO
     */
    private function fetch_status($status_id)
    {
        $requete = "SELECT *
                      FROM internal_msg_status
                     WHERE id = ".$status_id;
        $sql = $this->db->query($requete);
        $res = $this->db->fetch_object($sql);
        return ($res->name);
    }

    public function get_id()
    {
        if ($this->pId)
        {
            $this->id = $this->pId;
            return $this->pId;
        }

        else return false;
    }
    public function get_internal_msg_type_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->pInternal_msg_type_refid;
        }
    }
    public function get_title()
    {
        if ($this->get_id() > 0)
        {
            return $this->pTitle;
        }
    }
    public function get_internal_msg_type()
    {
        if ($this->get_id() > 0)
        {
            return $this->pInternal_msg_type;
        }
    }
    public function get_internal_msg()
    {
        if ($this->get_id() > 0)
        {
            return $this->pInternal_msg;
        }
    }
    public function get_internal_msg_status_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->pInternal_msg_status_refid;
        }
    }
    public function get_user_deleted()
    {
        if ($this->get_id() > 0)
        {
            return $this->puser_deleted;
        }
    }
    public function get_parents()
    {
        if ($this->get_id() > 0)
        {
            return $this->pParents;
        }
    }
    public function get_parents_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->pParents_refid;
        }
    }
    public function get_author()
    {
        if ($this->get_id() > 0)
        {
            return $this->pAuthor;
        }
    }
    public function get_user_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->puser_refid;
        }
    }
    public function get_date_create()
    {
        if ($this->get_id() > 0)
        {
            return $this->pDate_create;
        }
    }

    public function set_internal_msg_type_refid($internal_msg_type_refid)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("internal_msg_type_refid", $internal_msg_type_refid);
        }
    }
    public function set_title($title)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("title", $title);
        }
    }
    public function set_internal_msg_status_refid($internal_msg_status_refid)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("internal_msg_status_refid", $internal_msg_status_refid);
        }
    }
    public function set_user_deleted($user_deleted)
    {
        if ($this->get_id() > 0)
        {
            if (is_null($user_deleted) || empty($user_deleted) || !$user_deleted)
            {
                $user_deleted = "0";
            }
            return $this->update_field("user_deleted", $user_deleted);
        }
    }
    public function set_parents_refid($parents_refid)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("parents_refid", $parents_refid);
        }
    }
    public function set_user_refid($user_refid)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("user_refid", $user_refid);
        }
    }

    /**
     \brief        Trouve si le ticket fait l'objet d'un mantis
     \param        id           Id MySQL du type de internal_msg
     \return       bool         1 OK; false si KO
     */
    private function fetch_type_mantis($type_id)
    {
        $requete = "SELECT *
                      FROM internal_msg_type
                     WHERE id = ".$type_id;
        $sql = $this->db->query($requete);
        $res = $this->db->fetch_object($sql);
        return ($res->mantis);
    }

    /**
     \brief        Ajoute une reponse à un internal_msg
     \param        id           Id MySQL du internal_msg
     \param        data         internal_msg de reponse
     \param        user_refid  ID SQL de l'auteur
     \return       bool         true si OK; false si KO
     */
    public function respond($id,$data,$user_refid=false)
    {
        if (!$user_refid)
        {
            global $conf;
            $user_refid = $conf->user_id_from_internal_msg;
            if (!$user_refid  || $user_refid == 0)
            {
                global $current_user;
                $user_refid = $current_user->get_id();
            }
        }
        $this->fetch($id);
        global $trigger,$current_user;
        $trigger->run_trigger("RESPOND_INTERNAL_MSG", $this,$current_user);
        return $this->insert($user_refid,$this->get_internal_msg_type_refid(),$data,$this->get_title() ,$id);
    }
}
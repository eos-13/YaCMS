<?php
load_alternative_class('class/common_soap_object.class.php');
class external_msg extends common_soap_object
{
    //! Handler de la base de donnée
    protected $db;
    //! Handler syslog
    public $log;
    //! Nom de la table contenant le dictionnaire. Referencer dans la table dictionnaires
    protected $table;

    //! Le external_msg
    private $pExternal_msg;
    //! Titre du external_msg
    private $pTitle;
    //! Id du external_msg parent
    private $pParents_refid;
    //! external_msg parent
    private $pParents;
    //! Id utilisareur de l'auteur
    private $pId;
    //! Date de creation du message
    private $pDate_create;
    //! Email de l'envoyeur
    private $puser_mail;
    //! External message status
    private $pStatus;

    //! Le external_msg
    public $external_msg;
    //! Titre du external_msg
    public $title;
    //! Id du external_msg parent
    public $parents_refid;
    //! external_msg parent
    public $parents;
    //! ID external msg
    public $id;
    //! Date de creation du message
    public $date_create;
    //! Email de l'envoyeur
    private $user_mail;
    //! External message status
    private $status;

    /**
     \brief        Constructeur de l'objet. Set les variables $db et $log
     \param        db           Handler de la base de données
     \return       void         vide
     */
    public function __construct($db)
    {
        $this->db = $db;
        global $log;
        $this->log = $log;
        $this->table = 'external_msg';
    }
    /**
     \brief        Insert un nouveau external_msg
     \param        val          Valeur à inserer
     \return       bool         New SQL id  si OK; false si KO
     */
    public function insert($user_mail,$external_msg,$title,$status=1,$parents_refid = false)
    {
        global $current_user,$conf;
        if ($user_mail == 'internal')
        {
            if ($conf->user_id_from_external_msg > 0)
            {
                $u = new user($this->db);
                $u->fetch($conf->user_id_from_email);
                $user_mail = $u->get_email();
            } else {
                $user_mail = $current_user->get_email();
            }
        }
        $requete = "INSERT INTO ".$this->table. "
                                (`user_mail`, `external_msg`, `title`,`date_create`,status ".($parents_refid?',parents_refid':'').")
                         VALUES ( '".$user_mail."', '".addslashes($external_msg)."', '".addslashes($title)."', now(), ".$status." " .($parents_refid?','.$parents_refid:'').")";
        $sql = $this->db->query($requete);
        $this->log->log("Insert from ".$current_user->id." to:".$user_mail." external_msg:".$external_msg." Title:".$title." into ".$this->table,LOG_DEBUG);
        if ($sql){
            $this->log->log('Success',LOG_DEBUG);
            $id = $this->db->last_insert_id($this->table);
            global $conf,$trigger,$current_user;
            $that=$this;
            $that->fetch($id);
            $trigger->run_trigger('INSERT_EXTERNALMSG',$that,$current_user);
            return ($id) ;
        } else {
            $this->log->log('Failure',LOG_DEBUG);
            return false;
        }
    }
    /**
     \brief        Récupère les informations d'un external_msg
     \param        id           Id MySQL du external_msg
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
            $this->pExternal_msg = $res->external_msg;
            $this->pTitle = $res->title;
            $this->pDate_create = $res->date_create;
            $this->pParents_refid = $res->parents_refid;
            $this->puser_mail = $res->user_mail;
            $this->pStatus = $res->status;
            return $this;
        } else {
            return false;
        }
    }

    /**
     \brief        Récupère les informations d'un external_msg pour un utilisateur du site
     \param        id           Id MySQL du external_msg
     \return       bool         this si OK; false si KO
     */
    public function fetch_for_users($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table . "
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->pExternal_msg = $res->external_msg;
            $this->pTitle = $res->title;
            $this->pDate_create = $res->date_create;
            $this->pParents_refid = $res->parents_refid;
            $this->puser_mail = $res->user_mail;
            $this->pStatus = $res->status;
            return $this;
        } else {
            return false;
        }
    }
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            $this->pId = $this->id;
            $this->external_msg = $this->pExternal_msg;
            $this->title = $this->pTitle;
            $this->date_create = $this->pDate_create;
            $this->parents_refid = $this->pParents_refid ;
            $this->user_mail = $this->puser_mail;
            $this->status = $this->pStatus;
            return $this;
        } else {
            return false;
        }
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
    public function get_title()
    {
        if ($this->get_id() > 0)
        {
            return $this->pTitle;
        }
    }
    public function get_parents_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->pParents_refid;
        }
    }
    public function get_user_mail()
    {
        if ($this->get_id() > 0)
        {
            return $this->puser_mail;
        }
    }
    public function get_date_create()
    {
        if ($this->get_id() > 0)
        {
            return $this->pDate_create;
        }
    }
    public function get_status()
    {
        if ($this->get_id() > 0)
        {
            return $this->pStatus;
        }
    }

    public function set_title($title)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("title", $title);
        }
    }
    public function set_parents_refid($parents_refid)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("parents_refid", $parents_refid);
        }
    }
    public function set_user_mail($user_mail)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("user_mail", $user_mail);
        }
    }
    public function set_status($status)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field("status", $status);
        }
    }
}
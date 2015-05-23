<?php
load_alternative_class('class/common_soap_object.class.php');
class api_internal_msg extends common_soap_object
{
    //! Handler de la base de donnée
    protected $db;
    //! Handler syslog
    public $log;
    //! Nom de la table contenant le dictionnaire. Referencer dans la table dictionnaires
    protected $table = 'internal_msg';

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
     * @desc Create a new internal message
     * @return int $id
     * @param int $user_refid
     * @param int $internal_msg_type_refid
     * @param string $internal_msg
     * @param string $title
     * @param int $parents_refid
     */
    public function insert($user_refid,$internal_msg_type_refid,$internal_msg,$title,$parents_refid = false)
    {
        $this->obj->insert($user_refid,$internal_msg_type_refid,$internal_msg,$title,$parents_refid);
    }

    /**
     * @desc List all messages
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
                    'title' => $res->title,
                    'parent_refid' => $res->parent_refid
            );
        }
        return $a;
    }
    /**
     * @desc List all messages by author id
     * @return array $datas[]
     * @param int $user_id
     */
    public function list_all_by_author_refid($user_id)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE author_refid = ".$user_id;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id] = array(
                    'id' => $res->id,
                    'title' => $res->title,
                    'parent_refid' => $res->parent_refid
            );
        }
        return $a;
    }

    /**
     * @desc Get all datas of an internal message
     * @param int $id
     * @return array $datas[]
     */
    public function get_all($id)
    {
        $this->load($id);
        if ($this->get_id() > 0)
        {
            $a = array();
            $a['id'] = $this->obj->get_id();
            $a['internal_msg_type_refid'] = $this->obj->get_internal_msg_type_refid();
            $a['internal_msg_type'] = $this->obj->get_internal_msg_type();
            $a['internal_msg_status_refid'] = $this->obj->get_internal_msg_status_refid();
            $a['internal_msg_status'] = $this->obj->get_internal_msg_status();
            $a['internal_msg'] = $this->obj->get_internal_msg();
            $a['title'] = $this->obj->get_title();
            $a['parents_refid'] = $this->obj->get_parents_refid();
            $a['user_refid'] = $this->obj->get_user_refid();
            $a['user_deleted'] = $this->obj->get_user_deleted();
            $a['date_create'] = $thi->objs->get_date_create();
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @desc Answer to an internal message
     * @param int $id
     * @param string $content
     * @param int $user_refid
     * @return int $answer_id
     */
    public function respond($id,$content,$user_refid=false)
    {
        $this->load($id);
        return $this->respond($id,$content,$user_refid=false);
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
     * @desc Get internal msg_type_refid
     * @param int $id
     * @return int $internal_msg_type_refid
     */
    public function get_internal_msg_type_refid($id)
    {
        $this->load($id);
        return $this->obj->get_internal_msg_type_refid();
    }
    /**
     * @desc Get title
     * @param int $id
     * @return string $title
     */
    public function get_title($id)
    {
        $this->load($id);
        return $this->obj->get_title();
    }
    /**
     * @desc Get internal msg_type
     * @param int $id
     * @return string $internal_msg_type
     */
    public function get_internal_msg_type($id)
    {
        $this->load($id);
        return $this->obj->get_internal_msg_type();
    }
    /**
     * @desc Get internal msg
     * @param int $id
     * @return string $internal_msg
     */
    public function get_internal_msg($id)
    {
        $this->load($id);
        return $this->obj->get_internal_msg();
    }
    /**
     * @desc Get internal msg_status_refid
     * @param int $id
     * @return int $internal_msg_status_refid
     */
    public function get_internal_msg_status_refid($id)
    {
        $this->load($id);
        return $this->obj->get_internal_msg_status_refid();
    }
    /**
     * @desc Get user deleted
     * @param int $id
     * @return int $user_deleted
     */
    public function get_user_deleted($id)
    {
        $this->load($id);
        return $this->obj->get_user_deleted();
    }

    /**
     * @desc Get parents refid
     * @param int $id
     * @return int $parents_refid
     */
    public function get_parents_refid($id)
    {
        $this->load($id);
        return $this->obj->get_parents_refid();
    }

    /**
     * @desc Get user refid
     * @param int $id
     * @return int $user_refid
     */
    public function get_user_refid($id)
    {
        $this->load($id);
        return $this->obj->get_user_refid();
    }
    /**
     * @desc Get date create
     * @param int $id
     * @return datetime $date_create
     */
    public function get_date_create($id)
    {
        $this->load($id);
        return $this->obj->get_date_create();
    }
    /**
     * @desc Set internal msg_type_refid
     * @param int $id
     * @param int $internal_msg_type_refid
     * @return bool $res
     */
    public function set_internal_msg_type_refid($id,$internal_msg_type_refid)
    {
        $this->load($id);
        return $this->obj->set_internal_msg_type_refid($internal_msg_type_refid);
    }
    /**
     * @desc Set title
     * @param int $id
     * @param string $title
     * @return bool $res
     */
    public function set_title($id,$title)
    {
        $this->load($id);
        return $this->obj->set_title($title);
    }
    /**
     * @desc Set internal msg_status_refid
     * @param int $id
     * @param int $internal_msg_status_refid
     * @return bool $res
     */
    public function set_internal_msg_status_refid($id,$internal_msg_status_refid)
    {
        $this->load($id);
        return $this->obj->set_internal_msg_status_refid($internal_msg_status_refid);
    }
    /**
     * @desc Set user deleted
     * @param int $id
     * @param int $user_deleted
     * @return bool $res
     */
    public function set_user_deleted($id,$user_deleted)
    {
        $this->load($id);
        return $this->obj->set_user_deleted($user_deleted);
    }
    /**
     * @desc Set parents refid
     * @param int $id
     * @param int $parents_refid
     * @return bool $res
     */
    public function set_parents_refid($id,$parents_refid)
    {
        $this->load($id);
        return $this->obj->set_parents_refid($parents_refid);
    }
    /**
     * @desc Set user refid
     * @param int $id
     * @param int $user_refid
     * @return bool $res
     */
    public function set_user_refid($id,$user_refid)
    {
        $this->load($id);
        return $this->obj->set_user_refid($user_refid);
    }

}
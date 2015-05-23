<?php
load_alternative_class('class/common_soap_object.class.php');
class api_external_msg extends common_soap_object
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
     * @desc Get all datas
     * @return array $datas[]
     * @param int $id
     */
    public function get_all($id)
    {
        $this->load($id);
        if ($this->obj->get_id()>0)
        {
            $a['id'] = $this->obj->get_id();
            $a['external_msg'] = $this->obj->get_external_msg();
            $a['title'] = $this->obj->get_title();
            $a['date_create'] = $this->obj->get_date_create();
            $a['parents_refid'] = $this->obj->get_parents_refid() ;
            $a['user_mail'] = $this->obj->get_user_mail();
            $a['status'] = $this->obj->get_status();
            return $a;
        }
    }
    /**
     * @return array $datas
     * @desc List all entry id
     */
    public function list_all()
    {
        global $conf;
        $requete = "SELECT id,
                           title
                      FROM " . $this->table;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]['id']=$res->id;
            $a[$res->id]['title']=$res->title;
        }
        return $a;
    }
    /**
     * @param string $user_email
     * @return array $datas
     * @desc List all entry id
     */
    public function list_all_for_user($user_email)
    {
        global $conf;
        $requete = "SELECT id,
                           title
                      FROM " . $this->table."
                     WHERE user_mail = '".addslashes($user_email)."' ";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]['id']=$res->id;
            $a[$res->id]['title']=$res->title;
        }
        return $a;
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
     * @desc Get parent message
     * @param int $id
     * @return int $parents_refid
     */
    public function get_parents_refid($id)
    {
        $this->load($id);
        return $this->obj->get_parents_refid();
    }
    /**
     * @desc Get user mail
     * @param int $id
     * @return string $user_mail
     */
    public function get_user_mail($id)
    {
        $this->load($id);
        return $this->obj->get_user_mail();
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
     * @desc Get status
     * @param int $id
     * @return int $status
     */
    public function get_status($id)
    {
        $this->load($id);
        return $this->obj->get_status();
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
     * @desc Set parent external message
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
     * @desc Set user mail
     * @param int $id
     * @param string $user_mail
     * @return bool $res
     */
    public function set_user_mail($id,$user_mail)
    {
        $this->load($id);
        return $this->obj->set_user_mail($user_mail);
    }
    /**
     * @desc Set status
     * @param int $id
     * @param int $status
     * @return bool $res
     */
    public function set_status($id,$status)
    {
        $this->load($id);
        return $this->obj->set_status($status);
    }
    /**
     * @desc Create a new external message
     * @param string $user_mail
     * @param string $external_msg
     * @param string $title
     * @param int $status
     * @param int $parents_refid
     * @return bool $res
     */
    public function create($user_mail,$external_msg,$title,$status=1,$parents_refid = false)
    {
        return $this->obj->create($user_mail,$external_msg,$title,$status,$parents_refid);
    }
}
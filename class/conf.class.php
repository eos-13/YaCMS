<?php


class conf
{
    public $main_url_root;
    public $main_document_root;
    public $main_db_host;
    public $main_db_port;
    public $main_db_name;
    public $main_db_user;
    public $main_db_pass;
    public $main_db_type;
    public $main_db_character_set;
    public $character_set_client;
    public $main_db_collation;
    public $main_base_path;
    public $admin_mode = false;
    public $required_path;
    public $log;
    protected $db;

    public function __construct($bdd=false)
    {
        require(__DIR__.'/../conf/conf.php');
        $this->main_url_root=$main_url_root;
        $this->main_document_root=$main_document_root;
        $this->main_db_host=$main_db_host;
        $this->main_db_port=$main_db_port.
        $this->main_db_name=$main_db_name;
        $this->main_db_user=$main_db_user;
        $this->main_db_pass=$main_db_pass;
        $this->main_db_type=$main_db_type;
        $this->main_db_character_set=$main_db_character_set;
        $this->character_set_client=$character_set_client;
        $this->main_db_collation=$main_db_collation;
        $this->main_base_path=$main_base_path;
        $this->admin_keyword = $admin_keyword;
        $this->db = $bdd;
    }

    public function load_from_db($db)
    {
        $requete = "SELECT *
                     FROM conf";
        $sql = $db->query($requete);
        while ($res = $db->fetch_object($sql))
        {
            $key = $res->key;
            $this->$key = $res->value;
        }
        global $log;
        $this->db = $db;
        $this->log = $log;

    }
    /**
     * @param string $key
     * @param string $value
     * @param string $type
     * @param string $desc
     * @return boolean $res
     * @desc Set the value of a config parameter
     */
    public function set_value($key,$value,$type="text",$desc=false)
    {
        if (!$desc) $desc = 'Some description';
        $desc = addslashes($desc);
        if (!$type) $type = 'text';
        $type = addslashes($type);
        $requete = "INSERT INTO conf (`key`,`value`,`type`,`mandatory`,`description`)
                         VALUES ('".addslashes($key)."','".addslashes($value)."','".$type."' ,'0','".$desc."')
               ON DUPLICATE KEY UPDATE `value`='".addslashes($value)."'";
        $sql = $this->db->query($requete);
        return $sql;
    }
}
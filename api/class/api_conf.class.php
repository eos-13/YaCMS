<?php

load_alternative_class('class/common_soap_object.class.php');
class api_conf extends common_soap_object
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

    /**
     * @param string $key
     * @return string $value
     * @desc get the value of a config parameter
     */
    public function get_value($key)
    {
        $this->obj->load_from_db($this->db);
        return $this->$key;
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
        if (!$desc) $desc = 'Added from SOAP API';
        $desc = addslashes($desc);
        if (!$type) $type = 'text';
        $type = addslashes($type);
        $requete = "INSERT INTO conf (`key`,`value`,`type`,`mandatory`,`description`)
                         VALUES ('".addslashes($key)."','".addslashes($value)."','".$type."' ,'0','".$desc."')
               ON DUPLICATE KEY UPDATE `value`='".addslashes($value)."'";
        $sql = $this->db->query($requete);
        return $sql;
    }
    /**
     * @param string $key
     * @return boolean $res
     * @desc Del a conf key (only not mandatory key)
     */
    public function del_key($key)
    {
        $requete = "DELETE FROM conf
                          WHERE `key`='".addslashes($key)."'
                           AND mandatory = 0 ";
        $sql = $this->db->query($requete);
        return $sql;
    }

}
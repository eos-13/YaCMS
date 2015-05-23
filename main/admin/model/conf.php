<?php
class model_conf extends admin_common
{
    public $message = false;

    public function run()
    {
        $array=array();
        $requete = "SELECT *
                      FROM conf
                  ORDER BY mandatory DESC, `key` ASC";
        $sql = $this->db->query($requete);
        while ($res = $this->db->fetch_object($sql))
        {
            $array[]=array('key' => $res->key, 'value' => $res->value, 'type' => $res->type, 'mandatory' => $res->mandatory, 'description' => $res->description);
        }
        return $array;
    }
    public function set($post)
    {
        $requete = "UPDATE `conf`
                       SET `value`='".addslashes($post['update_value'])."'
                     WHERE `key` = '".addslashes($post['element_id'])."'";
        $sql = $this->db->query($requete);
        if ($sql)
            return $post['update_value'];
        else
            return $post['original_value'];
    }
    public function set_description($post)
    {
        $requete = "UPDATE `conf`
                       SET `description`='".addslashes($post['update_value'])."'
                     WHERE `key` = '".addslashes(preg_replace('/^desc_/','',$post['element_id']))."'
                       AND `mandatory` <> 1";
        $sql = $this->db->query($requete);
        if ($sql)
            return $post['update_value'];
        else
            return $post['original_value'];
    }
    public function create($post)
    {
        $requete = "INSERT INTO `conf`
                                (`key`, `value`, `type`,`description`)
                         VALUES ('".addslashes($post['new_key'])."','".addslashes($post['new_value'])."','".addslashes($post['new_type'])."','".addslashes($post['new_description'])."')";
        $sql = $this->db->query($requete);
        return $sql;
    }
    public function delete($post)
    {
        $requete = "DELETE FROM `conf`
                          WHERE `key` = '".addslashes($post['key'])."'
                            AND `mandatory` <> 1 ";
        $sql = $this->db->query($requete);
        return $sql;
    }
    public function valid($post)
    {
        $requete = "SELECT *
                      FROM `conf`
                     WHERE `key` = '".addslashes($post['new_key'])."'";
        $sql = $this->db->query($requete);
        if (!$sql) {
            return (json_encode('DB Error') );
        }
        if ($this->db->num_rows($sql) > 0 ){
            return (json_encode('Cette clef existe déjà'));
        } else {
            return (json_encode(true));
        }
    }

}
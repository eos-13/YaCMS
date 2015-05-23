<?php
class model_activation extends common
{
    public $id;
    public function run()
    {
        global $conf,$current_user;

    }
    public function activate($post)
    {
        $requete="SELECT id
                    FROM user
                   WHERE md5(md5) = '".addslashes($post['token'])."'
                     AND is_locked = 0 ";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) == 1)
        {
            $res = $this->db->fetch_object($sql);
            $requete = "UPDATE user
                           SET active = 1
                         WHERE id = ".$res->id;
            return $this->db->query($requete);
        }
        return false;
    }
}
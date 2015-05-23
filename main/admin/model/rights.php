<?php
class model_rights extends admin_common
{
    public $id=false;
    public $message = false;

    public function run()
    {
        global $current_user;
        $a['rights'] = $this->get_rights(($this->id?$this->id:$current_user->get_id()));
        $a['allusers'] = $this->get_all_users();
        return $a;
    }
    public function update($post)
    {
        $error= false;
        $requete = "DELETE FROM user_rights
                          WHERE user_refid = ".$post['id'];
        $sql = $this->db->query($requete);
        if (!$sql) $error = true;
        foreach($post as $key => $val)
        {
            if (preg_match('/^rights_([0-9]*)/',$key,$arrMatch))
            {
                $requete = "INSERT INTO user_rights
                                        (user_refid, rights_def_refid)
                                 VALUES (".$post['id'].",".$arrMatch[1].")";
                $sql = $this->db->query($requete);
                if(!$sql) $error = true;
            }
        }
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function get_rights($id)
    {
        $u = new user($this->db);
        $u->fetch($id);
        $u->fetch_right();
        $array = array();
        $requete = "SELECT rights_def.id,
                           rights_def.name,
                           rights_def.description
                      FROM rights_def";
        $sql = $this->db->query($requete);
        while($res=$this->db->fetch_object($sql))
        {
            $array[] = array(
                            'id' => $res->id,
                            'name' => $res->name,
                            'description' => $res->description,
                            'user_refid' => $u->has_right($res->name),
                       );
        }
        return $array;
    }
    private function get_all_users()
    {
        $a = array();
        $requete = "SELECT *
                      FROM user
                     WHERE active = 1
                  ORDER BY name, firstname";
        $sql = $this->db->query($requete);
        while ($res = $this->db->fetch_object($sql))
        {
            $a[] = array(
                'id' => $res->id,
                'name' => $res->name." ".$res->firstname
            );
        }
        return $a;
    }
}
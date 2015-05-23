<?php
class model_group_rights extends admin_common
{
    public $id=false;
    public $message = false;

    public function run()
    {
        if (!$this->id)
        {
            $requete = "SELECT id
                          FROM `group`
                      ORDER BY name
                         LIMIT 1";
            $sql = $this->db->query($requete);
            $res = $this->db->fetch_object($sql);
            $this->id = $res->id;
        }
        $a['rights'] = $this->get_rights($this->id);
        $a['allgroups'] = $this->get_all_groups();
        return $a;
    }
    public function update($post)
    {
        $error = false;
        $requete = "DELETE FROM group_rights
                          WHERE group_refid = ".$post['id'];
        $sql = $this->db->query($requete);
        if (!$sql) $error = true;
        foreach($post as $key => $val)
        {
            if (preg_match('/^rights_([0-9]*)/',$key,$arrMatch))
            {
                $requete = "INSERT INTO group_rights
                                        (group_refid, rights_def_refid)
                                 VALUES (".$post['id'].",".$arrMatch[1].")";
                $sql = $this->db->query($requete);
                if (!$sql) $error = true;
            }
        }
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function get_rights($id)
    {
        $array = array();
        $requete = "SELECT rights_def.id,
                           rights_def.name,
                           rights_def.description,
                           group_rights.group_refid
                      FROM rights_def
                 LEFT JOIN group_rights ON rights_def.id = group_rights.rights_def_refid
                       AND (group_rights.group_refid = ".$id." OR group_refid is null) ";
        $sql = $this->db->query($requete);
        while($res=$this->db->fetch_object($sql))
        {
            $array[] = array(
                            'id' => $res->id,
                            'name' => $res->name,
                            'description' => $res->description,
                            'group_refid' => (isset($res->group_refid)?true:false),
                       );
        }
        return $array;
    }
    private function get_all_groups()
    {
        $a = array();
        $requete = "SELECT *
                      FROM `group`
                     WHERE active = 1
                  ORDER BY name";
        $sql = $this->db->query($requete);
        while ($res = $this->db->fetch_object($sql))
        {
            $a[] = array(
                'id' => $res->id,
                'name' => $res->name,
            );
        }
        return $a;
    }
}
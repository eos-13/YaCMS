<?php
class model_annuaire extends common
{
    public $id;
    public $user_list;
    public function run()
    {
        //var_dump($current_user->has_right('admin'));
        global $conf,$current_user;

        $requete = "SELECT id
                      FROM user
                     WHERE 1=1 ";
        if (!($current_user->has_right('admin') || $current_user->has_right('user')) && $current_user->get_id()>0)
        {
            //On ne voit que les public et autre membre de mes groupes
            $a = $this->find_compatible_group();
            $requete .= " AND public_profile = 1  ";
            $requete .= " OR (public_profile = 2 AND user.id IN (".join(',',$a).")) ";
        } else if (!$current_user->get_id()>0 || ($current_user->get_id()>0 && (!($current_user->has_right('admin') || $current_user->has_right('user'))))) {
            //On ne voit que les publics
            $requete .= " AND public_profile = 1  ";
        }
        //Sinon, on voit tout (=je suis admin ou droit user)
        $requete .= " ORDER BY name";
        $sql = $this->db->query($requete);
        while ($res = $this->db->fetch_object($sql))
        {
            $u = new user($this->db);
            $u->fetch($res->id);

            $this->user_list[] = array(
                "id" => $u->get_md5(),
                "firstname" => $u->get_firstname(),
                'name' => $u->get_name(),
                "login" => $u->get_login(),
                "email" => $this->hide_email($u->get_email()),
                "avatar" => preg_replace('/\/$/','',$conf->main_url_root)."/". $u->get_avatar_path(true)
            );
        }
        return $this->user_list;
    }
    public function find_compatible_group()
    {
        global $current_user;
        //On cherche tous les users qui sont dans un group auquel j'appartiens
        $requete = "SELECT DISTINCT user.id
                      FROM user,
                           group_user
                     WHERE user.id = group_user.user_refid
                       AND user_refid <> ".$current_user->get_id()."
                       AND group_user.group_refid IN ( SELECT group_refid
                                                         FROM group_user
                                                        WHERE user_refid = ".$current_user->get_id()." ) ";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[] = $res->id;
        }
        return $a;
    }

}
?>
<?php
class model_user extends admin_common
{
    public $id;
    public $responce;
    public $message = false;

    public function run()
    {
        return (true);
    }
    public function list_user()
    {
        return $this->jqGrid();
    }
    public function valid_user($post)
    {

        $requete = "SELECT *
                      FROM user
                     WHERE `".$post['col']."` = '".addslashes($post['val'])."'";
        if ($post['oper'] == 'edit')
        {
            $requete .= " AND id != ".$post['id'];
        }
        $sql = $this->db->query($requete);
        if (!$sql) {
            return (json_encode(array(false,$post['col'].': DB Error')) );
        }
        if ($this->db->num_rows($sql) > 0 ){
            return (json_encode(array(false, $post['col'].': Cette valeur est déjà prise')));
        } else {
            return (json_encode(array(true,"")));
        }

    }
    public function extra_datas($uid)
    {
        $a = array();
        $u = new user($this->db);
        $u->fetch($uid);
        $public_prof = "Privé";
        if ($u->get_public_profile() == 1){ $public_prof = "Public"; }
        if ($u->get_public_profile() == 2){ $public_prof = "Groupe"; }
        $last_login = "Jamais connecté";
        if ($u->get_last_login()."x" != "x")
        {
            $last_login = convert_time_us_to_fr($u->get_last_login());
        }
        $a['extra'][]=array('last_login' => $last_login,
                            'profile_public' => $public_prof ,
                            'md5' => $u->get_md5(),
                            'description' => $u->get_description()
        );
        $requete = "SELECT `group`.`name`,
                           `group`.`id`
                      FROM `group`,
                           `group_user`
                     WHERE `group`.`id` = `group_user`.`group_refid`
                       AND `user_refid` = ".$uid;
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $a['group'][]=array('name' => $res->name,
                                'id' => $res->id);
        }
        //Toutes les publications
        $requete = "SELECT *
                      FROM `page`
                     WHERE `author_refid` = ".$uid;
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $a['page'][]=array('url' => $res->url,
                               'title' => $res->title,
                               'date_derniere_maj' => ($res->date_derniere_maj?convert_time_us_to_fr($res->date_derniere_maj):"Jamais modifié"),
                               'date_creation' => convert_time_us_to_fr($res->date_creation),
                               'id' => $res->id);
        }
        //Tous les commentaires
        $requete = "SELECT `page`.`url`,
                           `page`.`title`,
                           `commentaire`.`title` as ctitle,
                           `commentaire`.`date_creation`,
                           `commentaire`.`valid`,
                           `commentaire`.`id` as cid,
                           `page`.`id` as pid
                      FROM `commentaire`,
                           `page`
                     WHERE `commentaire`.`page_refid` = page.id
                       AND `author_id` = ".$uid;
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $valid = "En attente de modération";
            if ($res->valid == 1) $valid = "Publié";
            if ($res->valid == 2) $valid = "Validé";
            $a['comment'][]=array('url' => $res->url,
                                  'title'  => $res->title,
                                  'ctitle' => $res->ctitle,
                                  'date_creation' => convert_time_us_to_fr($res->date_creation),
                                  'valid' => $valid,
                                  'cid' => $res->cid,
                                  'pid' => $res->pid,);
        }
        global $conf;
        $a['conf']['main_url_root']=$conf->main_url_root;
        return $a;
    }
    public function rem_members($post)
    {
        $error = false;
        if (isset($post['rem_group']))
        {
            foreach($post['rem_members'] as $key => $val)
            {
                $requete = "DELETE FROM group_user
                                  WHERE group_refid = ".$post['rem_group']."
                                    AND user_refid = ".$val." ";
                $sql = $this->db->query($requete);
                if (!$sql) $error = true;
            }
        }
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return false;
    }
    public function edit_user($post)
    {
        load_alternative_class('class/user.class.php');
        $u = new user($this->db);
        $this->db->begin();
        if ($post['oper']=='add')
        {
            $id = $u->create();
        } else {
            $id = $post['id'];
        }
        if (!$id) {
            $this->db->rollback();
            $this->message = _("Opération echouée");
            return false;
        }
        $u->fetch($id);
        if ($post['oper'] == 'del')
        {
            $res = $u->del();
            $this->db->commit();
            if ($res) $this->message = _("Opération effectuée");
            else $this->message = _("Opération echouée");
            return json_encode($res?_("OK"):_("KO"));
        }
        $error = false;
        foreach($post as $key=>$val)
        {
            $func = "set_".$key;
            if (method_exists($u, $func))
            {
                if ($key == 'active' && $val=='off')
                {
                    $res = $u->set_active("0");
                    if (!$res) $error = true;
                } elseif ($key=='active' && $val=='on')
                {
                    $res = $u->set_active("1");
                    if (!$res) $error = true;
                } elseif ($key == 'pass' && $val != $u->get_pass()  && $val."x" != "x")
                {
                    $res = $u->set_pass($val);
                    if (!$res) $error = true;
                } else {
                    $res = $u->$func($val);
                    if (!$res) $error = true;
                }
            }
        }
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        $this->db->commit();
        return json_encode($error?_("KO"):_("OK"));
    }
    private function jqGrid()
    {
        $page = $_REQUEST['page']; // get the requested page
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
        $sord = $_REQUEST['sord']; // get the direction
        $_search = $_REQUEST['_search'];
        $searchField = (isset($_REQUEST['searchField'])?$_REQUEST['searchField']:false);
        $searchOper = (isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:false);
        $searchString = (isset($_REQUEST['searchString'])?$_REQUEST['searchString']:"");

        if ($searchString."x" != "x" && preg_match('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/',$searchString,$arrMatch))
        {
            $searchString = $arrMatch['3']."-".$arrMatch['2']."-".$arrMatch['1'];
            $searchField =  "date_format(".$searchField.",'%Y-%m-%d')";
        }
        if ($searchOper){
            switch ($searchOper){
                case "eq":
                    $searchOper = '=';
                break;
                case "ne":
                    $searchOper = '<>';
                break;
                case "bw":
                    $searchOper = 'LIKE';
                    $searchString = $searchString."%";
                break;
                case "bn":
                    $searchOper = 'NOT LIKE';
                    $searchString = $searchString."%";
                break;
                case "ew":
                    $searchOper = 'LIKE';
                    $searchString = "%".$searchString;
                break;
                case "en":
                    $searchOper = 'NOT LIKE';
                    $searchString = "%".$searchString;
                break;
                case "cn":
                    $searchOper = 'LIKE';
                    $searchString = "%".$searchString."%";
                break;
                case "nc":
                    $searchOper = 'NOT LIKE';
                    $searchString = "%".$searchString."%";
                break;
                case "nu":
                    $searchOper = 'IS NULL';
                    $searchString = "";
                break;
                case "nn":
                    $searchOper = 'IS NOT NULL';
                    $searchString = "";
                break;
                case "in":
                    $searchOper = 'IN';
                    $searchString = "(".$searchString.")";
                break;
                case "ni":
                    $searchOper = 'NOT IN';
                    $searchString = "(".$searchString.")";
                break;
                case "lt":
                    $searchOper = '<';
                break;
                case "le":
                    $searchOper = '<=';
                break;
                case "gt":
                    $searchOper = '>';
                break;
                case "ge":
                    $searchOper = '>=';
                break;

            }
        }

        if(!$sidx) $sidx =1; // connect to the database

        $requete = "SELECT COUNT(*) AS count
                      FROM user
                     WHERE 1=1 ";
        if ($_search == "true") {
            $requete .= " AND ". $searchField. " ".$searchOper." '".$searchString."'";
        }
        $sql = $this->db->query($requete);
        $res = $this->db->fetch_object($sql);
        $count = $res->count;
        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        if ($start < 0) $start = 0;

        $requete = "SELECT *
                      FROM user
                     WHERE 1=1 ";
        if ($_search == "true") {
            $requete .= " AND ". $searchField. " ".$searchOper." '".$searchString."'";
        }
        $requete .= "
            ORDER BY $sidx $sord
            LIMIT $start , $limit";
        $sql = $this->db->query($requete);
        $this->responce = new response();
        $this->responce->page = $page;
        $this->responce->total = $total_pages;
        $this->responce->records = $count;
        $iter=0;

        global $conf;
        while($res = $this->db->fetch_object($sql))
        {
            $this->responce->rows[$iter]['id']=$res->id;
            $avatar_path = $res->avatar_path;
            if ($res->avatar_path."x" == "x" )
            {
                $avatar_path = $conf->main_url_root.(preg_match('/\/$/', $conf->main_url_root)?"":"/")."img/avatars/avatar-default.png";
            } else {
                $avatar_path = $conf->main_url_root.(preg_match('/\/$/', $conf->main_url_root)?"":"/").$avatar_path;
            }
            $this->responce->rows[$iter]['cell']=array($res->id,$res->login,$res->pass,$res->name,$res->firstname,$res->email,$res->description,$avatar_path,$res->active,$res->is_locked);
            $iter++;
        }
        return json_encode($this->responce);
    }
}
class response{
    public $page;
    public $total;
    public $records;
    public $rows=array();
}
<?php
class model_group extends admin_common
{
    public $id;
    public $responce;
    public $message = false;

    public function run()
    {
        $g = $this->all_group();
        $u = $this->all_users();
        $u1 = $this->list_members($g[0]['id']);
        return (array('groups' => $g, 'users' => $u, 'group_members' => $u1));
    }
    public function add_members($post)
    {
        $error = false;
        if (is_array($post['add_groups']))
        {
            foreach($post['add_groups'] as $key => $val)
            {
                foreach($post['add_members'] as $key1 => $val1)
                {
                    $requete = "INSERT INTO group_user
                                            (group_refid, user_refid)
                                     VALUES (".$val.",".$val1.")";
                    $sql = $this->db->query($requete);
                    if (!$sql) $error = true;
                }
            }
        }
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return false;
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
                if (!$sql) $error = false;
            }
        }
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return false;
    }
    public function list_members($gid)
    {
        $a=array();
        $requete = "SELECT user.id,
                           user.name,
                           user.firstname
                      FROM user,
                           group_user
                     WHERE group_user.user_refid = user.id
                       AND group_user.group_refid = ".$gid."
                  ORDER BY user.name, user.firstname ";
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $a[]=array( "id" => $res->id,
                        "name" => $res->name,
                        "firstname" => $res->firstname );
        }
        return $a;
    }
    private function all_group()
    {
        $a = array();
        $requete = "SELECT `id`,
                           `name`
                      FROM `group`
                  ORDER BY `name`";
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $a[]=array( "id" => $res->id,
                    "name" => $res->name
                     );
        }
        return $a;
    }
    private function all_users()
    {
        $a = array();
        $requete = "SELECT `id`,
                           `name`,
                           `firstname`
                      FROM `user`
                  ORDER BY `name`,
                           `firstname`";
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $a[]=array( "id" => $res->id,
                        "name" => $res->name,
                        "firstname" => $res->firstname );
        }
        return $a;
    }
    public function list_group()
    {
        return $this->jqGrid();
    }
    public function valid_group($post)
    {

        $requete = "SELECT *
                      FROM `group`
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
    public function edit_group($post)
    {
        load_alternative_class('class/group.class.php');
        $u = new group($this->db);
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
            if ($res) $this->message = _("Opération effectuée");
            else $this->message = _("Opération echouée");
            $this->db->commit();
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
                    $res = $u->set_active(0);
                    if(!$res) $error = true;
                } elseif ($key=='active' && $val=='on')
                {
                    $res = $u->set_active(1);
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
                      FROM `group`
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
                      FROM `group`
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

        while($res = $this->db->fetch_object($sql))
        {
            $this->responce->rows[$iter]['id']=$res->id;
            $avatar_path = $res->avatar_path;
            if ($res->avatar_path."x" == "x" )
            {
                global $conf;
                $avatar_path = $conf->main_url_root.(preg_match('/\/$/', $conf->main_url_root)?"":"/")."img/avatars/avatar-default.png";
            }
            $this->responce->rows[$iter]['cell']=array($res->id,$res->name,$res->email,$res->description,$avatar_path,$res->active);
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
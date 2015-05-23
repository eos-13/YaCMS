<?php
class model_internal_msg extends admin_common
{
    public $id;
    public $responce;
    public $message=false;

    public function run()
    {
        return (true);
    }
    public function list_internal_msg()
    {
        return $this->jqGrid();
    }
    public function get_content($post)
    {
        $html = "";
        if ($post["id"] && $post["id"] > 0)
        {
            load_alternative_class('class/internal_msg.class.php');
            $internal_msg = new internal_msg($this->db);
            $internal_msg->fetch($post["id"]);
            $requete = "SELECT title,
                               internal_msg,
                               date_create
                          FROM internal_msg
                         WHERE parents_refid = ".$post["id"]." OR id = ".$post["id"]."
                      ORDER BY date_create DESC";
            $sql = $this->db->query($requete);
            $iter=0;
            $html = "";
            while ($res = $this->db->fetch_object($sql))
            {
                $html .= "<table width=100% CELLPADDING=10 border=1 style='border-collapse:collapse;'>";
                if ($iter == 0)
                {
                    $html .= "<tr><th>Sujet</th>";
                    $html .= "<td> ".$res->title."</td></tr>";
                    $html .= "<tr><td style='padding:3px;' colspan=2></td></tr>";
                }
                $html .= "<th colspan=1 width= 10%>Le:</th><td>".convert_time_us_to_fr($res->date_create)."</td>";
                $html .= "<tr><th colspan=2>Contenu:</th>";
                $html .= "<tr><td colspan=2>".$res->internal_msg."</td>";
                $html .= "</table>";
                $html .= "<br/>";
                $iter++;
            }
            $html .= "<table width=100% CELLPADDING=10 border=1 style='border-collapse:collapse;'>";
            $html .= "<tr><th>Réponse</th></tr>";
            $html .= "<tr><td>";
            $html .= "<textarea placeholder='Votre réponse' id='answer' name='answer'></textarea>";
            $html .= "</td></tr>";
            $html .= "<tr><td>";
            $html .= "<button id ='sendResponse'>Envoyer</button>";
            $html .= "</td></tr>";
            $html .= "</table>";
            if ($internal_msg->get_internal_msg_status_refid() < 2)
                $toto = $internal_msg->set_internal_msg_status_refid(2);
        }
        return $html;
    }
    public function cloture($post)
    {
        if ($post["id"]> 0)
        {
            load_alternative_class('class/internal_msg.class.php');
            $internal_msg = new internal_msg($this->db);
            $internal_msg->fetch($post["id"]);
            $internal_msg->set_internal_msg_status_refid(3);
            $this->message = "Cloturer";
        }
    }
    public function send_reponse($post)
    {
        if ($post["id"]> 0)
        {
            load_alternative_class('class/internal_msg.class.php');
            $internal_msg = new internal_msg($this->db);
            $internal_msg->respond($post["id"],$post['data']);
            $this->message = "Réponse envoyée";
        }
    }

    private function jqGrid()
    {
        $page = $_REQUEST['page']; // get the requested page
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. internal_msg click to sort
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
                      FROM internal_msg
                     WHERE 1=1 ";
        $requete .= " AND parents_refid IS NULL ";
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

$requete = "SELECT internal_msg.title,
                   internal_msg.id,
                   internal_msg_status.name as status,
                   internal_msg_status.id as status_id,
                   internal_msg_type.name as type,
                   user.firstname,
                   DATE_FORMAT(internal_msg.date_create,'%Y-%m-%d' )as date_create_group,
                   user.name,
                   internal_msg.date_create
              FROM internal_msg,
                   internal_msg_type,
                   internal_msg_status,
                   user
             WHERE 1=1 ";
$requete .= " AND internal_msg_type_refid = internal_msg_type.id
              AND internal_msg_status_refid = internal_msg_status.id
              AND user_refid = user.id
              AND parents_refid is NULL ";
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
            $this->responce->rows[$iter]["id"]=$res->id;
            $this->responce->rows[$iter]['cell']=array($res->id,$res->title,$res->status,$res->type,$res->name,$res->firstname,$res->date_create,$res->date_create_group,$res->status_id);
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
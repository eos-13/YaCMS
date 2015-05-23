<?php
class model_external_msg extends admin_common
{
    public $id;
    public $responce;
    public $message=false;

    public function run()
    {
        return (true);
    }
    public function list_external_msg()
    {
        return $this->jqGrid();
    }
    public function get_content($post)
    {
        $html = "";
        if ($post["id"] && $post["id"] > 0)
        {
            load_alternative_class('class/external_msg.class.php');
            $external_msg = new external_msg($this->db);
            $external_msg->fetch($post["id"]);
            $requete = "SELECT title,
                               external_msg,
                               date_create
                          FROM external_msg
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
                $html .= "<tr><td colspan=2>".strip_tags($res->external_msg)."</td>";
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
            if ($external_msg->get_status()<2)
                $toto = $external_msg->set_status(2);
        }
        return $html;
    }
    public function send_reponse($post)
    {
        load_alternative_class('class/external_msg.class.php');
        if ($post["id"]> 0)
        {
            global $conf,$current_user;
            $msg = "";
            if ($conf->external_message_model>0)
            {
                //Gen Mail model h2o
                load_alternative_class("class/mail_model.class.php");
                $mm = new mail_model($this->db);
                $mm->fetch($conf->external_message_model);
                $from_id = $current_user->get_id();
                if ( $conf->user_id_from_external_msg > 0)
                {
                    $from_id = $conf->user_id_from_external_msg;
                }

                $a = $mm->prepare_mail_external($from_id,$post['data']);
                $msg = $a['content'];
            } else {
                $msg = $post['data'];
            }
            //Store It
            $external_msg = new external_msg($this->db);
            $external_msg->fetch($post['id']);
            $external_msg->insert("internal", $msg, $external_msg->get_title(),3,$external_msg->get_id());
            load_alternative_class('class/mail.class.php');
            $mail = new mail();
            if ($conf->user_id_from_external_msg > 0)
            {
                $u = new user($this->db);
                $u->fetch($conf->user_id_from_email);
            } else {
                $u = $current_user;
            }
            $mail->send_email($external_msg->get_title(), $msg, $external_msg->get_user_mail(), $u->get_email());
            $external_msg->set_status(3);
            $this->message=_("Mail envoyé");
        }
    }

    private function jqGrid()
    {
        $page = $_REQUEST['page']; // get the requested page
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. external_msg click to sort
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
                      FROM external_msg
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

        $requete = "SELECT external_msg.title,
                           external_msg.id,
                           external_msg.status as status,
                           DATE_FORMAT(external_msg.date_create,'%Y-%m-%d' )as date_create_group,
                           user_mail,
                           external_msg.date_create
                      FROM external_msg
                     WHERE 1=1 ";
        $requete .= " AND parents_refid is NULL ";
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
            $this->responce->rows[$iter]['cell']=array($res->id,$res->title,$res->status,$res->user_mail,$res->date_create,$res->date_create_group);
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
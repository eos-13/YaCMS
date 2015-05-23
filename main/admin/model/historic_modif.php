<?php
class model_historic_modif extends admin_common
{
    public $id;
    public $responce;
    public $message = false;

    public function run()
    {
        return (true);
    }
    public function list_historic_modif()
    {
        return $this->jqGrid();
    }
    public function extra_datas($id)
    {
        $requete = "SELECT old_value,
                           new_value
                      FROM log_modif_page
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        $a = array();
        $a['html'] = "<table style='table-layout: fixed;' width='100%' ><tr><th width='50%' style='max-width:300px;'>Old</th><th width='50%'  style='max-width:300px;'>New</th></tr>";
        while ($res = $this->db->fetch_object($sql))
        {
            $a['html'] .= "<tr>";
            $a['html'] .= "    <td style='word-wrap:break-word; text-overflow:ellipsis;overflow:scroll;white-space:nowrap;'>";
            $a['html'] .= "        <div style='word-wrap: break-word; white-space: nowrap; text-overflow: ellipsis; overflow: auto;'>";
            $a['html'] .= "            <xmp>".$res->old_value ."</xmp>";
            $a['html'] .= "        </div>";
            $a['html'] .= "    </td>";
            $a['html'] .= "    <td style='word-wrap:break-word; text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'>";
            $a['html'] .= "        <div style='word-wrap: break-word; white-space: nowrap; text-overflow: ellipsis; overflow: auto;'>";
            $a['html'] .= "            <xmp>".$res->new_value."</xmp>";
            $a['html'] .= "        </div>";
            $a['html'] .= "    </td>";
            $a['html'] .= "</tr>";
        }
        $a['html'] .= "</table>";

        return $a;
    }


    private function jqGrid()
    {
        $page = $_REQUEST['page']; // get the requested page
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. historic_modif click to sort
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
                      FROM log_modif_page
                     WHERE 1=1 ";
        if ($_search == "true") {
            $requete .= " AND ". $searchField. " ".$searchOper." '".$searchString."'";
        }
        $requete .= " AND page_refid = ".$this->page_refid;

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

        $requete = "SELECT user.name as user_name,
                           user.firstname as user_firstname,
                           log_modif_page.date_modif,
                           log_modif_page.type_modif,
                           log_modif_page.id
                      FROM log_modif_page,
                           user
                     WHERE 1=1
                       AND user.id = log_modif_page.user_refid ";
        if ($_search == "true")
        {
            $requete .= " AND ". $searchField. " ".$searchOper." '".$searchString."'";
        }
        $requete .= " AND page_refid = ".$this->page_refid;
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
            $this->responce->rows[$iter]['cell']=array($res->id,$res->user_name,$res->user_firstname,$res->date_modif,$res->type_modif);
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
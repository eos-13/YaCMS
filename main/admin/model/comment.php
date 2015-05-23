<?php
class model_comment extends admin_common
{
    public $id;
    public $responce;
    public $message = false;

    public function run()
    {
        return (true);
    }
    public function list_comment()
    {
        return $this->jqGrid();
    }
    public function edit($post)
    {
        load_alternative_class('class/comment.class.php');
        $c = new comment($this->db);
        $id = $post['id'];
        if (!$id) {
            return false;
        }
        $c->fetch($id);
        if ($post['oper'] == 'del')
        {
            $error = $c->del();
            return json_encode($error?_("OK"):_("KO"));
        }
        if ($post['action'] == 'validate')
        {
            $error = $c->set_valid(2);
            return json_encode($error?_("OK"):_("KO"));
        }

        $this->db->commit();
        return json_encode(_("OK"));
    }
    public function detail($post)
    {
        load_alternative_class('class/comment.class.php');
        $c = new comment($this->db);
        if ($c->fetch($post['id']))
        {
            $html = "<table>";
            $html .= "<tr><th width=100>Author</th><td>".$c->get_author_html()."</td></tr>";
            $html .= "<tr><th>Title</th><td>".$c->get_title()."</td></tr>";
            $html .= "<tr><th valign=top>Commentaire</th><td>".$c->get_content()."</td></tr>";
            $html .= "</table>";
            return $html;
        } else {
            return "<div class='error'>Erreur détéctée</div>";
        }
    }
    private function jqGrid()
    {
        $page = $_REQUEST['page']; // get the requested page
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. comment click to sort
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
        global $conf;

        $requete = "SELECT COUNT(*) AS count
                      FROM commentaire,
                           page
                     WHERE 1=1
                       AND page.id = commentaire.page_refid ";
        if ($_search == "true") {
            $requete .= " AND ". $searchField. " ".$searchOper." '".$searchString."'";
        }
        if ($conf->moderation_type == "on")
        {
            $requete .= " AND valid = 0 ";
        } else {
            $requete .= " AND valid = 1 ";
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

        $requete =  "SELECT commentaire.id,
                           commentaire.author,
                           commentaire.author_id,
                           page.url,
                           commentaire.title,
                           commentaire.content,
                           commentaire.valid
                      FROM commentaire,
                           page
                     WHERE 1=1
                       AND page.id = commentaire.page_refid ";
        if ($_search == "true") {
            $requete .= " AND ". $searchField. " ".$searchOper." '".$searchString."'";
        }
        if ($conf->moderation_type == "on")
        {
            $requete .= " AND valid = 0 ";
        } else {
            $requete .= " AND valid = 1 ";
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
            $author = $res->author;
            if ($res->author_id)
            {
                $u = new user($this->db);
                $u->fetch($res->author_id);
                $author = $u->get_firstname()." ".$u->get_name();
            }
            $this->responce->rows[$iter]['id']=$res->id;
            $this->responce->rows[$iter]['cell']=array($res->id,$author,$res->url,$res->title,$res->content,$res->valid);
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
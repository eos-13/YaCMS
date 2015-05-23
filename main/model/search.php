<?php
class model_search extends common
{
    public function run()
    {
    }
    public function enrich_response($ret,$search_terms)
    {
        foreach($ret as $key=>$val)
        {
            load_alternative_class('class/page.class.php');
            load_alternative_class('class/sphinx.class.php');
            $sp = new Sphinx();
            $p = new page($this->db);
            $p->fetch($val['id']);
            $a = $sp->snippet($p, $search_terms,10);
            $a = preg_replace('/\.\.\./',"",$a);
            $a = preg_replace('/^ */',"",$a);
            $a = preg_replace('/ *$/',"",$a);
            if ($a."x" == "x") continue;
            $results[]=$a;
        }
        $results = array_unique($results);
        return $results;
    }
    public function search($p)
    {
        //Search all page accessible
        global $current_user;
        $requete = "SELECT DISTINCT page_refid
                      FROM group_publication_page,
                           group_publication
                     WHERE group_publication.id = group_publication_page.`group_publication_refid`
                       AND ( group_publication.is_public=1
                        OR group_publication.id IN (select group_publication_refid
                                                      from group_publication_user
                                                     WHERE user_refid = ".$current_user->get_id()." ))";
        $sql = $this->db->query($requete);
        $a = array();
        while($res=$this->db->fetch_object($requete))
        {
            $a[] = (int) $res->page_refid;
        }
        $where['col'] = 'page_id';
        $where['ope'] = 'IN';
        $where['data'] = $a;
        global $conf;
        $s_term = preg_split('/ /',$p);
        $s = array('content' => $s_term,
                'meta' => $s_term,
                'title' => $s_term,
                'keyword' => $s_term
        );
        load_alternative_class('class/sphinx.class.php');
        $sp = new Sphinx();
        $sp->set_match_any_char(true);

        $result = $sp->query(array('id'),$s,$where, 0 ,10,false);
        $total = $sp->count_result($s);
        return array('result' => $result, "total" => $total);
    }

}
?>
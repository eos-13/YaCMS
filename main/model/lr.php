<?php
class model_lr extends common
{
    public function run()
    {
    }
    public function search($post)
    {
        global $current_user;
        $requete = false;
        if (!$current_user || (! $current_user->get_id() || $current_user->get_id() > 0))
        {
            $requete = "SELECT DISTINCT page_refid
                                   FROM group_publication_page,
                                        group_publication
                                  WHERE group_publication.id = group_publication_page.`group_publication_refid`
                                    AND group_publication.is_public=1";
        } else {
            $requete = "SELECT DISTINCT page_refid
                          FROM group_publication_page,
                               group_publication
                         WHERE group_publication.id = group_publication_page.`group_publication_refid`
                           AND ( group_publication.is_public=1
                            OR group_publication.id IN (select group_publication_refid
                                                          from group_publication_user
                                                         WHERE user_refid = ".$current_user->get_id()." ))";
        }
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
        $s_term = preg_split('/ /',$post['s']);
        $s = array(
                'content' => $s_term,
                'meta' => $s_term,
                'title' => $s_term,
                'keyword' => $s_term
        );

        load_alternative_class('class/sphinx.class.php');
        $sp = new Sphinx();
        $sp->set_match_any_char(true);
        $p = (isset($post['p'])?$post["p"]:1);
        $result = $sp->query(array('id'), $s, $where, ( $p - 1 ) * $conf->liste_limit, $conf->liste_limit, false);
        $total = $sp->count_result($s);
        return array(
                'result' => $result,
                "total" => $total
        );
    }
    public function word_cloud($p)
    {
        load_alternative_class('class/sphinx.class.php');
        global $conf;
        if ($p->get_keyword()."x" =="x" || preg_match('/^\ *$/',$p->get_keyword()))
            return false;
        $keywords = $p->get_keyword();
        $keywords = preg_replace('/\ ?,\ ?/',',',$keywords);
        $s_term = preg_split('/,/',$keywords);
        $s = array(
                'content' => $s_term,
                'meta' => $s_term,
                'title' => $s_term
        );
        $sp = new Sphinx();
        $sp->set_match_any_char(true);
        $result = $sp->query(array('id'),$s,false,1 ,$conf->liste_limit,false);
        $meta = $sp->get_meta();
        $arr = array();
        $iter = 0;
        $tmpArr = array();
        foreach ($s_term as $key => $val)
        {
            if (!strlen($val) > 0) continue;
            $s = array(
                    'content' => array($val),
                    'meta' => array($val),
                    'title' => array($val)
            );
            $tmp_res = $sp->query(array('id'),$s,false,1 ,$conf->liste_limit,false);
            $tmp_meta = $sp->get_meta();
            foreach ($tmp_meta as $key1=>$val1)
            {
                if (preg_match('/keyword\[[0-9]*\]/',$val1['Variable_name']))
                {
                    $tmpArr[$val][]=$val1['Value'];
                }
            }
        }
        foreach ($meta as $key => $val)
        {
            if (preg_match('/keyword\[([0-9]*)\]/',$val['Variable_name'],$arrMatch))
            {
                $value = 1;
                foreach($meta as $key1 => $val1)
                {
                    if ($val1['Variable_name'] == 'hits['.$arrMatch[1].']')
                    {
                        $value = $val1['Value'];
                        break;
                    }
                }

                foreach($tmpArr as $key1=>$val1)
                {
                    foreach($val1 as $key2=>$val2)
                    {
                        if ($val['Value'] == $val2)
                        {
                            $found = false;
                            foreach($arr as $key3=>$val3)
                            {
                                if ($val3['text'] == $key1)
                                {
                                    $found = true;
                                    if ($val3['weight'] < $value)
                                    {
                                        $arr[$key3]['weight']=$value;
                                    }
                                }
                            }
                            if (!$found)
                            {
                                $arr[] = array(
                                        'text' => $key1,
                                        'weight' =>  $value,
                                        'link' => 'lr?s='.$key1
                                );
                            }
                        }
                    }
                }
                $iter++;
            }
        }
        return array('meta'=>json_encode($arr));
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
            $meta_cloud = $this->word_cloud($p);
            $p->set_description_sphinx($sp->snippet($p, $search_terms));
            $p->set_meta_cloud($meta_cloud['meta']);
            $results[]=$p->get_all();
        }
        return $results;
    }
}
?>
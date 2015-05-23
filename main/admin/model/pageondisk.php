<?php
class model_pageondisk extends admin_common
{
    public $id = false;
    public $message = false;

    //lister
    public function run()
    {
        $array["allpages"] = $this->get_datas();
        return $array;
    }
    private function get_datas()
    {
        $html = "";
        $a = $this->enrich($this->list_page());
        foreach($a as $key => $val)
        {
            $html.= $this->make_html($val);
        }
        return $html;
    }
    private function make_html($data)
    {
        if ($this->id == $data['id'])
            return "<option value='".$data['id']."' SELECTED>".$data['name']."</option>";
        else
            return "<option value='".$data['id']."'>".$data['name']."</option>";
    }
    private function list_page()
    {
        global $conf;
        $array = array();
        foreach(array('main','customer') as $path_root)
        {
            $path = $path_root."/view";
            if (is_dir($conf->main_document_root."/". $path))
            {
                $dir = new DirectoryIterator($conf->main_document_root."/". $path);
                foreach ($dir as $fileinfo)
                {
                    if (!$fileinfo->isDot()
                             && $fileinfo->getFilename() != "home.php"
                             && $fileinfo->getFilename() != "index.php"
                             && $fileinfo->getFilename() != "bdd.php"
                             && !preg_match('/^\./',$fileinfo->getFilename())
                             && preg_match('/\.php$/',$fileinfo->getFilename())
                    )
                    {
                        $file = preg_replace('/.php$/','',$fileinfo->getFilename());
                        $array[$file]=$file;
                    }
                }
            }
        }
        return $array;
    }
    private function enrich($arr)
    {
        $ret = array();
        foreach($arr as $val)
        {
            $tmp["name"] = $val;
            $tmp["url"] = $val;
            $tmp["id"] = $val;
            $tmp['is_referenced'] = 0;
            $requete = "SELECT *
                          FROM page
                         WHERE url = '".addslashes($val)."'
                           AND page_on_disk = 1";
            $sql = $this->db->query($requete);
            if ($sql && $this->db->num_rows($sql) > 0)
            {
                $res = $this->db->fetch_object($sql);
                $tmp['url'] = $res->url;
                $tmp['id'] = $res->id;
                $tmp['in_menu'] = $res->in_menu;
                $tmp['exclude_sitemap'] = $res->exclude_sitemap;
                $tmp['changeFreq_refid'] = $res->changeFreq_refid;
                $tmp['priority'] = $res->priority;
                $tmp['keyword'] = $res->keyword;
                $tmp['exclude_search'] = $res->exclude_search;
                $tmp['generated_formated_content'] = $res->generated_formated_content;
                $tmp['parent_refid'] = $res->parent_refid;
                $tmp['is_referenced'] = 1;
            }
            $ret[]=$tmp;
        }
        return $ret;
    }
    public function get_data($post)
    {
        $a = $this->enrich($this->list_page());
        foreach($a as $key=>$val)
        {
            if ($val['id'] == $post['id'])
            {
                if ($val['is_referenced'] == 1)
                {
                    return $this->make_html_referenced($val);
                } else {
                    return $this->make_html_unreferenced($val);
                }
            }
        }
    }
    private function make_html_referenced($data)
    {
        $html="<form id='form_ondisk' action='pageondisk?action=update&id=".$data['id']."' method='post'>";
        $html .= "<table style='border-collapse: collapse;' width='100%' CELLPADDING='10'>";
        $html .= "<tr class='background-td-even'><th width='200' style='min-width:200px;'>URL</th><td>";
        $html .= $data['url'];
        $html .= "</td></tr>";
        $html .= "<tr class='background-td-odd'><th width='200' style='min-width:200px;'>Reindexe la page (search engine)</th><td>";
        $html .= "<button id='reindex' value='". $data['url']."'>Reindexation</button>";
        $html .= "</td></tr>";
        $html .= "<tr  class='background-td-even'><th>Inclus dans le menu</th><td>";
        if ($data['in_menu'] == 1)
        {
            $html .= "<input name='in_menu' type='checkbox' CHECKED />";
        } else {
            $html .= "<input name='in_menu' type='checkbox' />";
        }

        $html .= "</td></tr>";
        $html .= "<tr class='background-td-odd'><th>Exclure du sitemap</th><td>";
        if ( $data['exclude_sitemap'] == 1)
        {
            $html .= "<input name='exclude_sitemap' type='checkbox' checked />";
        } else {
            $html .= "<input name='exclude_sitemap' type='checkbox' />";
        }
        $html .= "</td></tr>";
        $html .= "<tr  class='background-td-even'><th>Fréquence sitemap</th><td>";
        $html .= "<select name='changeFreq_refid'>";
        $html .= "<option value='0'>Select-></option>";
        $requete = "SELECT *
                      FROM sitemapFreq ";
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            if ($data['changeFreq_refid'] == $res->id)
            {
                $html .= "<option SELECTED value='".$res->id."'>".$res->name."</option>";
            } else {
                $html .= "<option value='".$res->id."'>".$res->name."</option>";
            }

        }
        $html .= "</select>";
        $html .= "</td></tr>";
        $html .= "<tr class='background-td-odd'><th>Priorité sitemap</th><td>";
        $html .='<span style= "float:left;" id="slider_info_priority"></span><div style="margin-left: 2em;" id="slider_priority" ></div>';
        $html.= '<input type="hidden" id="priority" name="priority" value="'.$data['priority'].'">';

        $html .= '<script>jQuery(document).ready(function(){';
        $html .= 'jQuery("#slider_priority").slider({';
        $html .= ' value:'. ( $data['priority']?$data['priority']:0.5 ) .',';
        $html .= ' min: 0,';
        $html .= ' max: 1.05,';
        $html .= ' step: 0.1,';
        $html .= ' slide: function( event, ui ) {';
        $html .= '    jQuery( "#priority" ).val(  ui.value );';
        $html .= '    jQuery( "#slider_info_priority" ).text(  ui.value );';
        $html .= ' }';
        $html .= '});';
        $html .= ' jQuery( "#priority" ).val( jQuery( "#slider_priority" ).slider( "value" ) );';
        $html .= ' jQuery( "#slider_info_priority" ).text( jQuery( "#slider_priority" ).slider( "value" ) );';
        $html .= '});</script>';

        $html .= "</td></tr>";
        $html .= "<tr  class='background-td-even'><th>Mots clefs</th><td>";
        $html .= "<textarea name='keyword'>".$data['keyword']."</textarea>";
        $html .= "</td></tr>";
        $html .= "<tr class='background-td-odd'><th>Exclure de la recherche</th><td>";
        if ($data['exclude_search'] == 1)
        {
            $html .= "<input name='exclude_search' type='checkbox' CHECKED />";
        } else {
            $html .= "<input name='exclude_search' type='checkbox' />";
        }
        $html .= "</td></tr>";
        $html .= "<tr  class='background-td-even'><th>Page parente</th><td>";
        $html .= "<select name='parent_refid'>";
        $html .= "<option value='0'>Select-></option>";
        $requete = "SELECT *
                      FROM page
                     WHERE id <> '".$data['id']."'
                  ORDER BY title";
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            if ($res->id == $data['parent_refid'])
                $html .= "<option SELECTED value='".$res->id."'>".$res->title."</option>";
            else
                $html .= "<option value='".$res->id."'>".$res->title."</option>";
        }
        $html .= "</select>";
        $html .= "</tr></table>";
        $html .= "<button>Sauvegarder</button><button id='del'>Supprimer</button>";
        $html .= "</form>";
        return $html;
    }
    private function make_html_unreferenced($data)
    {
        $html="<form action='pageondisk' method='POST'>";
        $html.= "<input type='hidden' name='action' value='reference'/>";
        $html.= "<input type='hidden' name='id' value='".$data['id']."'/>";
        $html .= "<button>Référence la page</button>";
        $html .= "</form>";
        return $html;
    }
    public function reference($data)
    {
        $requete = "INSERT INTO page
                                (title,in_menu,url,active,page_on_disk,date_creation)
                         VALUES ('".addslashes($data['id'])."',0,'".addslashes($data['id'])."',1,1,now())";
        $sql = $this->db->query($requete);
        if ($sql) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $this->db->last_insert_id('page');
    }
    public function update($post)
    {
        $error = false;
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($post['id']);
        if (isset($post['in_menu']) && $post['in_menu'] == "on")
        {
            $res = $p->set_in_menu(1);
            if (!$res) $error = true;
        } else {
            $res = $p->set_in_menu("0");
            if (!$res) $error = true;
        }
        if (isset($post['exclude_sitemap']) && $post['exclude_sitemap'] == "on")
        {
            $res = $p->set_exclude_sitemap(1);
            if (!$res) $error = true;
        } else {
            $res = $p->set_exclude_sitemap("0");
            if (!$res) $error = true;
        }

        if ($post['changeFreq_refid'] > 0)
        {
            $res = $p->set_changeFreq_refid($post['changeFreq_refid']);
            if (!$res) $error = true;
        } else {
            $res = $p->set_changeFreq_refid("null");
            if (!$res) $error = true;
        }
        $res = $p->set_priority($post['priority']);
        if (!$res) $error = true;
        $res = $p->set_keyword($post['keyword']);
        if (!$res) $error = true;
        if (isset($post['exclude_sitemap']) && $post['exclude_sitemap'] == "on")
        {
            $res = $p->set_exclude_search(1);
            if (!$res) $error = true;
        } else {
            $res = $p->set_exclude_search("0");
            if (!$res) $error = true;
        }
        if ($post['parent_refid'] > 0)
        {
            $res = $p->link($post['id'],$post['parent_refid']);
            if (!$res) $error = true;
        } else {
            $res = $p->link($post['id'],"null");
            if (!$res) $error = true;
        }
        $p->refresh_sitemap();
        $res = $p->update_sphinx();
        if (!$res) $error = true;
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function reindex($post)
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($post['id']);
        $res = $p->update_sphinx();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function del($post)
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($post['id']);
        $res = $p->del();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function transform_id($id)
    {
        $requete = "SELECT *
                          FROM page
                         WHERE id = '".addslashes($id)."'
                           AND page_on_disk = 1";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            return $res->id;
        }
        $requete = "SELECT *
                          FROM page
                         WHERE url = '".addslashes($id)."'
                           AND page_on_disk = 1";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            return $res->id;
        }
        return $id;
    }
}
?>
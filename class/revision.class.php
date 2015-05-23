<?php
load_alternative_class('class/page.class.php');
class revision extends page
{
    protected $table = "page_revision";

    private $pId;
    private $pTitle;
    private $pUrl;
    private $pContent;
    private $pInmenu;
    private $pParentrefid;
    private $pModelrefid;
    private $pMeta;
    private $pJs;
    private $pJs_code;
    private $pCss;
    private $pCss_code;
    private $pActive;
    private $pDate_creation;
    private $pDate_derniere_maj;
    private $pChangeFreq_refid;
    private $pPriority;
    private $pExclude_sitemap;
    private $pExclude_search;
    private $pContent_with_section;
    private $pSection_content;
    private $pKeyword;
    private $pGenerated_formated_content;
    private $pPage_on_disk;
    private $pPermit_comment;
    private $pJs_external;
    private $pAuthor_refid;
    private $pExtra_params;
    private $pIs_locked_for_edition;
    private $pSocial_media;
    private $pPlugins;
    private $pUse_cache;
    private $pPublication_status_refid;
    private $pIs_a_draft_for;
    private $pLast_modif_by;
    private $pLang;
    private $pForm_refid;

    private $pPage_refid;

    private $pCacheSection_content=false;
    private $pDescription_sphinx="";
    private $pMeta_cloud;
    private $pSection=array();
    private $pCacheSection=false;

    public $page_refid;

    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $id;
            $this->id = $id;
            $this->pTitle = $res->title;
            $this->pUrl = $res->url;
            $this->pContent = $res->content;
            $this->pInmenu = $res->in_menu;
            $this->pParentrefid = $res->parent_refid;
            $this->pModelrefid = $res->model_refid;
            $this->pMeta = $res->meta;
            $this->pJs = json_decode($res->js);
            $this->pJs_code = $res->js_code;
            $this->pCss = json_decode($res->css);
            $this->pCss_code = $res->css_code;
            $this->pActive = $res->active;
            $this->pDate_creation = $res->date_creation;
            $this->pDate_derniere_maj = $res->date_derniere_maj;
            $this->pChangeFreq_refid = $res->changeFreq_refid;
            $this->pPriority = $res->priority;
            $this->pExclude_sitemap = $res->exclude_sitemap;
            $this->pExclude_search = $res->exclude_search;
            $this->pContent_with_section = $res->content." ". $this->get_section_content();
            $this->pSection_content = $this->get_section_content();
            $this->pKeyword = $res->keyword;
            $this->pGenerated_formated_content = $res->generated_formated_content;
            $this->pPage_on_disk = $res->page_on_disk;
            $this->pPermit_comment = $res->permit_comment;
            $this->pJs_external = $res->js_external;
            $this->pAuthor_refid = $res->author_refid;
            $this->pExtra_params = $res->extra_params;
            $this->pIs_locked_for_edition = $res->is_locked_for_edition;
            $this->pSocial_media = $res->social_media;
            $this->pPlugins = $res->plugins;
            $this->pUse_cache = $res->use_cache;
            $this->pPublication_status_refid = $res->publication_status_refid;
            $this->pIs_a_draft_for = $res->is_a_draft_for;
            $this->pLast_modif_by = $res->last_modif_by;
            $this->pLang = $res->lang;
            $this->pForm_refid = $res->form_refid;

            $this->pPage_refid = $res->page_refid;

            return $res->id;
        } else {
            return false;
        }
    }
    public function get_section_content()
    {
        if (! $this->pCacheSection_content)
        {
            $requete = "SELECT id
                          FROM section_revision
                         WHERE active = 1
                           AND page_revision_refid = ".$this->get_id(). "
                      ORDER BY `order`";
            $sql1 = $this->db->query($requete);
            $tmp = "";
            if ($sql1 && $this->db->num_rows($sql1) > 0)
            {
                load_alternative_class('class/section_revision.class.php');
                $sect = new section_revision($this->db);
                while($res1 = $this->db->fetch_object($sql1))
                {
                    $sect->fetch($res1->id);
                    $tmp.="<section>".$sect->get_content()."</section>";
                }
            }
            $this->pCacheSection_content = $tmp;
            $this->pSection_content = $tmp;
            return ($tmp);
        } else {
            $this->pSection_content = $this->pCacheSection_content;
            return $this->pCacheSection_content;
        }
    }
    public function del()
    {
        if ($this->get_id()>0)
        {
            $this->db->begin();
            $requete = "DELETE FROM section_revision
                              WHERE page_revision_refid = ".$this->get_id();
            $sql1 = $this->db->query($requete);
            $requete = "DELETE FROM ".$this->table ."
                              WHERE id = ".$this->get_id();
            $sql = $this->db->query($requete);
            if ($sql1 && $sql)
            {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollback();
                return false;
            }
        } else {
            return false;
        }

    }
    public function get_all_section()
    {
        if($this->pId >0)
        {
            $requete = "SELECT id
                          FROM section_revision
                         WHERE page_revision_refid = ".$this->pId."
                      ORDER BY `order`";
            $sql1 = $this->db->query($requete);
            $tmp = array();
            if ($sql1 && $this->db->num_rows($sql1) > 0)
            {
                load_alternative_class('class/section_revision.class.php');
                $sect = new section_revision($this->db);
                while($res1 = $this->db->fetch_object($sql1))
                {
                    $sect->fetch($res1->id);
                    $tmp[]=array('title' => $sect->get_title(),
                            'content' =>  $sect->get_content(),
                            'id' => $sect->get_id(),
                            'active' => $sect->get_active(),
                            'associated_img' => $sect->get_associated_img()
                    );
                }
            }
            return $tmp;
        } else {
            return false;
        }
    }
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->pId;
            $this->title = $this->pTitle;
            $this->url = $this->pUrl;
            $this->content = $this->pContent;
            $this->content_with_section = $this->pContent_with_section;
            $this->section_content = $this->pSection_content;
            $this->inmenu = $this->pInmenu;
            $this->parentrefid = $this->pParentrefid;
            $this->modelrefid = $this->pModelrefid;
            $this->meta = $this->pMeta;
            $this->js = $this->pJs;
            $this->js_external = $this->pJs_external;
            $this->js_code = $this->pJs_code;
            $this->css = $this->pCss;
            $this->css_code = $this->pCss_code;
            $this->active = $this->pActive;
            $this->date_creation = $this->pDate_creation;
            $this->date_derniere_maj = $this->pDate_derniere_maj;
            $this->changeFreq_refid = $this->pChangeFreq_refid;
            $this->priority = $this->pPriority;
            $this->exclude_sitemap = $this->pExclude_sitemap;
            $this->exclude_search = $this->pExclude_search;
            $this->keyword = $this->pKeyword;
            $this->generated_formated_content = $this->pGenerated_formated_content;

            $this->description_sphinx = $this->pDescription_sphinx;
            $this->meta_cloud = $this->pMeta_cloud;
            $this->page_on_disk = $this->pPage_on_disk;
            $this->permit_comment = $this->pPermit_comment;
            $this->js_external = $this->pJs_external;
            $this->author_refid = $this->pAuthor_refid;
            $this->extra_params = $this->pExtra_params;
            $this->is_locked_for_edition = $this->pIs_locked_for_edition;
            $this->social_media = $this->pSocial_media;
            $this->plugins = $this->pPlugins;
            $this->use_cache = $this->pUse_cache;
            $this->publication_status_refid = $this->pPublication_status_refid;
            $this->is_a_draft_for = $this->pIs_a_draft_for;
            $this->last_modif_by = $this->pLast_modif_by;
            $this->page_refid = $this->pPage_refid;
            $this->lang = $this->pLang;
            $this->form_refid = $this->pForm_refid;

            return $this;
        } else {
            return false;
        }
    }
    public function get_id()
    {
        if ($this->pId > 0)
            return $this->pId;
        else
            return false;
    }
    public function get_date_creation()
    {
        if ($this->get_id() > 0)
            return $this->pDate_creation;
        else
            return false;
    }
    public function get_date_derniere_maj()
    {
        if ($this->get_id() > 0)
            return $this->pDate_derniere_maj;
        else
            return false;
    }

    public function get_title()
    {
        if ($this->get_id() > 0)
            return $this->pTitle;
        else
            return false;
    }
    public function get_url()
    {
        if ($this->get_id() > 0)
            return $this->pUrl;
        else
            return false;
    }
    public function get_model_refid()
    {
        if ($this->get_id() > 0)
            return $this->pModelrefid;
        else
            return false;
    }
    public function get_content()
    {
        if ($this->get_id() > 0 && !empty($this->pContent))
            return $this->pContent;
        else
            return false;
    }
    public function get_in_menu()
    {
        if ($this->get_id() > 0)
            return $this->pInmenu;
        else
            return false;
    }
    public function get_parent_refid()
    {
        if ($this->get_id() > 0)
            return $this->pParentrefid;
        else
            return false;
    }
    public function get_meta()
    {
        if ($this->get_id() > 0)
            return $this->pMeta;
        else
            return false;
    }
    public function get_js()
    {
        if ($this->get_id() > 0)
            return $this->pJs;
        else
            return false;
    }
    public function get_js_external()
    {
        if ($this->get_id() > 0)
            return $this->pJs_external;
        else
            return false;
    }
    public function get_js_code()
    {
        if ($this->get_id() > 0)
            return $this->pJs_code;
        else
            return false;
    }
    public function get_css()
    {
        if ($this->get_id() > 0)
            return $this->pCss;
        else
            return false;
    }
    public function get_css_code()
    {
        if ($this->get_id() > 0)
            return $this->pCss_code;
        else
            return false;
    }
    public function get_active()
    {
        if ($this->get_id() > 0)
            return $this->pActive;
        else
            return false;
    }
    public function get_priority()
    {
        if ($this->get_id() > 0)
            return $this->pPriority;
        else
            return false;
    }
    public function get_changeFreq_refid()
    {
        if ($this->get_id() > 0)
            return $this->pChangeFreq_refid;
        else
            return false;
    }
    public function get_exclude_sitemap()
    {
        if ($this->get_id() > 0)
            return $this->pExclude_sitemap;
        else
            return false;
    }
    public function get_exclude_search()
    {
        if ($this->get_id() > 0)
            return $this->pExclude_search;
        else
            return false;
    }
    public function get_content_with_section()
    {
        if ($this->get_id() > 0)
            return $this->pContent_with_section;
        else
            return false;
    }
    public function get_keyword()
    {
        if ($this->get_id() > 0)
            return $this->pKeyword;
        else
            return false;
    }
    public function get_generated_formated_content()

    {
        if ($this->get_id() > 0)
            return $this->pGenerated_formated_content;
        else
            return false;
    }
    public function get_meta_cloud()
    {
        if ($this->get_id() > 0)
            return $this->pMeta_cloud;
        else
            return false;
    }
    public function get_description_sphinx()
    {
        return $this->pDescription_sphinx;
    }
    public function get_page_on_disk()
    {
        if ($this->get_id() > 0)
            return $this->pPage_on_disk;
        else
            return false;
    }
    public function get_permit_comment()
    {
        if ($this->get_id() > 0)
            return $this->pPermit_comment;
        else
            return false;
    }
    public function get_author_refid()
    {
        if ($this->get_id() > 0)
            return $this->pAuthor_refid;
        else
            return false;
    }
    public function get_author()
    {
        if ($this->get_id() > 0)
        {
            $u = new user($this->db);
            $u->fetch($this->get_author_refid());
            return $u;
        } else
            return false;
    }
    public function get_extra_params()
    {
        if ($this->get_id() > 0)
            return $this->pExtra_params;
        else
            return false;
    }
    public function get_is_locked_for_edition()
    {
        if ($this->get_id() > 0)
            return $this->pIs_locked_for_edition;
        else
            return false;
    }
    public function get_social_media()
    {
        if ($this->get_id() > 0)
        {
            return $this->pSocial_media;
        } else
            return false;
    }
    public function get_plugins()
    {
        if ($this->get_id() > 0)
        {
            return $this->pPlugins;
        } else
            return false;
    }
    public function get_use_cache()
    {
        if ($this->get_id() > 0)
            return $this->pUse_cache;
        else
            return false;
    }
    public function get_publication_status_refid()
    {
        if ($this->get_id() > 0)
            return $this->pPublication_status_refid;
        else
            return false;
    }
    public function get_is_a_draft_for()
    {
        if ($this->get_id() > 0)
            return $this->pIs_a_draft_for;
        else
            return false;
    }
    public function get_last_modif_by()
    {
        if ($this->get_id() > 0)
            return $this->pLast_modif_by;
        else
            return false;
    }
    public function get_last_modif_by_user_obj()
    {
        if ($this->get_id() > 0)
        {
            $u = new user($this->db);
            $u->fetch($this->get_last_modif_by());
            return $u;
        } else
            return false;
    }
    public function get_lang()
    {
        if ($this->get_id() > 0)
            return $this->pLang;
        else
            return false;
    }
    public function get_section()
    {
        if ($this->get_id()>0)
        {
            if (! $this->pCacheSection)
            {
                $requete = "SELECT id
                              FROM section_revision
                             WHERE active = 1
                               AND page_revision_refid = ".$this->get_id()."
                          ORDER BY `order`";
                $sql1 = $this->db->query($requete);
                $tmp = array();
                if ($sql1 && $this->db->num_rows($sql1) > 0)
                {
                    load_alternative_class('class/section_revision.class.php');
                    while($res1 = $this->db->fetch_object($sql1))
                    {
                        $sect = new section_revision($this->db);
                        $sect->fetch($res1->id);
                        $tmp[]= $sect->get_all();
                    }
                }
                $this->pCacheSection = $tmp;
                $this->pSection = $tmp;
                return ($tmp);
            } else {
                $this->pSection = $this->pCacheSection;
                return $this->pCacheSection;
            }
        }
    }
    public function get_page_refid()
    {
        if ($this->get_id() > 0)
            return $this->pPage_refid;
        else
            return false;
    }
    public function get_form_refid()
    {
        if ($this->get_id() > 0)
            return $this->pForm_refid;
        else
            return false;
    }
    public function make_a_page()
    {
        if ($this->get_id()>0)
        {
            global $current_user;
            $this->db->begin();
            $requete = "UPDATE page
                           SET";
            if ($this->get_title()."x" != "x")
                $requete .= "      `title` = '".addslashes($this->get_title())."',";
            else
                $requete .= "      `title` = '',";
            if ($this->get_content()."x" != "x")
                $requete .= "      `content` = '".addslashes($this->get_content())."',";
            else
                $requete .= "      `content` = '',";
            if ($this->get_model_refid()."x" != "x")
                $requete .= "      `model_refid` = '".$this->get_model_refid()."',";
            else
                $requete .= "      `model_refid` = null,";
            if ($this->get_in_menu()."x" != "x")
                $requete .= "      `in_menu` = '".$this->get_in_menu()."',";
            else
                $requete .= "      `in_menu` = 0,";
            if ($this->get_url()."x" != "x")
                $requete .= "      `url` = '".addslashes($this->get_url())."',";
            else
                $requete .= "      `url` = '',";
            if ($this->get_parent_refid()."x" != "x")
                $requete .= "      `parent_refid` = '".$this->get_parent_refid()."',";
            else
                $requete .= "      `parent_refid` = null,";
            if ($this->get_meta()."x" != "x")
                $requete .= "      `meta` = '".addslashes($this->get_meta())."',";
            else
                $requete .= "      `meta` = '',";
            if (json_encode($this->get_js())."x" != "x")
                $requete .= "      `js` = '".addslashes(json_encode($this->get_js()))."',";
            else
                $requete .= "      `js` = '',";
            if ($this->get_js_external()."x" != "x")
                $requete .= "      `js_external` = '".addslashes($this->get_js_external())."',";
            else
                $requete .= "      `js_external` = '',";
            if ($this->get_js_code()."x" != "x")
                $requete .= "      `js_code` = '".addslashes($this->get_js_code())."',";
            else
                $requete .= "      `js_code` = '',";
            if (json_encode($this->get_css())."x" != "x")
                $requete .= "      `css` = '".addslashes(json_encode($this->get_css()))."',";
            else
                $requete .= "      `css` = '',";
            if ($this->get_css_code()."x" != "x")
                $requete .= "      `css_code` = '".addslashes($this->get_css_code())."',";
            else
                $requete .= "      `css_code` = '',";
            if ($this->get_active()."x" != "x")
                $requete .= "      `active` = '".$this->get_active()."',";
            else
                $requete .= "      `active` = 0,";
            if ($this->get_date_creation()."x" != "x")
                $requete .= "      `date_creation` = '".addslashes($this->get_date_creation())."',";
            else
                $requete .= "      `date_creation` = now,";
            if ($this->get_date_derniere_maj()."x" != "x")
                $requete .= "      `date_derniere_maj` = '".addslashes($this->get_date_derniere_maj())."',";
            else
                $requete .= "      `date_derniere_maj` = now,";
            if ($this->get_last_modif_by()."x" != "x")
                $requete .= "      `last_modif_by` = '".$this->get_last_modif_by()."',";
            else
                $requete .= "      `last_modif_by` = null,";
            if ($this->get_changeFreq_refid()."x" != "x")
                $requete .= "      `changeFreq_refid` = '".$this->get_changeFreq_refid()."',";
            else
                $requete .= "      `changeFreq_refid` = null,";
            if ($this->get_priority()."x" != "x")
                $requete .= "      `priority` = '".$this->get_priority()."',";
            else
                $requete .= "      `priority` = 0.5,";
            if ($this->get_exclude_sitemap()."x" != "x")
                $requete .= "      `exclude_sitemap` = '".$this->get_exclude_sitemap()."',";
            else
                $requete .= "      `exclude_sitemap` = 0,";
            if ($this->get_exclude_search()."x" != "x")
                $requete .= "      `exclude_search` = '".$this->get_exclude_search()."',";
            else
                $requete .= "      `exclude_search` = 0,";
            if ($this->get_keyword()."x" != "x")
                $requete .= "      `keyword` = '".addslashes($this->get_keyword())."',";
            else
                $requete .= "      `keyword` = '',";
            if ($this->get_generated_formated_content()."x" != "x")
                $requete .= "      `generated_formated_content` = '".addslashes($this->get_generated_formated_content())."',";
            else
                $requete .= "      `generated_formated_content` = '',";
            if ($this->get_page_on_disk()."x" != "x")
                $requete .= "      `page_on_disk` = '".$this->get_page_on_disk()."',";
            else
                $requete .= "      `page_on_disk` = 0,";
            if ($this->get_permit_comment()."x" != "x")
                $requete .= "      `permit_comment`= '".$this->get_permit_comment()."',";
            else
                $requete .= "      `permit_comment` = 0,";
            if ($this->get_author_refid()."x" != "x")
                $requete .= "      `author_refid` = '".$this->get_author_refid()."',";
            else
                $requete .= "      `author_refid` = '".$current_user->get_id()."',";
            if ($this->get_extra_params()."x" != "x")
                $requete .= "      `extra_params` = '".addslashes($this->get_extra_params())."',";
            else
                $requete .= "      `extra_params` = '',";
            if ($this->get_is_locked_for_edition()."x" != "x")
                $requete .= "      `is_locked_for_edition` = '".$this->get_is_locked_for_edition()."',";
            else
                $requete .= "      `is_locked_for_edition` = null,";
            if ($this->get_social_media()."x" != "x")
                $requete .= "      `social_media` = '".addslashes($this->get_social_media())."',";
            else
                $requete .= "      `social_media` = '',";
            if ($this->get_plugins()."x" != "x")
                $requete .= "      `plugins` = '".addslashes($this->get_plugins())."',";
            else
                $requete .= "      `plugins` = '',";
            if ($this->get_use_cache()."x" != "x")
                $requete .= "      `use_cache` = '".$this->get_use_cache()."',";
            else
                $requete .= "      `use_cache` = 0,";
            if ($this->get_publication_status_refid()."x" != "x")
                $requete .= "      `publication_status_refid` = '".$this->get_publication_status_refid()."',";
            else
                $requete .= "      `publication_status_refid` = null,";
            if ($this->get_is_a_draft_for()."x" != "x")
                $requete .= "      `is_a_draft_for` = '".$this->get_is_a_draft_for()."',";
            else
                $requete .= "      `is_a_draft_for` = null,";
            if ($this->get_lang()."x" != "x")
                $requete .= "      `lang` = '".$this->get_lang()."',";
            else
                $requete .= "      `lang` = null,";
            $requete .="       `date_derniere_maj` = now(),";
            $requete .="       `last_modif_by` = ".$current_user->get_id();
            $requete .= " WHERE id = ".$this->get_page_refid();
            $sql_main = $this->db->query($requete);
            $requete = "DELETE FROM section
                              WHERE page_refid = ".$this->get_page_refid();
            $sql = $this->db->query($requete);
            $requete= "SELECT id
                         FROM section_revision
                        WHERE page_revision_refid = ".$this->get_id();
            $sql = $this->db->query($requete);

            load_alternative_class('class/section_revision.class.php');
            $sql_global=true;
            while ($res = $this->db->fetch_object($sql))
            {
                $requete = "INSERT INTO section(author_refid) VALUES (".$current_user->get_id().") ";
                $sql_pre = $this->db->query($requete);
                $new_section_id = $this->db->last_insert_id('section');
                $s = new section_revision($this->db);
                $s->fetch($res->id);
                $requete = "UPDATE section
                               SET";
                if ($s->get_content()."x" != "x")
                    $requete .="       `content` = '".addslashes($s->get_content())."', ";
                else
                    $requete .= "       `content` = null";
                if ($s->get_title()."x" != "x")
                    $requete .="       `title`  = '".addslashes($s->get_title())."',";
                else
                    $requete .= "       `title` = null";
                if ($s->get_order()."x" != "x")
                    $requete .="       `order` = '".$s->get_order()."' ,";
                else
                    $requete .= "       `order` = null";
                if ($s->get_active()."x" != "x")
                    $requete .="       `active` = '".$s->get_active()."',";
                else
                    $requete .= "       `active` = 0";
                if ($s->get_date_creation()."x" != "x")
                    $requete .="       `date_creation` = '".addslashes($s->get_date_creation())."',";
                else
                    $requete .= "       `date_creation` = null";
                if ($s->get_date_derniere_maj()."x" != "x")
                    $requete .="       `date_derniere_maj` ='".addslashes($s->get_date_derniere_maj())."',";
                else
                    $requete .= "       `date_derniere_maj` = null";
                if ($s->get_author_refid()."x" != "x")
                    $requete .="       `author_refid` = '".$s->get_author_refid()."' ,";
                else
                    $requete .= "       `author_refid` = null";
                if ($s->get_is_locked_for_edition()."x" != "x")
                    $requete .="       `is_locked_for_edition` = '".$s->get_is_locked_for_edition()."',";
                else
                    $requete .= "       `is_locked_for_edition` = null";
                if ($s->get_associated_img()."x" != "x")
                    $requete .="       `associated_img` = '". addslashes($s->get_associated_img()) ."' ,";
                else
                    $requete .= "       `associated_img` = null";
                if ($s->get_last_modif_by()."x" != "x")
                    $requete .="       `last_modif_by` = '".$s->get_last_modif_by()."',";
                else
                    $requete .= "       `last_modif_by` = null";
                if ($s->get_lang()."x" != "x")
                    $requete .="       `lang` = '".$s->get_lang()."',";
                else
                    $requete .= "       `lang` = null";

                $requete .="       `page_refid` = '".$this->get_page_refid()."'";
                $requete .=" WHERE id = ".$new_section_id;
                $sql1 = $this->db->query($requete);
                if ($sql_global && !$sql1) $sql_global= false;
            }
            global $trigger,$current_user;
            $trigger->run_trigger("PAGE_MAKE_REV", $this,$current_user);

            if ($sql_main && $sql_global)
            {
                $this->db->commit();
                load_alternative_class('class/page.class.php');
                $p = new page($this->db);
                $p->fetch($this->get_page_refid());
                $p->update_sphinx();
                $p->refresh_sitemap();
                $p->set_log_modif_page('restore_revision',"NA",$this->get_id());
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
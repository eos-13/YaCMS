<?php
class page extends common_object
{
    protected $db;
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
    private $pForm;

    private $pCacheSection_content=false;
    private $pDescription_sphinx="";
    private $pMeta_cloud;
    private $pSection=array();
    private $pCacheSection=false;

    public $id;
    public $title;
    public $url;
    public $content;
    public $inmenu;
    public $parentrefid;
    public $modelrefid;
    public $meta;
    public $js;
    public $js_code;
    public $css;
    public $css_code;
    public $active;
    public $date_creation;
    public $date_derniere_maj;
    public $changeFreq_refid;
    public $priority;
    public $exclude_sitemap;
    public $exclude_search;
    public $content_with_section;
    public $section_content;
    public $keyword;
    public $generated_formated_content;
    public $page_on_disk;
    public $permit_comment;
    public $js_external;
    public $author_refid;
    public $extra_params;
    public $is_locked_for_edition;
    public $social_media;
    public $plugins;
    public $use_cache;
    public $publication_status_refid;
    public $is_a_draft_for;
    public $last_modif_by;
    public $lang;
    public $form_refid;
    public $form;


    public $description_sphinx;
    public $meta_cloud;
    public $publication_status_name;


    protected $table = "page";

    /**
     * @param int $id
     * @return int $id
     * @desc load page datas
     */
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

            $this->pForm = $this->get_form();

            return $res->id;
        } else {
            return false;
        }
    }
    /**
     * @param string $short_url
     * @return int $draftFor
     * @desc Load page by url or load a draft
     */

    public function fetch_by_url($url,$draftFor=false)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE url = '".addslashes($url)."'";
        if (!$draftFor)
            $requete .= "AND is_a_draft_for IS NULL";
        else
            $requete = " AND is_a_draft_for = ".$draftFor;
        global $lang,$conf;
        if ($lang == $conf->default_lang)
        {
            $requete .= " AND (lang IS NULL or lang = '".addslashes($lang)."' )";
        } else {
            $requete .= " AND lang = '".addslashes($lang)."' ";
        }
        $sql = $this->db->query($requete);
        if (!$sql || $this->db->num_rows($sql)==0) return false;
        $res = $this->db->fetch_object($sql);
        $this->pId = $res->id;
        $this->id = $res->id;
        $this->pTitle = $res->title;
        $this->pUrl = $res->url;
        $this->pContent = $res->content;
        $this->pActive = $res->active;
        //On ajoute les sections
        $this->pContent_with_section = $res->content." ". $this->get_section_content();
        $this->pSection_content = $this->get_section_content();

        $this->pForm = $this->get_form();

        $this->pInmenu = $res->in_menu;
        $this->pParentrefid = $res->parent_refid;
        $this->pModelrefid = $res->model_refid;
        $this->pMeta = $res->meta;
        $this->pJs = json_decode($res->js,true);
        $this->pJs_code = $res->js_code;
        $this->pCss = json_decode($res->css);
        $this->pCss_code = $res->css_code;
        $this->pDate_creation = $res->date_creation;
        $this->pDate_derniere_maj =$res->date_derniere_maj;
        $this->pChangeFreq_refid = $res->changeFreq_refid;
        $this->pPriority = $res->priority;
        $this->pExclude_sitemap = $res->exclude_sitemap;
        $this->pExclude_search = $res->exclude_search;
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

        return $res->id;
    }
    /**
     * @return string $section
     * @desc Fetch all the section contents of a loaded page
     */

    public function get_section_content()
    {
        if (! $this->pCacheSection_content)
        {
            $requete = "SELECT id
                          FROM section
                         WHERE active = 1
                           AND page_refid = ".$this->get_id(). "
                      ORDER BY `order`";
            $sql1 = $this->db->query($requete);
            $tmp = "";
            if ($sql1 && $this->db->num_rows($sql1) > 0)
            {
                load_alternative_class('class/section.class.php');
                $sect = new section($this->db);
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
    /**
     * @desc Fetch the object of a form
     */
    public function get_form()
    {
        if ($this->get_id()> 0)
        {
            if ($this->get_form_refid() > 0)
            {
                load_alternative_class('class/form.class.php');
                $f = new form($this->db);
                $f->fetch($this->get_form_refid());
                return $f;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * @return string[] $sections
     * @desc Fetch an array of all page section for a laoded page
     */

    public function get_all_section()
    {
        if($this->get_id() >0)
        {
            $requete = "SELECT id
                          FROM section
                         WHERE page_refid = ".$this->pId."
                      ORDER BY `order`";
            $sql1 = $this->db->query($requete);
            $tmp = array();
            if ($sql1 && $this->db->num_rows($sql1) > 0)
            {
                load_alternative_class('class/section.class.php');
                $sect = new section($this->db);
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
    /**
     * @param int $id
     * @desc Set all private data public
     */
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
            $this->lang = $this->pLang;
            $this->form_refid = $this->pForm_refid;
            $this->form = $this->pForm;

            $requete = "SELECT *
                          FROM publication_status
                         WHERE id = ".$this->pPublication_status_refid;
            $sql = $this->db->query($requete);
            $res = $this->db->fetch_object($sql);
            $this->publication_status_name = $res->name;

            return $this;
        } else {
            return false;
        }
    }
    /**
     * @return int $id
     * @desc Get the id of current loaded page
     */
    public function get_id()
    {
        if ($this->pId > 0)
        {
            $this->id = $this->pId;
            return $this->pId;
        }
        else
            return false;
    }
    /**
     * @return  dateTime $date_creation
     * @desc Get the creation date of currently loaded page
     */
    public function get_date_creation()
    {
        if ($this->get_id() > 0)
            return $this->pDate_creation;
        else
            return false;
    }
    /**
     * @return  dateTime $date_last_modify
     * @desc Get the last modification date of currently loaded page
     */

    public function get_date_derniere_maj()
    {
        if ($this->get_id() > 0)
            return $this->pDate_derniere_maj;
        else
            return false;
    }
    /**
     * @return  string $title
     * @desc Get the title of the page
     */
    public function get_title()
    {
        if ($this->get_id() > 0)
            return $this->pTitle;
        else
            return false;
    }
    /**
     * @return  string $url
     * @desc Get the url of the page
     */
    public function get_url()
    {
        if ($this->get_id() > 0)
            return $this->pUrl;
        else
            return false;
    }
    /**
     * @return  int $model_refid
     * @desc Get the model id of the page
     */
    public function get_model_refid()
    {
        if ($this->get_id() > 0)
            return $this->pModelrefid;
        else
            return false;
    }
    /**
     * @return  string $content
     * @desc Get the content of the page
     */
    public function get_content()
    {
        if ($this->get_id() > 0 && !empty($this->pContent))
            return $this->pContent;
        else
            return false;
    }
    /**
     * @return  bool $in_menu
     * @desc Is the page appear in menu
     */
    public function get_in_menu()
    {
        if ($this->get_id() > 0)
            return $this->pInmenu;
        else
            return false;
    }
    /**
     * @return  int $parent_refid
     * @desc Get the title of the page
     */
    public function get_parent_refid()
    {
        if ($this->get_id() > 0)
            return $this->pParentrefid;
        else
            return false;
    }
    /**
     * @return  string $meta
     * @desc Get the meta keyword of the page
     */
    public function get_meta()
    {
        if ($this->get_id() > 0)
            return $this->pMeta;
        else
            return false;
    }
    /**
     * @return  string $js[]
     * @desc Get the js library list of the page
     */
    public function get_js()
    {
        if ($this->get_id() > 0)
            return $this->pJs;
        else
            return false;
    }
    /**
     * @return  string $js_external
     * @desc Get the external js library list (json_encoded) of the page
     */
    public function get_js_external()
    {
        if ($this->get_id() > 0)
            return $this->pJs_external;
        else
            return false;
    }
    /**
     * @return  string $js_code
     * @desc Get the js_code of the page
     */
    public function get_js_code()
    {
        if ($this->get_id() > 0)
            return $this->pJs_code;
        else
            return false;
    }
    /**
     * @return  string $css[]
     * @desc Get the list of css files of the page
     */
    public function get_css()
    {
        if ($this->get_id() > 0)
            return $this->pCss;
        else
            return false;
    }
    /**
     * @return  string $css_code
     * @desc Get the css code of the page
     */
    public function get_css_code()
    {
        if ($this->get_id() > 0)
            return $this->pCss_code;
        else
            return false;
    }
    /**
     * @return  bool $active
     * @desc Is the page active ?
     */
    public function get_active()
    {
        if ($this->get_id() > 0)
            return $this->pActive;
        else
            return false;
    }
    /**
     * @return  float $priority
     * @desc Get the sitemap priority of the page
     */
    public function get_priority()
    {
        if ($this->get_id() > 0)
            return $this->pPriority;
        else
            return false;
    }
    /**
     * @return  int $changeFreq_refid
     * @desc Get sitemap changeFreq refid
     */
    public function get_changeFreq_refid()
    {
        if ($this->get_id() > 0)
            return $this->pChangeFreq_refid;
        else
            return false;
    }
    /**
     * @return  bool $exclude_sitemap
     * @desc Is the page excluded from sitemap
     */
    public function get_exclude_sitemap()
    {
        if ($this->get_id() > 0)
            return $this->pExclude_sitemap;
        else
            return false;
    }
    /**
     * @return  bool $exclude_search
     * @desc Is the page excluded from search
     */
    public function get_exclude_search()
    {
        if ($this->get_id() > 0)
            return $this->pExclude_search;
        else
            return false;
    }
    /**
     * @return  string $content_with_section
     * @desc Get the content of the page and all sections
     */
    public function get_content_with_section()
    {
        if ($this->get_id() > 0)
            return $this->pContent_with_section;
        else
            return false;
    }
    /**
     * @return  string $keyword
     * @desc Get the keyword of a page
     */
    public function get_keyword()
    {
        if ($this->get_id() > 0)
            return $this->pKeyword;
        else
            return false;
    }
    /**
     * @return  string $generated_formated_content
     * @desc Get the generated formated content of a page
     */
    public function get_generated_formated_content()

    {
        if ($this->get_id() > 0)
            return $this->pGenerated_formated_content;
        else
            return false;
    }
    /**
     * @return  string $meta_cloud
     * @desc Get the meta cloud of the page
     */
    public function get_meta_cloud()
    {
        if ($this->get_id() > 0)
            return $this->pMeta_cloud;
        else
            return false;
    }
    /**
     * @return  string $description_sphinx
     * @desc Get the search description
     */
    public function get_description_sphinx()
    {
        return $this->pDescription_sphinx;
    }
    /**
     * @return  bool $page_on_disk
     * @desc Is page on disk or in db
     */
    public function get_page_on_disk()
    {
        if ($this->get_id() > 0)
            return $this->pPage_on_disk;
        else
            return false;
    }
    /**
     * @return  bool $permit_comment
     * @desc Are comments allowed on this page ?
     */
    public function get_permit_comment()
    {
        if ($this->get_id() > 0)
            return $this->pPermit_comment;
        else
            return false;
    }
    /**
     * @return  int $author_refid
     * @desc Get the id of the author
     */
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
    /**
     * @return  string $extra_params
     * @desc Get the json_encoded list of all params
     */
    public function get_extra_params()
    {
        if ($this->get_id() > 0)
            return $this->pExtra_params;
        else
            return false;
    }
    /**
     * @return  int $is_locked_for_edition
     * @desc Is the page locked for edition? if result > 0, it take the value of the user who locked the page
     */
    public function get_is_locked_for_edition()
    {
        if ($this->get_id() > 0)
            return $this->pIs_locked_for_edition;
        else
            return false;
    }
    /**
     * @return  string $social_media
     * @desc Get the json_encoded list of social media id for a page. These ids come from the social_media table.
     */
    public function get_social_media()
    {
        if ($this->get_id() > 0)
        {
            return $this->pSocial_media;
        } else
            return false;
    }
    /**
     * @return  string $plugins
     * @desc Get the json encoded list of plugin of the page
     */
    public function get_plugins()
    {
        if ($this->get_id() > 0)
        {
            return $this->pPlugins;
        } else
            return false;
    }
    /**
     * @return  bool $use_cache
     * @desc Does page use CSS and JS cache ?
     */
    public function get_use_cache()
    {
        if ($this->get_id() > 0)
            return $this->pUse_cache;
        else
            return false;
    }
    /**
     * @return  int $publication_status_refid
     * @desc Get the id of the publication state
     */
    public function get_publication_status_refid()
    {
        if ($this->get_id() > 0)
            return $this->pPublication_status_refid;
        else
            return false;
    }
    /**
     * @return  int $is_a_draft_for
     * @desc Get the page this one is a draft for (or false if not a draft)
     */
    public function get_is_a_draft_for()
    {
        if ($this->get_id() > 0)
            return $this->pIs_a_draft_for;
        else
            return false;
    }
    /**
     * @return  int $last_modify_by
     * @desc Get the user id of the last editor
     */
    public function get_last_modif_by()
    {
        if ($this->get_id() > 0)
            return $this->pLast_modif_by;
        else
            return false;
    }
    /**
     * @return  string $title
     * @desc Get the title of the page
     */
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
    /**
     * @return  string $lang
     * @desc Get the locale of the page (null if default local)
     */
    public function get_lang()
    {
        if ($this->get_id() > 0)
            return $this->pLang;
        else
            return false;
    }
    /**
     * @return  int $form_refid
     * @desc Get the id of the form publish on the page (or null if no form)
     */
    public function get_form_refid()
    {
        if ($this->get_id() > 0)
            return $this->pForm_refid;
        else
            return false;
    }
    /**
     * @param int $from_page_id
     * @param int $dest_page_id
     * @return  bool $result
     * @desc Link to page together
     */
    public function link($page_id,$to_page_id)
    {
        $requete="UPDATE page
                     SET parent_refid = ".$to_page_id ."
                   WHERE id = ".$page_id;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            $this->set_log_modif_page('link page',"NA",$page_id ."=>". $to_page_id);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $this->refresh_sitemap();
        return $sql;
    }
    /**
     * @param string $content
     * @return  bool $result
     * @desc Set the content
     */
    public function set_content($content)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('content',$content);
            if ($ret) $this->set_log_modif_page('content',$this->get_content(),$content);
            $this->refresh_sitemap();
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $title
     * @return  bool $result
     * @desc Set the title
     */
    public function set_title($title)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('title',$title);
            if ($ret) $this->set_log_modif_page('title',$this->get_title(),$title);
            $this->refresh_sitemap();
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $url
     * @return  bool $result
     * @desc Set the url
     */
    public function set_url($url)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('url',$url);
            if ($ret) $this->set_log_modif_page('url',$this->get_url(),$url);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param bool $in_menu
     * @return  bool $result
     * @desc Change the presence in menu of a page
     */
    public function set_in_menu($in_menu)
    {
        if (!$in_menu || empty($in_menu) || is_null($in_menu))
        {
            $in_menu = "0";
        }
        if ($this->get_id() > 0){
            $ret = $this->update_field('in_menu',$in_menu);
            if ($ret) $this->set_log_modif_page('in_menu',$this->get_in_menu(),$in_menu);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param int $model_Refid
     * @return  bool $result
     * @desc Change the model id of a page
     */
    public function set_model_refid($model_refid)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('model_refid',$model_refid);
            if ($ret) $this->set_log_modif_page('model_refid',$this->get_model_refid(),$model_refid);
            $this->refresh_sitemap();
            $this->fetch($this->get_id());
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $meta
     * @return  bool $result
     * @desc Change the meta word of a page
     */
    public function set_meta($meta)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('meta',$meta);
            if ($ret) $this->set_log_modif_page('meta',$this->get_meta(),$meta);
            $this->fetch($this->get_id());
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $js
     * @return  bool $result
     * @desc Change the js library list of the page (need to be json_encoded array)
     */
    public function set_js($js)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('js',$js);
            if ($ret) $this->set_log_modif_page('js',json_encode($this->get_js()),json_encode($js));
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $js_external
     * @return  bool $result
     * @desc Change the external js library list of the page (need to be json_encoded array)
     */
    public function set_js_external($js)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('js_external',$js);
            if ($ret) $this->set_log_modif_page('js_external',$this->get_js_external(),$js);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $js_code
     * @return  bool $result
     * @desc Change the js code of the page
     */
    public function set_js_code($js_code)
    {
        if ($this->get_id() > 0){
            $res =  $this->update_field('js_code',$js_code);
            if ($res) $this->set_log_modif_page('js_code',$this->get_js_code(),$js_code);
            return $res;
        } else {
            return false;
        }
    }
    /**
     * @param string $css
     * @return  bool $result
     * @desc Change the css file list of the page (need to be json_encoded array)
     */
    public function set_css($css)
    {
        if ($this->get_id() > 0){
            $res =  $this->update_field('css',$css);
            if ($res) $this->set_log_modif_page('css',json_encode($this->get_css()),json_encode($css));
            $this->refresh_sitemap();
            return $res;
        } else {
            return false;
        }
    }
    /**
     * @param string $css_code
     * @return  bool $result
     * @desc Change the css code of the page
     */
    public function set_css_code($css_code)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('css_code',$css_code);
            if ($ret) $this->set_log_modif_page('css_code',$this->get_css_code(),$css_code);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param bool $active
     * @return  bool $result
     * @desc Set if the page is active or not
     */
    public function set_active($active)
    {
        if (!$active || empty($active) || is_null($active))
        {
            $active = "0";
        }
        if ($this->get_id() > 0){
            $ret =  $this->update_field('active',$active);
            if ($ret) $this->set_log_modif_page('active',$this->get_active(),$active);
            $this->refresh_sitemap();
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param int $changeFreq_refid
     * @return  bool $result
     * @desc Change the id of the sitemap change frequency parameter
     */
    public function set_changeFreq_refid($changeFreq_refid)
    {
        if ($this->get_id() > 0){
            $ret =  $this->update_field('changeFreq_refid',$changeFreq_refid);
            if ($ret) $this->set_log_modif_page('changeFreq_refid',$this->get_changeFreq_refid(),$changeFreq_refid);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param float $priority
     * @return  bool $result
     * @desc Change the sitemap priority of a page
     */
    public function set_priority($priority)
    {
        if ($this->get_id() > 0){
            if (!$priority || empty($priority) || is_null($priority))
            {
                $priority = "0";
            }
            $ret =  $this->update_field('priority',$priority);
            if ($ret) $this->set_log_modif_page('priority',$this->get_priority(),$priority);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param bool $exclude_sitemap
     * @return  bool $result
     * @desc Set if page is exclude or not from sitemap
     */
    public function set_exclude_sitemap($exclude_sitemap)
    {
        if ($this->get_id() > 0)
        {
            if (!$exclude_sitemap || empty($exclude_sitemap) || is_null($exclude_sitemap))
            {
                $exclude_sitemap = "0";
            }
            $ret =  $this->update_field('exclude_sitemap',$exclude_sitemap);
            if ($ret) $this->set_log_modif_page('exclude_sitemap',$this->get_exclude_sitemap(),$exclude_sitemap);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param bool $exclude_search
     * @return  bool $result
     * @desc Set if page is exclude or not from search
     */
    public function set_exclude_search($exclude_search)
    {
        if (!$exclude_search || empty($exclude_search) || is_null($exclude_search))
        {
            $exclude_search = "0";
        }
        if ($this->get_id() > 0)
        {
            $rem = $this->pExclude_search;
            $ret =  $this->update_field('exclude_search',$exclude_search);
            if ($ret) $this->set_log_modif_page('exclude_search',$this->get_exclude_search(),$exclude_search);
            $this->pExclude_search = $exclude_search;
            if ($this->pExclude_search == 1 && $this->pExclude_search != $rem)
            {
                $this->delete_sphinx();
            } else if ( $this->pExclude_search != $rem) {
                $this->insert_sphinx();
            }

            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $keyword
     * @return  bool $result
     * @desc Set the page keyword
     */
    public function set_keyword($keyword)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('keyword',$keyword);
            if ($ret) $this->set_log_modif_page('keyword',$this->get_keyword(),$keyword);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $generated_formated_content
     * @return  bool $result
     * @desc Set the generated content of a page
     */
    public function set_generated_formated_content($generated_formated_content)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('generated_formated_content',$generated_formated_content);
            if ($ret) $this->set_log_modif_page('generated_formated_content',$this->get_generated_formated_content(),$generated_formated_content);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $desc
     * @return  bool $result
     * @desc Change sphinx description
     */
    public function set_description_sphinx($desc)
    {
        $this->pDescription_sphinx = $desc;
    }
    /**
     * @param string $meta_cloud
     * @return  bool $result
     * @desc Set meta cloud datas
     */
    public function set_meta_cloud($meta_cloud)
    {
        $this->pMeta_cloud = $meta_cloud;
    }
    /**
     * @param bool $page_on_disk
     * @return  bool $result
     * @desc Set if page page is on disk or not
     */
    public function set_page_on_disk($page_on_disk)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('page_on_disk',$page_on_disk);
            if ($ret) $this->set_log_modif_page('page_on_disk',$this->get_page_on_disk(),$page_on_disk);
            $this->refresh_sitemap();
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param bool $permit_comment
     * @return  bool $result
     * @desc Change if comments are allowed or not
     */
    public function set_permit_comment($permit_comment)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('permit_comment',$permit_comment);
            if ($ret) $this->set_log_modif_page('permit_comment',$this->get_permit_comment(),$permit_comment);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param int $author_refid
     * @return  bool $result
     * @desc Set the id of the page author
     */
    public function set_author_refid($author_refid)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('author_refid',$author_refid);
            if ($ret) $this->set_log_modif_page('author_refid',$this->get_author_refid(),$author_refid);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $extra_params_name
     * @param string $extra_params_val
     * @return  bool $result
     * @desc Set the value for an extraparams
     */
    public function set_extra_params($extra_params_name,$extra_params_val)
    {
        if ($this->get_id() > 0)
        {
            $a = json_decode($this->get_extra_params());
            $a->$extra_params_name = $extra_params_val;
            $a = json_encode($a);
            $this->pExtra_params = $a;
            $ret =  $this->update_field('extra_params',$a);
            if ($ret) $this->set_log_modif_page('extra_params',$this->get_extra_params(),$extra_params_name." ".$extra_params_val);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $extra_params
     * @return  bool $result
     * @desc Change all the extraparams of a page (need to be json encoded array)
     */
    public function set_extra_params_field($extra_params)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('extra_params',$extra_params);
            if ($ret) $this->set_log_modif_page('extra_params',$this->get_extra_params(),$extra_params);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param int $is_locked_for_edition
     * @return  bool $result
     * @desc Set if page is locked or not for edition (if is_locked_for_edition > 0, it takes the value of the locker user id)
     */
    public function set_is_locked_for_edition($is_locked_for_edition)
    {
        if ($this->get_id() > 0)
        {
            if (!$is_locked_for_edition || empty($is_locked_for_edition) || is_null($is_locked_for_edition))
            {
                $is_locked_for_edition = "0";
            }
            if ($ret) $this->set_log_modif_page('is_locked_for_edition',$this->get_is_locked_for_edition(),$is_locked_for_edition);
            $ret =  $this->update_field('is_locked_for_edition',$is_locked_for_edition);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $social_media
     * @return  bool $result
     * @desc Set the available social media link on page (json array of social media from social_media table)
     */
    public function set_social_media($social_media)
    {
    if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('social_media',$social_media);
            if ($ret) $this->set_log_modif_page('social_media',$this->get_social_media(),$social_media);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $plugins
     * @return  bool $result
     * @desc Set the available plugin for the page (json_encode array)
     */
    public function set_plugins($plugins)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('plugins',$plugins);
            if ($ret) $this->set_log_modif_page('plugins',$this->get_plugins(),$plugins);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param bool $use_cache
     * @return  bool $result
     * @desc Set if page the page use CSS/JS cache
     */
    public function set_use_cache($use_cache)
    {
        if (!$use_cache || empty($use_cache) || is_null($use_cache))
        {
            $use_cache = "0";
        }
        if ($this->get_id() > 0){
            $ret = $this->update_field('use_cache',$use_cache);
            if ($ret) $this->set_log_modif_page('use_cache',$this->get_use_cache(),$use_cache);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param int $publication_status_refid
     * @return  bool $result
     * @desc Set the publication status id
     */
    public function set_publication_status_refid($publication_status_refid)
    {
        if ($this->get_id() > 0)
        {
            load_alternative_class('class/publication_status.class.php');
            $ps = new publication_status($this->db);
            $ps->fetch($publication_status_refid);
            $was_sphinx = $ps->get_in_search_engine();
            $ps->fetch($this->get_publication_status_refid());
            $is_sphinx = $ps->get_in_search_engine();
            $ret = $this->update_field('publication_status_refid',$publication_status_refid);
            if ($ret) $this->set_log_modif_page('publication_status_refid',$this->get_publication_status_refid(),$publication_status_refid);
            $this->refresh_sitemap();
            if ($was_sphinx == 1 && $is_sphinx == 0)
            {
                $this->delete_sphinx(true);
            }

            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param bool $is_a_draft_for
     * @return  bool $result
     * @desc If the page is a draft for another, place its id
     */
    public function set_is_a_draft_for($is_a_draft_for)
    {
        if ($this->get_id() > 0)
        {
            if (!$is_a_draft_for || empty($is_a_draft_for) || is_null($is_a_draft_for))
            {
                $is_a_draft_for = "null";
            }
            $ret = $this->update_field('is_a_draft_for',$is_a_draft_for);
            if ($ret) $this->set_log_modif_page('is_a_draft_for',$this->get_is_a_draft_for(),$is_a_draft_for);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param int $last_modif_by
     * @return  bool $result
     * @desc Set the user id of the last user that modify a page.
     */
    public function set_last_modif_by($last_modif_by)
    {
        if ($this->get_id() > 0)
        {
            $ret = $this->update_field('last_modif_by',$last_modif_by);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param string $lang
     * @return  bool $result
     * @desc Set the lang of the page (empty for default lang)
     */
    public function set_lang($lang)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('lang',$lang);
            if ($ret) $this->set_log_modif_page('lang',$this->get_lang(),$lang);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param int $form_refid
     * @return  bool $result
     * @desc Publish a form (by it's id) on the page.
     */
    public function set_form_refid($form_refid)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('form_refid',$form_refid);
            $this->update_sphinx();
            if ($ret) $this->set_log_modif_page('form_refid',$this->get_form_refid(),$form_refid);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @param bool $what_modified
     * @param bool $old_value
     * @param bool $new_value
     * @return  bool $result
     * @desc Fill the log table with the current modification
     */
    public function set_log_modif_page($what_modified,$old_value,$new_value)
    {
        global $current_user;
        $this->set_last_modif_by($current_user->get_id());
        $requete = "INSERT INTO log_modif_page
                                (page_refid, user_refid, type_modif, old_value, new_value)
                         VALUES (".$this->get_id().",".$current_user->get_id().",'".addslashes($what_modified)."', '".addslashes($old_value)."','".addslashes($new_value)."')";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        return $sql;
    }
    /**
     * @param bool $parent_refid
     * @return  bool $result
     * @desc Set the page parent for menu / breadcrumbs
     */
    public function set_parent_refid($parent_refid)
    {
        if ($this->get_id() > 0)
        {
            $ret =  $this->update_field('parent_refid',$parent_refid);
            if ($ret) $this->set_log_modif_page('parent_refid',$this->get_parent_refid(),$parent_refid);
            $this->refresh_sitemap();
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @return  string $properties[]
     * @desc list all page properties and model
     */
    public function list_properties()
    {
        return array(
                "url"                 => array("name" => "url",                       "type" => "text",     "field" => "url"),
                "in_menu"             => array("name" => "in_menu",                   "type" => "checkbox", "field" => "in_menu"),
                "use_cache"           => array("name" => "use_cache",                 "type" => "checkbox", "field" => "use_cache"),
                "permit_comment"      => array("name" => "permit_comment",            "type" => "checkbox", "field" => "permit_comment"),
                "model_refid"         => array("name" => "model",                     "type" => "select",   "field" => "model_refid", "source_table" => "model","selectid"=>"id", "selectname"=>"name","order"=>"name"),
                "meta"                => array("name" => "meta",                      "type" => "textarea", "field" => "meta"),
                "js"                  => array("name" => "JS library",                "type" => "select",   "field" => "js", "source_dir" => "js", "option" => "multiple" , "optgroup" => 1, "postprocess" => "process_js"),
                "js_external"         => array("name" => "JS External lib (sep: ,)",  "type" => "textarea", "field" => "js_external", "postprocess" => "process_js_external"),
                "js_code"             => array("name" => "JS code",                   "type" => "textarea", "field" => "js_code"),
                "css"                 => array("name" => "CSS library",               "type" => "select",   "field" => "css", "source_dir"=>"css", "option" => "multiple" , "optgroup" => 1, "postprocess" => "process_css"),
                "css_code"            => array("name" => "CSS code",                  "type" => "textarea", "field" => "css_code"),
                "priority"            => array("name" => "Sitemap priority",          "type" => "slider",   "field" => "priority", "min" => 0, "max" => 1, "step"=>0.1 ,"default" => 0.5),
                "changeFreq_refid"    => array("name" => "Sitemap changeFreq",        "type" => "select",   "field" => "changeFreq_refid", "source_table" => "sitemapFreq","selectid"=>"id", "selectname"=>"name","order"=>"name"),
                "social_links"        => array("name" => "Social links",              "type" => "select",   "field" => "social_media", "source_table" => "social_media","selectid"=>"id", "selectname"=>"name","order"=>"name", "option" => "multiple", "postprocess" => "process_social" ),
                "exclude_sitemap"     => array("name" => "Exclude page from sitemap", "type" => "checkbox", "field" => "exclude_sitemap" ),
                "exclude_search"      => array("name" => "Exclude page from search",  "type" => "checkbox", "field" => "exclude_search" ),
                "keyword"             => array("name" => "Mots clefs(séparer par ,)", "type" => "textarea", "field" => "keyword"),
        );
    }
    /**
     * @param string $field
     * @return  string $result
     * @desc Get a property value
     */
    public function get_property($field)
    {
        $fct = "get_".$field;
        return $this->$fct();
    }
    /**
     * @param string $value
     * @return  string $result
     * @desc Encode a list of css file (array) into a json array
     */
    private function process_css($value)
    {
        $value = json_encode($value);
        return $value;
    }
    /**
     * @param string $value
     * @return  string $result
     * @desc Encode a list of external js url (array) into a json array
     */
    private function process_js_external($value)
    {
        if ($value."x" == "x") return "";
        $value = preg_split('/,/',$value);
        $value = json_encode($value);
        return $value;
    }
    /**
     * @param string $value
     * @return  string $result
     * @desc Encode a list of js file (array) into a json array
     */
    private function process_js($value)
    {
        $value = json_encode($value);
        return $value;
    }
    /**
     * @param string $value
     * @return  string $result
     * @desc Encode a list of social media id (from social_media table) (array) into a json array
     */
    private function process_social($value)
    {
        $value = json_encode($value);
        return $value;
    }
    /**
     * @param string $post[]
     * @return  bool $result
     * @desc Process all properties value to store in db
     */
    public function parse_POST_and_update($post)
    {
        $this->id = $post['id'];
        $this->pId = $post['id'];
        $this->fetch($this->get_id());
        $model = $this->list_properties();
        $error = false;
        foreach($model as $key=>$val)
        {
            if ($val['type'] == 'checkbox' && !isset($post[$val['field']]))
            {
                $func = "set_".$val['field'];
                $res = $this->$func(0);
                if (!$res && !$error) $error=true;
            }
            foreach($post as $field => $value)
            {
                if ($val['field'] == $field)
                {
                    $func = "set_".$field;
                    switch($val['type'])
                    {
                        case 'text':
                        case 'textarea':
                        case 'select':
                        {
                            if (isset($val['postprocess']))
                            {
                                $pp = $val['postprocess'];
                                $value = $this->$pp($value);

                            }
                            $res = $this->$func($value);
                            if (!$res && !$error) $error=true;
                        }
                        break;
                        case 'slider':
                            if ($value < $val['min']) { $value = $min; }
                            if ($value > $val['max']) { $value = $max; }
                            if (isset($val['postprocess']))
                            {
                                $pp = $val['postprocess'];
                                $value = $this->$pp($value);

                            }
                            $res = $this->$func($value);
                            if (!$res && !$error) $error=true;
                        break;
                        case 'checkbox':
                        {
                            if (isset($value))
                            {
                                $res = $this->$func(1);
                                if (!$res && !$error) $error=true;
                            } else {
                                $res = $this->$func("0");
                                if (!$res && !$error) $error=true;
                            }
                        }
                        break;
                    }
                    break;
                }
            }
        }
        if (!$error) return true;
        else return false;
    }
    /**
     * @param string $base_url
     * @param string $url
     * @param string $iter
     * @param string $lang
     * @return  string $new_url
     * @desc Find a free url name according to the lang. It had a numerical suffixe at the end of the original url
     */
    public function find_new_url($base_url,$url,$iter=false,$lang=false)
    {
        if ($iter){
            $url = $base_url."_".$iter;
        }
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE url = '".$url."'";
       if ($lang)
       {
           $requete .= "AND lang = '".$this->get_lang()."' ";
       }
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            if (!$iter) { $iter = 1;} else { $iter++; }

            $free = $this->find_new_url($base_url,$url,$iter++,$lang);
            return $free;
        } else {
            return $url;
        }
    }
    /**
     * @param string $lang
     * @return  string $result
     * @desc Add a new page. Return false on error, the id of new page on success
     */
    public function add($lang=false)
    {
        global $current_user;
        $url = "New";
        $url = $this->find_new_url($url, $url,false,$lang);
        $page_lang = "null";
        if ($lang)
        {
            $page_lang = "'".addslashes($lang)."'";
        }
        $requete="INSERT INTO page
                              (`title`,`content`,`model_refid`,`in_menu`,`url`,`parent_refid`,`meta`,`js`,`js_code`,`css`,`css_code`,`active`,`date_creation`,`author_refid`,`last_modif_by`,`lang`)
                       VALUES ('New','',1,1,'".$url."',null,'','','','','',1,now(),".$current_user->get_id().",".$current_user->get_id().",".$page_lang.")";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            global $trigger;
            $trigger->run_trigger("ADD_PAGE", $this,$current_user);
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $ret = $this->db->last_insert_id($this->table);
        if ($ret > 0){
            $this->fetch($ret);
            $this->refresh_sitemap();
            $this->insert_sphinx();
        }
        return $ret;
    }
    /**
     * @return  bool $result
     * @desc Delete a page and clean database
     */
    public function del()
    {
        $requete = "SELECT id
                      FROM page
                     WHERE is_a_draft_for = ".$this->get_id();
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            while($res = $this->db->fetch_object($sql))
            {
                $p = new page($this->db);
                $p->fetch($res->id);
                $p->del();
            }
        }
        $requete = "DELETE FROM section
                          WHERE page_refid = ".$this->get_id();
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $requete = "DELETE FROM group_publication_page
                          WHERE page_refid = ".$this->get_id();
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $requete = "DELETE FROM ".$this->table."
                          WHERE id = ".$this->id;
        $sql1 = $this->db->query($requete);
        if ($sql1)
        {
            global $trigger,$current_user;
            $trigger->run_trigger("DEL_PAGE", $this,$current_user);
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $ret =  ($sql&&$sql1);
        $this->refresh_sitemap();
        $this->delete_sphinx();
        return $ret;
    }
    /**
     * @param string $lang
     * @param bool $rename
     * @return  bool $result
     * @desc Clone a page to a different lang, or to a different name.
     */
    public function page_clone($lang=false,$rename=true)
    {
        global $current_user;
        $url = $this->get_url();
        if ($rename)
            $url = $this->find_new_url("Clone_".$url, "Clone_".$url,false,$lang);
        $page_lang = "null";
        if ($lang)
        {
            $page_lang = "'".addslashes($lang)."'";
        }


        $requete = "INSERT INTO page
                                (`title`,
                                 `content`,
                                 `model_refid`,
                                 `in_menu`,
                                 `url`,
                                 `parent_refid`,
                                 `meta`,
                                 `js`,
                                 `js_external`,
                                 `js_code`,
                                 `css`,
                                 `css_code`,
                                 `active`,
                                 `date_creation`,
                                 `keyword`,
                                 `author_refid`,
                                 `extra_params`,
                                 `social_media`,
                                 `plugins`,
                                 `use_cache`,
                                 `publication_status_refid`,
                                 `lang`,
                                 `form_refid`,
                                 `priority`,
                                 `exclude_search`,
                                 `exclude_sitemap`,
                                 `changeFreq_refid`
                                )
                         VALUES (
                                '".($rename  ?  $_("Clone"). " " : "").addslashes($this->get_title())."',
                                ".($this->get_content() ? "'".addslashes($this->get_content())."'" : "null").",
                                ".($this->get_model_refid() ? $this->get_model_refid() : "null").",
                                ".($this->get_in_menu() ? $this->get_in_menu() : 0).",
                                '".addslashes($url)."',
                                ".($this->get_parent_refid() ? $this->get_parent_refid() : "null").",
                                ".($this->get_meta() ? "'".addslashes($this->get_meta())."'" : "null").",
                                ".($this->get_js() ? "'".addslashes(json_encode($this->get_js()))."'" : "null").",
                                ".($this->get_js_external() ? "'".addslashes(json_encode($this->get_js_external()))."'" : "null").",
                                ".($this->get_js_code() ? "'".addslashes($this->get_js_code())."'" : "null").",
                                ".($this->get_css() ? "'".addslashes(json_encode($this->get_css()))."'" : "null").",
                                ".($this->get_css_code() ? "'".addslashes($this->get_css_code())."'" : "null").",
                                ".($this->get_active() ? $this->get_active() : "0").",
                                now(),
                                ".($this->get_keyword() ? "'".addslashes($this->get_keyword())."'" : "null").",
                                ".$current_user->get_id().",
                                ".($this->get_extra_params() ? "'".addslashes($this->get_extra_params())."'" : "null").",
                                ".($this->get_social_media() ? "'".addslashes($this->get_social_media())."'" : "null").",
                                ".($this->get_plugins() ? "'".addslashes($this->get_plugins())."'" : "null").",
                                ".($this->get_use_cache() ? $this->get_use_cache() : "0").",
                                1,
                                ".$page_lang.",
                                ".($this->get_form_refid() > 0 ? $this->get_form_refid() : "null").",
                                ".($this->get_priority() ? "'".addslashes($this->get_priority())."'" : "0.5").",
                                ".($this->get_exclude_search() ? addslashes($this->get_exclude_search()) : "0").",
                                ".($this->get_exclude_sitemap() ? addslashes($this->get_exclude_sitemap()) : "0").",
                                ".($this->get_changeFreq_refid()  ?  addslashes($this->get_changeFreq_refid()) : "null")."
                                ) ";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }

        $newId =  $this->db->last_insert_id($this->table);
        if ($newId > 0)
        {
            $requete = "SELECT `id`
                          FROM section
                         WHERE `page_refid` = ".$this->id."
                      ORDER BY `order`";
            $sql = $this->db->query($requete);
            load_alternative_class('class/section.class.php');
            while($res = $this->db->fetch_object($sql))
            {
                $s = new section($this->db);
                $s->fetch($res->id);
                $s->section_clone($newId,$lang);
            }
            $this->refresh_sitemap();
            $this->fetch($newId);

            $this->insert_sphinx();
            global $trigger,$current_user;
            $trigger->run_trigger("CLONE_PAGE", $this,$current_user,$newId);
            return $newId;
        } else {
            return false;
        }

    }
    /**
     * @return  string $result
     * @desc Make a draft for a page. Return false on error, the id of new page on success
     */
    public function make_a_draft()
    {
        global $current_user;
        $url = $this->get_url();

        $requete = "INSERT INTO page
                                (`title`,`content`,`model_refid`,`in_menu`,`url`,
                                 `parent_refid`,`meta`,`js`,`js_external`,`js_code`,
                                 `css`,`css_code`,`active`,`date_creation`,`keyword`,`author_refid`,
                                 `extra_params`,`social_media`, `plugins`,`use_cache`,`publication_status_refid`, `is_a_draft_for`, `lang`
                                )
                         VALUES ('draft ".addslashes($this->pTitle)."','".addslashes($this->pContent)."',".$this->pModelrefid.", '".$this->pInmenu."','".addslashes($url)."',
                                  ".$this->pParentrefid.",'".addslashes($this->pMeta)."','".addslashes(json_encode($this->pJs))."','".addslashes(json_encode($this->pJs_external))."','".addslashes($this->pJs_code)."',
                                  '".addslashes(json_encode($this->pCss))."', '".addslashes($this->pCss_code)."',".$this->pActive.",now(), '".addslashes($this->pKeyword)."', ".$current_user->get_id().",
                                  '".addslashes($this->get_extra_params())."', '".addslashes($this->get_social_media())."','".addslashes($this->get_plugins())."',".$this->get_use_cache().",1, ".$this->get_id().", '".addslashes($this->get_lang())."'
                                ) ";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $newId =  $this->db->last_insert_id($this->table);
        if ($newId > 0)
        {
            $requete = "SELECT `id`
                          FROM section
                         WHERE `page_refid` = ".$this->id."
                      ORDER BY `order`";
            $sql = $this->db->query($requete);
            load_alternative_class('class/section.class.php');
            while($res = $this->db->fetch_object($sql))
            {
                $s = new section($this->db);
                $s->fetch($res->id);
                $s->section_clone($newId);
            }
            return $newId;
        } else {
            return false;
        }

    }
    /**
     * @return  bool $result
     * @desc Check if a page publication status allow search engine indexing
     */
    public function publication_status_allow_sphinx_index()
    {
        load_alternative_class('class/publication_status.class.php');
        $ps = new publication_status($this->db);
        $ps->fetch($this->get_publication_status_refid());
        if ($ps->get_in_search_engine()> 0) return true;
        else return false;
    }
    /**
     * @desc Insert a new page into search engine. If page is qualified to.
     */
    public function insert_sphinx()
    {
        if ($this->get_is_a_draft_for() > 0) return ;
        if (!$this->publication_status_allow_sphinx_index()) return ;
        global $conf;
        if ($this->pExclude_search != 1 && $conf->sphinx_index_type == 'rt')
        {
            $date_maj=0;
            if ($this->get_date_derniere_maj()."x" != "x")
            {
                $date_maj = $this->get_date_derniere_maj();
            } else {
                $date_maj = $this->get_date_creation();
            }
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $date_maj);
            $date_maj =  $date->format('U');
            load_alternative_class('class/sphinx.class.php');
            $sp = new Sphinx();
            $a = array(
                 "id" => $this->get_id(),
                 "page_id" => $this->get_id(),
                 "title" => addslashes($this->get_title()." "),
                 "meta" => addslashes($this->get_meta()." "),
                 "keyword" => addslashes($this->get_keyword()." "),
                 "content" => addslashes($this->get_formated_content()." "),
                 "date_modification" => $date_maj
            );
            $res = $sp->insert($a);
        }
    }
    /**
     * @desc Update search engine data if the page is qualified to.
     */
    public function update_sphinx()
    {
        global $conf;
        if ($this->get_is_a_draft_for() > 0) return ;

        if ( ! $this->publication_status_allow_sphinx_index()) return ;
        if ($this->pExclude_search != 1  && $conf->sphinx_index_type == 'rt')
        {
            $date_maj=0;
            if ($this->get_date_derniere_maj()."x" != "x")
            {
                $date_maj = $this->get_date_derniere_maj();
            } else {
                $date_maj = $this->get_date_creation();
            }
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $date_maj);
            $date_maj =  $date->format('U');
            load_alternative_class('class/sphinx.class.php');
            $sp = new Sphinx();
            $a = array(
                    "id" => $this->get_id(),
                    "page_id" => $this->get_id(),
                    "title" => addslashes($this->get_title()." "),
                    "meta" => addslashes($this->get_meta()." "),
                    "keyword" => addslashes($this->get_keyword()." "),
                    "content" => addslashes($this->get_formated_content()." "),
                    "date_modification" => $date_maj
            );
            $res = $sp->update($a);
            return $res;
        }
    }
    /**
     * @param bool $force
     * @desc Delete a page index from search engine.
     */
    public function delete_sphinx($force=false)
    {
        if (!$force) if ($this->get_is_a_draft_for() > 0) return ;
        if (!$force) if ( !$this->publication_status_allow_sphinx_index()) return ;
        load_alternative_class('class/sphinx.class.php');
        $sp = new Sphinx();
        $res = $sp->delete($this->get_id());
    }
    /**
     * @return  string $result
     * @desc The content ready for search indexation
     */
    private function get_formated_content()
    {
        global $conf;
        ob_start();
        $rem = $conf->admin_mode;
        $conf->admin_mode = false;
        dispatch($this->db, ($this->get_url()."x" == "x"?"home":$this->get_url()),$search=true);
        $conf->admin_mode = $rem;
        $result = ob_get_clean();
        $this->set_generated_formated_content($result);
        return $result;
    }
    /**
     * @return  string $result
     * @desc Export a page into XML
     */
    public function export()
    {
        if ($this->get_id()>0)
        {
            $dom = new DOMDocument("1.0", "utf-8");
            $dom->formatOutput = true;
            $export = $dom->createElement('export');
            $page = $dom->createElement('page');
            $content = $dom->createElement('content');
            $content_cdata = $dom->createCDATASection($this->get_content());
            $content->appendChild($content_cdata);
            $page->appendChild($content);

            $title = $dom->createElement('title');
            $title_cdata = $dom->createCDATASection($this->get_title());
            $title->appendChild($title_cdata);
            $page->appendChild($title);

            $url = $dom->createElement('url',$this->get_url());
            $page->appendChild($url);
            $sections=$this->get_all_section();
            $section = $dom->createElement('sections');
            if (is_array($sections))
            {
                foreach($sections as $key => $val)
                {
                    $section_xml = $dom->createElement('section');
                    foreach($val as $key_s => $val_s)
                    {
                        $sect = false;
                        if ($key_s == "id") continue;
                        if ($key_s == "title" || $key_s == "content")
                        {
                            $sect = $dom->createElement($key_s);
                            $tmp = $dom->createCDATASection($val_s);
                            $sect->appendChild($tmp);
                        } else {
                            $sect = $dom->createElement($key_s,$val_s);
                        }
                        $section_xml->appendChild($sect);
                    }
                    $section->appendChild($section_xml);
                }
            }
            $page->appendChild($section);
            $prop = $dom->createElement('properties');
            $properties = $this->list_properties();
            foreach($properties as $key => $val)
            {
                $p = $dom->createElement($val['field']);
                $val = json_encode($this->get_property($val['field']));
                $tmp = $dom->createCDATASection($val);
                $p->appendChild($tmp);

                $prop->appendChild($p);
            }
            $page->appendChild($prop);

            //extra_params
            $extra_params = $dom->createElement('extra_params');
            $extra_params_cdata = $dom->createCDATASection($this->get_extra_params());
            $extra_params->appendChild($extra_params_cdata);
            $page->appendChild($extra_params);

            //plugin
            $plugins = $dom->createElement('plugins_data');
            $plugins_cdata = $dom->createCDATASection($this->get_plugins());
            $plugins->appendChild($plugins_cdata);
            $page->appendChild($plugins);

            //lang
            $lang = $dom->createElement('lang',$this->get_lang());
            $page->appendChild($lang);

            //Author
            $a = $this->get_author();
            $al = $dom->createElement('author_login',$a->get_login());
            $page->appendChild($al);
            $ae = $dom->createElement('author_email',$a->get_email());
            $page->appendChild($ae);
            $an = $dom->createElement('author_name',$a->get_name());
            $page->appendChild($an);
            $af = $dom->createElement('author_firstname',$a->get_firstname());
            $page->appendChild($af);

            $model_refid = $this->get_model_refid();
            if (isset($model_refid) && $this->get_model_refid() > 0)
            {
                $m = $dom->createElement('template');
                load_alternative_class('class/template.class.php');
                $t = new template($this->db);
                $t->fetch($this->get_model_refid());

                $mp = $dom->createElement("path");
                $val = $t->get_path();
                $tmp = $dom->createCDATASection($val);
                $mp->appendChild($tmp);

                $m->appendChild($mp);

                $mc = $dom->createElement("content");
                $val = $t->get_content();
                $tmp = $dom->createCDATASection($val);
                $mc->appendChild($tmp);

                $m->appendChild($mc);

                $mn = $dom->createElement("name");
                $val = $t->get_name();
                $tmp = $dom->createCDATASection($val);
                $mn->appendChild($tmp);

                $m->appendChild($mn);


                $mp = $dom->createElement("plugins");
                $val = $t->get_plugins();
                $tmp = $dom->createCDATASection($val);
                $mp->appendChild($tmp);

                $m->appendChild($mp);

                $me = $dom->createElement("extra_params");
                $val = $t->get_extra_params();
                $tmp = $dom->createCDATASection($val);
                $me->appendChild($tmp);

                $m->appendChild($me);

                $page->appendChild($m);
            }

            $ddm = $dom->createElement('date_derniere_modification',$this->get_date_derniere_maj());
            $page->appendChild($ddm);
            $dc = $dom->createElement('date_creation',$this->get_date_creation());
            $page->appendChild($dc);
            $de = $dom->createElement('date_exportation',time());
            $page->appendChild($de);
            $export->appendChild($page);
            $dom->appendChild($export);
            $xml = $dom->saveXML();

            global $trigger,$current_user;
            $trigger->run_trigger("EXPORT_PAGE_XML", $this,$current_user,$xml);

            return $xml;
        }
    }
    /**
     * @param int $id
     * @param bool $search_mode
     * @return  bool $result
     * @desc Check if publication right is public or not
     */
    public function check_status_publication($id=false, $search_mode)
    {
        if ($search_mode) return true;
        if (!$id) $id = $this->get_id();
        if ($id>0)
        {
            $requete = "SELECT publication_status.is_public
                          FROM publication_status,
                               page
                         WHERE publication_status.id = page.publication_status_refid
                           AND page.id = ".$id;
            $sql = $this->db->query($requete);
            if ($sql && $this->db->num_rows($sql) > 0)
            {
                $res = $this->db->fetch_object($sql);
                if ($res->is_public == 1) return true;
            } else {
                return false;
            }
        }
    }
    /**
     * @param int $id
     * @param bool $search_mode
     * @return  bool $result
     * @desc Check if publication right is allowed for a publication group
     */
    public function check_group_publication($id=false,$search_mode)
    {
        if ($search_mode) return true;
        if (!$id) $id = $this->get_id();
        if ($id>0)
        {
            $requete = "SELECT *
                          FROM group_publication,
                               group_publication_page
                         WHERE group_publication_page.group_publication_refid = group_publication.id
                           AND group_publication_page.page_refid = ".$id;
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $a = array();
                while($res = $this->db->fetch_object($sql))
                {
                    if ($res->is_public == 1)
                    {
                        return true;
                    }
                    $a[$res->id]=$res->id;
                }
                global $current_user;
                if ($current_user->get_id()>0)
                {
                    $requete = "SELECT *
                                  FROM group_publication_user
                                 WHERE user_refid = ".$current_user->get_id();
                    $sql = $this->db->query($requete);
                    if ($sql)
                    {
                        while ($res = $this->db->fetch_object($sql))
                        {
                            if (in_array($res->group_publication_refid, $a))
                            {
                                return true;
                            }
                        }
                        return false;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
    /**
     * @return  string $section[]
     * @desc Get all section object of a page
     */
    public function get_section()
    {
        if ($this->get_id()>0)
        {
            if (! $this->pCacheSection)
            {
                $requete = "SELECT id
                              FROM section
                             WHERE active = 1
                               AND page_refid = ".$this->get_id()."
                          ORDER BY `order`";
                $sql1 = $this->db->query($requete);
                $tmp = array();
                if ($sql1 && $this->db->num_rows($sql1) > 0)
                {
                    load_alternative_class('class/section.class.php');
                    while($res1 = $this->db->fetch_object($sql1))
                    {
                        $sect = new section($this->db);
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
    /**
     * @return  bool $result
     * @desc Does this page as a draft?
     */
    public function has_a_draft()
    {
        $requete = "SELECT *
                      FROM page
                     WHERE is_a_draft_for = ".$this->get_id();
        $sql = $this->db->query($requete);
        if ($sql)
        {
            if ($this->db->num_rows($sql) > 0) return true;
            else return false;
        } else {
            return false;
        }
    }
    /**
     * @return  int $result
     * @desc Return the is a the published page
     */
    public function publish_draft()
    {

        $id = $this->get_is_a_draft_for();
        $this->db->begin();
        $requete = "UPDATE page
                       SET  ";
        if ($this->get_title()."x" != "x")
            $requete .= "      `title` = '".addslashes($this->get_title())."',";
            if ($this->get_content()."x" != "x")
            $requete .= "      `content` = '".addslashes($this->get_content())."',";
            if ($this->get_model_refid()."x" != "x")
            $requete .= "      `model_refid` = '".$this->get_model_refid()."',";
            if ($this->get_in_menu()."x" != "x")
            $requete .= "      `in_menu` = '".$this->get_in_menu()."',";
            if ($this->get_url()."x" != "x")
            $requete .= "      `url` = '".addslashes($this->get_url())."',";
            if ($this->get_parent_refid()."x" != "x")
            $requete .= "      `parent_refid` = '".$this->get_parent_refid()."',";
            if ($this->get_meta()."x" != "x")
            $requete .= "      `meta` = '".addslashes($this->get_meta())."',";
            if (json_encode($this->get_js())."x" != "x")
            $requete .= "      `js` = '".addslashes(json_encode($this->get_js()))."',";
            if ($this->get_js_external()."x" != "x")
            $requete .= "      `js_external` = '".addslashes($this->get_js_external())."',";
            if ($this->get_js_code()."x" != "x")
            $requete .= "      `js_code` = '".addslashes($this->get_js_code())."',";
            if (json_encode($this->get_css())."x" != "x")
            $requete .= "      `css` = '".addslashes(json_encode($this->get_css()))."',";
            if ($this->get_css_code()."x" != "x")
            $requete .= "      `css_code` = '".addslashes($this->get_css_code())."',";
            if ($this->get_active()."x" != "x")
            $requete .= "      `active` = '".$this->get_active()."',";
            if ($this->get_date_derniere_maj()."x" != "x")
            $requete .= "      `date_derniere_maj` = '".addslashes($this->get_date_derniere_maj())."',";
            if ($this->get_last_modif_by()."x" != "x")
            $requete .= "      `last_modif_by` = '".$this->get_last_modif_by()."',";
            if ($this->get_changeFreq_refid()."x" != "x")
            $requete .= "      `changeFreq_refid` = '".$this->get_changeFreq_refid()."',";
            if ($this->get_priority()."x" != "x")
            $requete .= "      `priority` = '".$this->get_priority()."',";
            if ($this->get_exclude_sitemap()."x" != "x")
            $requete .= "      `exclude_sitemap` = '".$this->get_exclude_sitemap()."',";
            if ($this->get_exclude_search()."x" != "x")
            $requete .= "      `exclude_search` = '".$this->get_exclude_search()."',";
            if ($this->get_keyword()."x" != "x")
            $requete .= "      `keyword` = '".addslashes($this->get_keyword())."',";
            if ($this->get_generated_formated_content()."x" != "x")
            $requete .= "      `generated_formated_content` = '".addslashes($this->get_generated_formated_content())."',";
            if ($this->get_page_on_disk()."x" != "x")
            $requete .= "      `page_on_disk` = '".$this->get_page_on_disk()."',";
            if ($this->get_permit_comment()."x" != "x")
            $requete .= "      `permit_comment`= '".$this->get_permit_comment()."',";
            if ($this->get_author_refid()."x" != "x")
            $requete .= "      `author_refid` = '".$this->get_author_refid()."',";
            if ($this->get_extra_params()."x" != "x")
            $requete .= "      `extra_params` = '".addslashes($this->get_extra_params())."',";
            if ($this->get_is_locked_for_edition()."x" != "x")
            $requete .= "      `is_locked_for_edition` = '".$this->get_is_locked_for_edition()."',";
            if ($this->get_social_media()."x" != "x")
            $requete .= "      `social_media` = '".addslashes($this->get_social_media())."',";
            if ($this->get_plugins()."x" != "x")
            $requete .= "      `plugins` = '".addslashes($this->get_plugins())."',";
            if ($this->get_use_cache()."x" != "x")
            $requete .= "      `use_cache` = '".$this->get_use_cache()."',";
            if ($this->get_is_a_draft_for()."x" != "x")
            $requete .= "      `is_a_draft_for` = '".$this->get_is_a_draft_for()."',";
            if ($this->get_lang()."x" != "x")
                $requete .= "      `lang` = '".$this->get_lang()."',";
            $requete .= "       publication_status_refid=2`
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        $requete = "DELETE FROM section
                          WHERE page_refid = ".$id;
        $sql1 = $this->db->query($requete);
        $requete = "UPDATE section
                       SET page_refid = ".$id ."
                     WHERE page_refid = ".$this->get_id();
        $sql2 = $this->db->query($requete);

        $requete = "DELETE FROM section
                          WHERE page_refid = ".$this->get_id();
        $sql3 = $this->query($requete);

        $requete = "DELETE FROM page
                          WHERE id = ".$this->get_id();
        $sql4 = $this->query($requete);
        //Idem pour les sections => on efface les anciennes, on change l'id de page des nouvelle
        //On supprime le draft
        if ($sql && $sql1 && $sql2 && $sql3 && $sql4) {
            $this->db->commit();
            global $trigger,$current_user;
            $trigger->run_trigger("PUBLISH_DRAFT", $this,$current_user);
        }
        else $this->db->rollback();
        return $id;
    }
    /**
     * @return  int $result
     * @desc Make a revision of a page. return the id of the revision or false on failure
     */
    public function make_a_revision()
    {
        if ($this->get_id()>0)
        {
            $requete = "INSERT INTO page_revision
                                   (page_refid)
                            VALUES (".$this->id.")";
            $sql = $this->db->query($requete);
            $id_rev = $this->db->last_insert_id($this->table);
            $requete = "UPDATE page_revision
                           SET";
            if ($this->get_title()."x" != "x")
            $requete .= "      `title` = '".addslashes($this->get_title())."',";
            if ($this->get_content()."x" != "x")
            $requete .= "      `content` = '".addslashes($this->get_content())."',";
            if ($this->get_model_refid()."x" != "x")
            $requete .= "      `model_refid` = '".$this->get_model_refid()."',";
            if ($this->get_in_menu()."x" != "x")
            $requete .= "      `in_menu` = '".$this->get_in_menu()."',";
            if ($this->get_url()."x" != "x")
            $requete .= "      `url` = '".addslashes($this->get_url())."',";
            if ($this->get_parent_refid()."x" != "x")
            $requete .= "      `parent_refid` = '".$this->get_parent_refid()."',";
            if ($this->get_meta()."x" != "x")
            $requete .= "      `meta` = '".addslashes($this->get_meta())."',";
            if (json_encode($this->get_js())."x" != "x")
            $requete .= "      `js` = '".addslashes(json_encode($this->get_js()))."',";
            if ($this->get_js_external()."x" != "x")
            $requete .= "      `js_external` = '".addslashes($this->get_js_external())."',";
            if ($this->get_js_code()."x" != "x")
            $requete .= "      `js_code` = '".addslashes($this->get_js_code())."',";
            if (json_encode($this->get_css())."x" != "x")
            $requete .= "      `css` = '".addslashes(json_encode($this->get_css()))."',";
            if ($this->get_css_code()."x" != "x")
            $requete .= "      `css_code` = '".addslashes($this->get_css_code())."',";
            if ($this->get_active()."x" != "x")
            $requete .= "      `active` = '".$this->get_active()."',";
            if ($this->get_date_creation()."x" != "x")
            $requete .= "      `date_creation` = '".addslashes($this->get_date_creation())."',";
            if ($this->get_date_derniere_maj()."x" != "x")
            $requete .= "      `date_derniere_maj` = '".addslashes($this->get_date_derniere_maj())."',";
            if ($this->get_last_modif_by()."x" != "x")
            $requete .= "      `last_modif_by` = '".$this->get_last_modif_by()."',";
            if ($this->get_changeFreq_refid()."x" != "x")
            $requete .= "      `changeFreq_refid` = '".$this->get_changeFreq_refid()."',";
            if ($this->get_priority()."x" != "x")
            $requete .= "      `priority` = '".$this->get_priority()."',";
            if ($this->get_exclude_sitemap()."x" != "x")
            $requete .= "      `exclude_sitemap` = '".$this->get_exclude_sitemap()."',";
            if ($this->get_exclude_search()."x" != "x")
            $requete .= "      `exclude_search` = '".$this->get_exclude_search()."',";
            if ($this->get_keyword()."x" != "x")
            $requete .= "      `keyword` = '".addslashes($this->get_keyword())."',";
            if ($this->get_generated_formated_content()."x" != "x")
            $requete .= "      `generated_formated_content` = '".addslashes($this->get_generated_formated_content())."',";
            if ($this->get_page_on_disk()."x" != "x")
            $requete .= "      `page_on_disk` = '".$this->get_page_on_disk()."',";
            if ($this->get_permit_comment()."x" != "x")
            $requete .= "      `permit_comment`= '".$this->get_permit_comment()."',";
            if ($this->get_author_refid()."x" != "x")
            $requete .= "      `author_refid` = '".$this->get_author_refid()."',";
            if ($this->get_extra_params()."x" != "x")
            $requete .= "      `extra_params` = '".addslashes($this->get_extra_params())."',";
            if ($this->get_is_locked_for_edition()."x" != "x")
            $requete .= "      `is_locked_for_edition` = '".$this->get_is_locked_for_edition()."',";
            if ($this->get_social_media()."x" != "x")
            $requete .= "      `social_media` = '".addslashes($this->get_social_media())."',";
            if ($this->get_plugins()."x" != "x")
            $requete .= "      `plugins` = '".addslashes($this->get_plugins())."',";
            if ($this->get_use_cache()."x" != "x")
            $requete .= "      `use_cache` = '".$this->get_use_cache()."',";
            if ($this->get_publication_status_refid()."x" != "x")
            $requete .= "      `publication_status_refid` = '".$this->get_publication_status_refid()."',";
            if ($this->get_is_a_draft_for()."x" != "x")
            $requete .= "      `is_a_draft_for` = '".$this->get_is_a_draft_for()."',";
            if ($this->get_lang()."x" != "x")
            $requete .= "      `lang` = '".$this->get_lang()."',";
            $requete .="       `page_refid` = '".$this->id."'";
            $requete .= "WHERE id = ".$id_rev;
            $sql = $this->db->query($requete);
            $requete= "SELECT id
                         FROM section
                        WHERE page_refid = ".$this->id;
            $sql = $this->db->query($requete);
            load_alternative_class('class/section.class.php');
            while ($res = $this->db->fetch_object($sql))
            {
                $s = new section($this->db);
                $s->fetch($res->id);
                $requete = "INSERT INTO section_revision
                                        (section_refid,page_revision_refid)
                                 VALUES (".$s->get_id().",".$id_rev.")";
                $sql1 = $this->db->query($requete);
                $s_id = $this->db->last_insert_id("section");
                $requete = "UPDATE section_revision
                               SET";
                if ($s->get_content()."x" != "x")
                $requete .="       `content` = '".addslashes($s->get_content())."', ";
                if ($s->get_title()."x" != "x")
                $requete .="       `title`  = '".addslashes($s->get_title())."',";
                if ($s->get_order()."x" != "x")
                $requete .="       `order` = '".$s->get_order()."' ,";
                if ($s->get_active()."x" != "x")
                $requete .="       `active` = '".$s->get_active()."',";
                if ($s->get_date_creation()."x" != "x")
                $requete .="       `date_creation` = '".addslashes($s->get_date_creation())."',";
                if ($s->get_date_derniere_maj()."x" != "x")
                $requete .="       `date_derniere_maj` ='".addslashes($s->get_date_derniere_maj())."',";
                if ($s->get_author_refid()."x" != "x")
                $requete .="       `author_refid` = '".$s->get_author_refid()."' ,";
                if ($s->get_is_locked_for_edition()."x" != "x")
                $requete .="       `is_locked_for_edition` = '".$s->get_is_locked_for_edition()."',";
                if ($s->get_associated_img()."x" != "x")
                $requete .="       `associated_img` = '". addslashes($s->get_associated_img()) ."' ,";
                if ($s->get_last_modif_by()."x" != "x")
                $requete .="       `last_modif_by` = '".$s->get_last_modif_by()."',";
                if ($s->get_lang()."x" != "x")
                    $requete .="       `lang` = '".$s->get_lang()."',";

                $requete .="       `page_revision_refid` = '".$id_rev."'";
                $requete .=" WHERE id = ".$s_id;
                $sql1 = $this->db->query($requete);
            }
            global $trigger,$current_user;
            $trigger->run_trigger("REV_MAKE_PAGE", $this,$current_user);

            return $id_rev;
        } else {
            return false;
        }
    }
}
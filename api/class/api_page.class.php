<?php
load_alternative_class('class/common_soap_object.class.php');
class api_page extends common_soap_object
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
     * @desc List all pages
     * @return array $datas[]
     */
    public function list_all()
    {
        $requete = "SELECT id,
                           lang,
                           title
                      FROM page";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[] = array(
                'id' => $res->id,
                'lang' => $res->lang,
                'title' => $res->title
            );
        }
        return $a;
    }

    /**
     * @param string $short_url
     * @param int $draftFor
     * @return int id
     * @desc Get id of a page by its url
     */

    public function fetch_by_url($url,$draftFor=false)
    {
        return $this->obj->fetch_by_url($url,$draftFor=false);
    }
    /**
     * @param int $id
     * @desc Get all datas
     */
    public function get_all($id)
    {
        $this->load($id);
        if ($this->obj->get_id() > 0)
        {
            $a = array();
            $a['id'] = $this->obj->get_id();
            $a['title'] = $this->obj->get_title();
            $a['url'] = $this->obj->get_url();
            $a['content'] = $this->obj->get_content();
            $a['content_with_section'] = $this->obj->get_content_with_section();
            $a['section_content'] = $this->obj->get_section_content();
            $a['inmenu'] = $this->obj->get_in_menu();
            $a['parentrefid'] = $this->obj->get_parent_refid();
            $a['modelrefid'] = $this->obj->get_model_refid();
            $a['meta'] = $this->obj->get_meta();
            $a['js'] = $this->obj->get_js();
            $a['js_external'] = $this->obj->get_js_external();
            $a['js_code'] = $this->obj->get_js_code();
            $a['css'] = $this->obj->get_css();
            $a['css_code'] = $this->obj->get_css_code();
            $a['active'] = $this->obj->get_active();
            $a['date_creation'] = $this->obj->get_date_creation();
            $a['date_derniere_maj'] = $this->obj->get_date_derniere_maj();
            $a['changeFreq_refid'] = $this->obj->get_changeFreq_refid();
            $a['priority'] = $this->obj->get_priority();
            $a['exclude_sitemap'] = $this->obj->get_exclude_sitemap();
            $a['exclude_search'] = $this->obj->get_exclude_search();
            $a['keyword'] = $this->obj->get_keyword();
            $a['generated_formated_content'] = $this->obj->get_generated_formated_content();

            $a['description_sphinx'] = $this->obj->get_description_sphinx();
            $a['meta_cloud'] = $this->obj->get_meta_cloud();
            $a['page_on_disk'] = $this->obj->get_page_on_disk();
            $a['permit_comment'] = $this->obj->get_permit_comment();
            $a['js_external'] = $this->obj->get_js_external();
            $a['author_refid'] = $this->obj->get_author_refid();
            $a['extra_params'] = $this->obj->get_extra_params();
            $a['is_locked_for_edition'] = $this->obj->get_is_locked_for_edition();
            $a['social_media'] = $this->obj->get_social_media();
            $a['plugins'] = $this->obj->get_plugins();
            $a['use_cache'] = $this->obj->get_use_cache();
            $a['publication_status_refid'] = $this->obj->get_publication_status_refid();
            $a['is_a_draft_for'] = $this->obj->get_is_a_draft_for();
            $a['last_modif_by'] = $this->obj->get_last_modif_by();
            $a['lang'] = $this->obj->get_lang();
            $a['form_refid'] = $this->obj->get_form_refid();

            $requete = "SELECT *
                          FROM publication_status
                         WHERE id = ".$this->obj->get_publication_status_refid();
            $sql = $this->db->query($requete);
            $res = $this->db->fetch_object($sql);
            $a['publication_status_name'] = $res->name;

            return $a;
        } else {
            return false;
        }
    }
    /**
     * @param int $from_page_id
     * @param int $dest_page_id
     * @return  bool $result
     * @desc Link 2 pages together
     */
    public function link($page_id,$to_page_id)
    {
        return $this->obj->link($page_id,$to_page_id);
    }
    /**
     * @return  array $properties[]
     * @desc list all page properties and model
     */
    public function list_properties()
    {
        return $this->obj->list_properties();
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
        return $this->obj->find_new_url($base_url,$url,$iter=false,$lang=false);
    }
    /**
     * @param string $lang
     * @return  string $result
     * @desc Add a new page. Return false on error, the id of new page on success
     */
    public function add($lang=false)
    {
        return $this->obj->add($lang=false);
    }
    /**
     * @param int $id
     * @return  bool $result
     * @desc Delete a page and clean database
     */
    public function del($id)
    {
        $this->load($id);
        return $this->obj->del();
    }
    /**
     * @param int $id
     * @param string $lang
     * @param bool $rename
     * @return  bool $result
     * @desc Clone a page to a different lang, or to a different name.
     */
    public function page_clone($id,$lang=false,$rename=true)
    {
        $this->load($id);
        $this->obj->page_clone($lang=false,$rename=true);
    }
    /**
     * @param int $id
     * @return  string $result
     * @desc Make a draft for a page. Return false on error, the id of new page on success
     */
    public function make_a_draft($id)
    {
        $this->load($id);
        $this->obj->make_a_draft();
    }
    /**
     * @param int $id
     * @return  bool $result
     * @desc Check if a page publication status allow search engine indexing
     */
    public function publication_status_allow_sphinx_index($id)
    {
        $this->load($id);
        $this->obj->publication_status_allow_sphinx_index();
    }
    /**
     * @param int $id
     * @desc Insert a new page into search engine. If page is qualified to.
     */
    public function insert_sphinx($id)
    {
        $this->load($id);
        $this->obj->insert_sphinx();
    }
    /**
     * @param int $id
     * @desc Update search engine data if the page is qualified to.
     */
    public function update_sphinx($id)
    {
        $this->load($id);
        $this->obj->update_sphinx();
    }
    /**
     * @param int $id
     * @param bool $force
     * @desc Delete search engine data if the page is qualified to.
     */
    public function delete_sphinx($id,$force=false)
    {
        $this->load($id);
        $this->obj->delete_sphinx($force);
    }
    /**
     * @param int $id
     * @return  string $result
     * @desc Export a page into XML
     */
    public function export($id)
    {
        $this->load($id);
        return $this->obj->export();
    }
    /**
     * @param int $id
     * @param bool $search_mode
     * @return  bool $result
     * @desc Check if publication right is public or not
     */
    public function check_status_publication($id, $search_mode=false)
    {
        $this->load($id);
        $this->obj->check_status_publication($id, $search_mode);
    }
    /**
     * @param int $id
     * @param bool $search_mode
     * @return  bool $result
     * @desc Check if publication right is allowed for a publication group
     */
    public function check_group_publication($id,$search_mode)
    {
        $this->load($id);
        $this->obj->check_group_publication($id, $search_mode);
    }
    /**
     * @return  bool $result
     * @desc Does this page as a draft?
     */
    public function has_a_draft($id)
    {
        $this->load($id);
        $this->obj->has_a_draft();
    }
    /**
     * @param int $id
     * @return  int $result
     * @desc Return the id a the published page
     */
    public function publish_draft($id)
    {
        $this->load($id);
        $this->obj->publish_draft();
    }
    /**
     * @param int $id
     * @return  int $result
     * @desc Make a revision of a page. return the id of the revision or false on failure
     */
    public function make_a_revision($id)
    {
        $this->load($id);
        $this->obj->make_a_revision();
    }
    /**
     * @desc Get section content
     * @param int $id
     * @return string $section_content
     */
    public function get_section_content($id)
    {
        $this->load($id);
        return $this->obj->get_section_content();
    }
    /**
     * @desc Get form
     * @param int $id
     * @return string $form
     */
    public function get_form($id)
    {
        $this->load($id);
        return $this->obj->get_form();
    }
    /**
     * @desc Get id
     * @param int $id
     * @return int $id
     */
    public function get_id($id)
    {
        $this->load($id);
        return $this->obj->get_id();
    }
    /**
     * @desc Get date creation
     * @param int $id
     * @return datetime $date_creation
     */
    public function get_date_creation($id)
    {
        $this->load($id);
        return $this->obj->get_date_creation();
    }
    /**
     * @desc Get date derniere_maj
     * @param int $id
     * @return datetime $date_derniere_maj
     */
    public function get_date_derniere_maj($id)
    {
        $this->load($id);
        return $this->obj->get_date_derniere_maj();
    }
    /**
     * @desc Get title
     * @param int $id
     * @return string $title
     */
    public function get_title($id)
    {
        $this->load($id);
        return $this->obj->get_title();
    }
    /**
     * @desc Get url
     * @param int $id
     * @return string $url
     */
    public function get_url($id)
    {
        $this->load($id);
        return $this->obj->get_url();
    }
    /**
     * @desc Get model refid
     * @param int $id
     * @return int $model_refid
     */
    public function get_model_refid($id)
    {
        $this->load($id);
        return $this->obj->get_model_refid();
    }
    /**
     * @desc Get content
     * @param int $id
     * @return string $content
     */
    public function get_content($id)
    {
        $this->load($id);
        return $this->obj->get_content();
    }
    /**
     * @desc Get in menu
     * @param int $id
     * @return int $in_menu
     */
    public function get_in_menu($id)
    {
        $this->load($id);
        return $this->obj->get_in_menu();
    }
    /**
     * @desc Get parent refid
     * @param int $id
     * @return int $parent_refid
     */
    public function get_parent_refid($id)
    {
        $this->load($id);
        return $this->obj->get_parent_refid();
    }
    /**
     * @desc Get meta
     * @param int $id
     * @return string $meta
     */
    public function get_meta($id)
    {
        $this->load($id);
        return $this->obj->get_meta();
    }
    /**
     * @desc Get js
     * @param int $id
     * @return string $js
     */
    public function get_js($id)
    {
        $this->load($id);
        return json_encode($this->obj->get_js());
    }
    /**
     * @desc Get js external
     * @param int $id
     * @return string $js_external
     */
    public function get_js_external($id)
    {
        $this->load($id);
        return $this->obj->get_js_external();
    }
    /**
     * @desc Get js code
     * @param int $id
     * @return string $js_code
     */
    public function get_js_code($id)
    {
        $this->load($id);
        return $this->obj->get_js_code();
    }
    /**
     * @desc Get css
     * @param int $id
     * @return string $css
     */
    public function get_css($id)
    {
        $this->load($id);
        return json_encode($this->obj->get_css());
    }
    /**
     * @desc Get css code
     * @param int $id
     * @return string $css_code
     */
    public function get_css_code($id)
    {
        $this->load($id);
        return $this->obj->get_css_code();
    }
    /**
     * @desc Get active
     * @param int $id
     * @return int $active
     */
    public function get_active($id)
    {
        $this->load($id);
        return $this->obj->get_active();
    }
    /**
     * @desc Get priority
     * @param int $id
     * @return float $priority
     */
    public function get_priority($id)
    {
        $this->load($id);
        return $this->obj->get_priority();
    }
    /**
     * @desc Get changeFreq refid
     * @param int $id
     * @return int $changeFreq_refid
     */
    public function get_changeFreq_refid($id)
    {
        $this->load($id);
        return $this->obj->get_changeFreq_refid();
    }
    /**
     * @desc Get exclude sitemap
     * @param int $id
     * @return int $exclude_sitemap
     */
    public function get_exclude_sitemap($id)
    {
        $this->load($id);
        return $this->obj->get_exclude_sitemap();
    }
    /**
     * @desc Get exclude search
     * @param int $id
     * @return int $exclude_search
     */
    public function get_exclude_search($id)
    {
        $this->load($id);
        return $this->obj->get_exclude_search();
    }
    /**
     * @desc Get content with_section
     * @param int $id
     * @return string $content_with_section
     */
    public function get_content_with_section($id)
    {
        $this->load($id);
        return $this->obj->get_content_with_section();
    }
    /**
     * @desc Get keyword
     * @param int $id
     * @return string $keyword
     */
    public function get_keyword($id)
    {
        $this->load($id);
        return $this->obj->get_keyword();
    }
    /**
     * @desc Get generated formated_content
     * @param int $id
     * @return string $generated_formated_content
     */
    public function get_generated_formated_content($id)
    {
        $this->load($id);
        return $this->obj->get_generated_formated_content();
    }
    /**
     * @desc Get meta cloud
     * @param int $id
     * @return string $meta_cloud
     */
    public function get_meta_cloud($id)
    {
        $this->load($id);
        return $this->obj->get_meta_cloud();
    }
    /**
     * @desc Get description sphinx
     * @param int $id
     * @return string $description_sphinx
     */
    public function get_description_sphinx($id)
    {
        $this->load($id);
        return $this->obj->get_description_sphinx();
    }
    /**
     * @desc Get page on_disk
     * @param int $id
     * @return int $page_on_disk
     */
    public function get_page_on_disk($id)
    {
        $this->load($id);
        return $this->obj->get_page_on_disk();
    }
    /**
     * @desc Get permit comment
     * @param int $id
     * @return int $permit_comment
     */
    public function get_permit_comment($id)
    {
        $this->load($id);
        return $this->obj->get_permit_comment();
    }
    /**
     * @desc Get author refid
     * @param int $id
     * @return int $author_refid
     */
    public function get_author_refid($id)
    {
        $this->load($id);
        return $this->obj->get_author_refid();
    }
    /**
     * @desc Get extra params
     * @param int $id
     * @return string $extra_params
     */
    public function get_extra_params($id)
    {
        $this->load($id);
        return $this->obj->get_extra_params();
    }
    /**
     * @desc Get is locked_for_edition
     * @param int $id
     * @return int $is_locked_for_edition
     */
    public function get_is_locked_for_edition($id)
    {
        $this->load($id);
        return $this->obj->get_is_locked_for_edition();
    }
    /**
     * @desc Get social media
     * @param int $id
     * @return string $social_media
     */
    public function get_social_media($id)
    {
        $this->load($id);
        return $this->obj->get_social_media();
    }
    /**
     * @desc Get plugins
     * @param int $id
     * @return string $plugins
     */
    public function get_plugins($id)
    {
        $this->load($id);
        return $this->obj->get_plugins();
    }
    /**
     * @desc Get use cache
     * @param int $id
     * @return int $use_cache
     */
    public function get_use_cache($id)
    {
        $this->load($id);
        return $this->obj->get_use_cache();
    }
    /**
     * @desc Get is a_draft_for
     * @param int $id
     * @return int $is_a_draft_for
     */
    public function get_is_a_draft_for($id)
    {
        $this->load($id);
        return $this->obj->get_is_a_draft_for();
    }
    /**
     * @desc Get last modif_by
     * @param int $id
     * @return int $last_modif_by
     */
    public function get_last_modif_by($id)
    {
        $this->load($id);
        return $this->obj->get_last_modif_by();
    }
    /**
     * @desc Get lang
     * @param int $id
     * @return string $lang
     */
    public function get_lang($id)
    {
        $this->load($id);
        return $this->obj->get_lang();
    }
    /**
     * @desc Get form refid
     * @param int $id
     * @return int $form_refid
     */
    public function get_form_refid($id)
    {
        $this->load($id);
        return $this->obj->get_form_refid();
    }

    /**
     * @desc Get property
     * @param int $id
     * @return string $property
     */
    public function get_property($id)
    {
        $this->load($id);
        return $this->obj->get_property($field);
    }
    /**
     * @desc Get section
     * @param int $id
     * @return string $section
     */
    public function get_section($id)
    {
        $this->load($id);
        return $this->obj->get_section();
    }
    /**
     * @desc Set content
     * @param int $id
     * @param string $content
     * @return bool $res
     */
    public function set_content($id,$content)
    {
        $this->load($id);
        return $this->obj->set_content($content);
    }
    /**
     * @desc Set title
     * @param int $id
     * @param string $title
     * @return bool $res
     */
    public function set_title($id,$title)
    {
        $this->load($id);
        return $this->obj->set_title($title);
    }
    /**
     * @desc Set url
     * @param int $id
     * @param string $url
     * @return bool $res
     */
    public function set_url($id,$url)
    {
        $this->load($id);
        return $this->obj->set_url($url);
    }
    /**
     * @desc Set in menu
     * @param int $id
     * @param int $in_menu
     * @return bool $res
     */
    public function set_in_menu($id,$in_menu)
    {
        $this->load($id);
        return $this->obj->set_in_menu($in_menu);
    }
    /**
     * @desc Set model refid
     * @param int $id
     * @param int $model_refid
     * @return bool $res
     */
    public function set_model_refid($id,$model_refid)
    {
        $this->load($id);
        return $this->obj->set_model_refid($model_refid);
    }
    /**
     * @desc Set meta
     * @param int $id
     * @param string $meta
     * @return bool $res
     */
    public function set_meta($id,$meta)
    {
        $this->load($id);
        return $this->obj->set_meta($meta);
    }
    /**
     * @desc Set js
     * @param int $id
     * @param string $js
     * @return bool $res
     */
    public function set_js($id,$js)
    {
        $this->load($id);
        return $this->obj->set_js($js);
    }
    /**
     * @desc Set js external
     * @param int $id
     * @param string $js
     * @return bool $res
     */
    public function set_js_external($id,$js)
    {
        $this->load($id);
        return $this->obj->set_js_external($js);
    }
    /**
     * @desc Set js code
     * @param int $id
     * @param string $js_code
     * @return bool $res
     */
    public function set_js_code($id,$js_code)
    {
        $this->load($id);
        return $this->obj->set_js_code($js_code);
    }
    /**
     * @desc Set css
     * @param int $id
     * @param string $css
     * @return bool $res
     */
    public function set_css($id,$css)
    {
        $this->load($id);
        return $this->obj->set_css($css);
    }
    /**
     * @desc Set css code
     * @param int $id
     * @param string $css_code
     * @return bool $res
     */
    public function set_css_code($id,$css_code)
    {
        $this->load($id);
        return $this->obj->set_css_code($css_code);
    }
    /**
     * @desc Set active
     * @param int $id
     * @param int $active
     * @return bool $res
     */
    public function set_active($id,$active)
    {
        $this->load($id);
        return $this->obj->set_active($active);
    }
    /**
     * @desc Set changeFreq refid
     * @param int $id
     * @param int $changeFreq_refid
     * @return bool $res
     */
    public function set_changeFreq_refid($id,$changeFreq_refid)
    {
        $this->load($id);
        return $this->obj->set_changeFreq_refid($changeFreq_refid);
    }
    /**
     * @desc Set priority
     * @param int $id
     * @param float $priority
     * @return bool $res
     */
    public function set_priority($id,$priority)
    {
        $this->load($id);
        return $this->obj->set_priority($priority);
    }
    /**
     * @desc Set exclude sitemap
     * @param int $id
     * @param int $exclude_sitemap
     * @return bool $res
     */
    public function set_exclude_sitemap($id,$exclude_sitemap)
    {
        $this->load($id);
        return $this->obj->set_exclude_sitemap($exclude_sitemap);
    }
    /**
     * @desc Set exclude search
     * @param int $id
     * @param int $exclude_search
     * @return bool $res
     */
    public function set_exclude_search($id,$exclude_search)
    {
        $this->load($id);
        return $this->obj->set_exclude_search($exclude_search);
    }
    /**
     * @desc Set keyword
     * @param int $id
     * @param string $keyword
     * @return bool $res
     */
    public function set_keyword($id,$keyword)
    {
        $this->load($id);
        return $this->obj->set_keyword($keyword);
    }
    /**
     * @desc Set generated formated_content
     * @param int $id
     * @param string $generated_formated_content
     * @return bool $res
     */
    public function set_generated_formated_content($id,$generated_formated_content)
    {
        $this->load($id);
        return $this->obj->set_generated_formated_content($generated_formated_content);
    }
    /**
     * @desc Set description sphinx
     * @param int $id
     * @param string $desc
     * @return bool $res
     */
    public function set_description_sphinx($id,$desc)
    {
        $this->load($id);
        return $this->obj->set_description_sphinx($desc);
    }
    /**
     * @desc Set meta cloud
     * @param int $id
     * @param string $meta_cloud
     * @return bool $res
     */
    public function set_meta_cloud($id,$meta_cloud)
    {
        $this->load($id);
        return $this->obj->set_meta_cloud($meta_cloud);
    }
    /**
     * @desc Set page on_disk
     * @param int $id
     * @param int $page_on_disk
     * @return bool $res
     */
    public function set_page_on_disk($id,$page_on_disk)
    {
        $this->load($id);
        return $this->obj->set_page_on_disk($page_on_disk);
    }
    /**
     * @desc Set permit comment
     * @param int $id
     * @param int $permit_comment
     * @return bool $res
     */
    public function set_permit_comment($id,$permit_comment)
    {
        $this->load($id);
        return $this->obj->set_permit_comment($permit_comment);
    }
    /**
     * @desc Set author refid
     * @param int $id
     * @param int $author_refid
     * @return bool $res
     */
    public function set_author_refid($id,$author_refid)
    {
        $this->load($id);
        return $this->obj->set_author_refid($author_refid);
    }
    /**
     * @desc Set extra params
     * @param int $id
     * @param string $extra_params_name
     * @param string $extra_params_val
     * @return bool $res
     */
    public function set_extra_params($id,$extra_params_name,$extra_params_val)
    {
        $this->load($id);
        return $this->obj->set_extra_params($extra_params_name,$extra_params_val);
    }
    /**
     * @desc Set extra params_field
     * @param int $id
     * @param string $extra_params
     * @return bool $res
     */
    public function set_extra_params_field($id,$extra_params)
    {
        $this->load($id);
        return $this->obj->set_extra_params_field($extra_params);
    }
    /**
     * @desc Set is locked_for_edition
     * @param int $id
     * @param int $is_locked_for_edition
     * @return bool $res
     */
    public function set_is_locked_for_edition($id,$is_locked_for_edition)
    {
        $this->load($id);
        return $this->obj->set_is_locked_for_edition($is_locked_for_edition);
    }
    /**
     * @desc Set social media
     * @param int $id
     * @param string $social_media
     * @return bool $res
     */
    public function set_social_media($id,$social_media)
    {
        $this->load($id);
        return $this->obj->set_social_media($social_media);
    }
    /**
     * @desc Set plugins
     * @param int $id
     * @param string $plugins
     * @return bool $res
     */
    public function set_plugins($id,$plugins)
    {
        $this->load($id);
        return $this->obj->set_plugins($plugins);
    }
    /**
     * @desc Set use cache
     * @param int $id
     * @param int $use_cache
     * @return bool $res
     */
    public function set_use_cache($id,$use_cache)
    {
        $this->load($id);
        return $this->obj->set_use_cache($use_cache);
    }

    /**
     * @desc Set is a_draft_for
     * @param int $id
     * @param int $is_a_draft_for
     * @return bool $res
     */
    public function set_is_a_draft_for($id,$is_a_draft_for)
    {
        $this->load($id);
        return $this->obj->set_is_a_draft_for($is_a_draft_for);
    }
    /**
     * @desc Set last modif_by
     * @param int $id
     * @param int $last_modif_by
     * @return bool $res
     */
    public function set_last_modif_by($id,$last_modif_by)
    {
        $this->load($id);
        return $this->obj->set_last_modif_by($last_modif_by);
    }
    /**
     * @desc Set lang
     * @param int $id
     * @param string $lang
     * @return bool $res
     */
    public function set_lang($id,$lang)
    {
        $this->load($id);
        return $this->obj->set_lang($lang);
    }
    /**
     * @desc Set form refid
     * @param int $id
     * @param int $form_refid
     * @return bool $res
     */
    public function set_form_refid($id,$form_refid)
    {
        $this->load($id);
        return $this->obj->set_form_refid($form_refid);
    }
    /**
     * @desc Set log modif_page
     * @param int $id
     * @param string $what_modified
     * @param string $old_value
     * @param string $new_value
     * @return bool $res
     */
    public function set_log_modif_page($id,$what_modified,$old_value,$new_value)
    {
        $this->load($id);
        return $this->obj->set_log_modif_page($what_modified,$old_value,$new_value);
    }
    /**
     * @desc Set parent refid
     * @param int $id
     * @param int $parent_refid
     * @return bool $res
     */
    public function set_parent_refid($id,$parent_refid)
    {
        $this->load($id);
        return $this->obj->set_parent_refid($parent_refid);
    }
}
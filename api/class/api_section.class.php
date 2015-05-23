<?php
load_alternative_class('class/common_soap_object.class.php');
class api_section extends common_soap_object
{
    protected $db;
    private $pId;
    private $pTitle;
    private $pContent;
    private $pPagerefid;
    private $pOrder;
    private $pActive;
    private $pAuthor_refid;
    private $pIs_locked_for_edition;
    private $pAssociated_img;
    private $pLast_modif_by;
    private $pDate_derniere_maj;
    private $pDate_creation;
    private $pLang;

    public $id;
    public $title;
    public $content;
    public $page_refid;
    public $order;
    public $active;
    public $author_refid;
    public $is_locked_for_edition;
    public $associated_img;
    public $last_modif_by;
    public $date_derniere_maj;
    public $date_creation;
    public $lang;


    protected $table = "section";

    /**
     * @desc List all section
     * @return array $datas[]
     */
    public function list_all()
    {
        $requete = "SELECT *
                      FROM " . $this->table;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id] = array(
                'id' => $res->id,
                'title' => $res->title,
                'page_refid' => $res->page_refid
            );
        }
        return $a;
    }
    /**
     * @desc List all section for a page id
     * @param int $page_refid
     * @return array $datas[]
     */
    public function list_all_by_page_refid($page_refid)
    {
        $requete = "SELECT *
                      FROM " . $this->table."
                     WHERE page_refid = ".$page_refid;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id] = array(
                    'id' => $res->id,
                    'title' => $res->title,
                    'page_refid' => $res->page_refid
            );
        }
        return $a;
    }

    /**
     * @desc Get all data about a section
     * @param int $id
     * @return array $datas[]
     */
    public function get_all($id)
    {
        $this->load($id);
        if ($this->obj->get_id() > 0)
        {
            $a = array();
            $a['id'] = $this->obj->get_id();
            $a['title'] = $this->obj->get_title();
            $a['content'] = $this->obj->get_content();
            $a['page_refid'] = $this->obj->get_page_refid();
            $a['order'] = $this->obj->get_order();
            $a['active'] = $this->obj->get_active();
            $a['author_refid'] = $this->obj->get_author_refid();
            $a['is_locked_for_edition'] = $this->obj->get_is_locked_for_edition();
            $a['associated_img'] = $this->obj->get_associated_img();
            $a['last_modif_by'] = $this->obj->get_last_modif_by();
            $a['date_derniere_maj'] = $this->obj->get_date_derniere_maj();
            $a['date_creation'] = $this->obj->get_date_creation();
            $a['lang'] = $this->obj->get_lang();
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @desc Link a section to a page
     * @param int $section_id
     * @param int $page_id
     * @return bool $res
     */
    public function link($section_id,$page_id)
    {
        return $this->obj->link($section_id,$page_id);
    }
    /**
     * @desc Reset all section order
     * @param int $page_id
     * @return bool $res
     */
    public function reset_pos($page_id)
    {
        return $this->obj->reset_pos($page_id);
    }
    /**
     * @desc Add a new section
     * @param int $page_id
     * @param string $lang
     * @return int $new_id
     */
    public function add($page_id,$lang=false)
    {
        return $this->obj->add($page_id,$lang);
    }
    /**
     * @desc Delete a section
     * @param int $id
     * @return bool $result
     */
    public function delete($id)
    {
        return $this->obj->delete();
    }
    /**
     * @desc Copy a section
     * @param int $newPage
     * @param string $lang
     * @return bool $result
     */
    public function section_clone($newPage=false,$lang=false)
    {
        return $this->obj->section_clone($newPage,$lang);
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
     * @desc Get order
     * @param int $id
     * @return int $order
     */
    public function get_order($id)
    {
        $this->load($id);
        return $this->obj->get_order();
    }
    /**
     * @desc Get page refid
     * @param int $id
     * @return int $page_refid
     */
    public function get_page_refid($id)
    {
        $this->load($id);
        return $this->obj->get_page_refid();
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
     * @desc Get associated img
     * @param int $id
     * @return string $associated_img
     */
    public function get_associated_img($id)
    {
        $this->load($id);
        return $this->obj->get_associated_img();
    }
    /**
     * @desc Get is locked_for_edition
     * @param int $id
     * @return string $is_locked_for_edition
     */
    public function get_is_locked_for_edition($id)
    {
        $this->load($id);
        return $this->obj->get_is_locked_for_edition();
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
    * @desc Set title
    * @param int $id
    * @param string $title
    * @param bool $reindex
    * @return bool $res
    */
    public function set_title($id,$title,$reindex=true)
    {
        $this->load($id);
        return $this->obj->set_title($title);
    }
    /**
     * @desc Set content
     * @param int $id
     * @param string $content
     * @param bool $reindex
     * @return bool $res
     */
    public function set_content($id,$content,$reindex=true)
    {
        $this->load($id);
        return $this->obj->set_content($content);
    }
    /**
     * @desc Force reindex
     * @param int $id
     * @return bool $res
     */
    public function force_reindex($id)
    {
        $this->load($id);
        return $this->obj->force_reindex();
    }


    /**
     * @desc Set order
     * @param int $id
     * @param int $order
     * @return bool $res
     */
    public function set_order($id,$order)
    {
        $this->load($id);
        return $this->obj->set_order($order);
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
     * @desc Set page refid
     * @param int $id
     * @param int $page_refid
     * @return bool $res
     */
    public function set_page_refid($id,$page_refid)
    {
        $this->load($id);
        return $this->obj->set_page_refid($page_refid);
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
     * @desc Set associated img
     * @param int $id
     * @param string $associated_img
     * @return bool $res
     */
    public function set_associated_img($id,$associated_img)
    {
        $this->load($id);
        return $this->obj->set_associated_img($associated_img);
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
}
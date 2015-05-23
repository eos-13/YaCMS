<?php
load_alternative_class('class/section.class.php');
class section_revision extends section
{
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


    private $pSection_refid;

    public $section_refid;

    protected $table = "section_revision";

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
            $this->pContent = $res->content;
            $this->pPagerefid = $res->page_revision_refid;
            $this->pOrder = $res->order;
            $this->pActive = $res->active;
            $this->pAuthor_refid = $res->author_refid;
            $this->pIs_locked_for_edition = $res->is_locked_for_edition;
            $this->pAssociated_img = $res->associated_img;
            $this->pLast_modif_by = $res->last_modif_by;
            $this->pDate_derniere_maj = $res->date_derniere_maj;
            $this->pDate_creation = $res->date_creation;
            $this->pSection_refid = $res->section_refid;
            $this->pLang = $res->lang;
            return $this;
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
            $this->content = $this->pContent;
            $this->page_refid = $this->pPagerefid;
            $this->order = $this->pOrder;
            $this->active = $this->pActive;
            $this->author_refid = $this->pAuthor_refid;
            $this->is_locked_for_edition = $this->pIs_locked_for_edition;
            $this->associated_img = $this->pAssociated_img;
            $this->last_modif_by = $this->pLast_modif_by;
            $this->date_derniere_maj = $this->pDate_derniere_maj;
            $this->date_creation = $this->pDate_creation;
            $this->section_refid = $this->pSection_refid;
            $this->lang = $this->pLang;
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
    public function get_title()
    {
        if ($this->get_id() > 0)
            return $this->pTitle;
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
    public function get_order()
    {
        if ($this->get_id() > 0)
            return $this->pOrder;
        else
            return false;
    }
    public function get_page_refid()
    {
        if ($this->get_id() > 0)
            return $this->pPagerefid;
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
            $u->fetch($this->pAuthor_refid);
            return $u;
        } else
            return false;
    }
    public function get_associated_img()
    {
        if ($this->get_id() > 0)
        {
            return $this->pAssociated_img;
            return $u;
        } else
            return false;
    }
    public function get_is_locked_for_edition()
    {
        if ($this->get_id() > 0)
        {
            return $this->pIs_locked_for_edition;
        } else
            return false;
    }
    public function get_last_modif_by()
    {
        if ($this->get_id() > 0)
        {
            return $this->pLast_modif_by;
        } else
            return false;
    }
    public function get_date_creation()
    {
        if ($this->get_id() > 0)
        {
            return $this->pDate_creation;
        } else
            return false;
    }
    public function get_date_derniere_maj()
    {
        if ($this->get_id() > 0)
        {
            return $this->pDate_derniere_maj;
        } else
            return false;
    }
    public function get_section_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->pSection_refid;
        } else
            return false;
    }
    public function get_lang()
    {
        if ($this->get_id() > 0)
        {
            return $this->pLang;
        } else
            return false;
    }
}
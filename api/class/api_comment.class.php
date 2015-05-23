<?php
load_alternative_class('class/common_soap_object.class.php');
class api_comment extends common_soap_object
{
    protected $db;
    private $pId;
    private $pTitle;
    private $pContent;
    private $pDate_creation;
    private $pPage_refid;
    private $pAuthor;
    private $pAuthor_refid;
    private $pValid;

    public $id;
    public $title;
    public $content;
    public $date_creation;
    public $page_refid;
    public $author;
    public $author_refid;
    public $valid;

    protected $table = "commentaire";

    /**
     * @return array $datas
     * @desc List all entry id
     */
    public function list_all()
    {
        $requete = "SELECT *
                      FROM " . $this->table;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]=$res->id;
        }
        return $a;
    }

    /**
     * @return array $datas
     * @desc List all entry id, filter by title
     */
    public function list_all_by_title($title)
    {
        $requete = "SELECT *
                      FROM " . $this->table."
                     WHERE title LIKE '".addslashes($title)."'";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]=$res->id;
        }
        return $a;
    }

    /**
     * @desc Get all datas of a comment
     * @param int $id
     * @return string $datas[]
     */
    public function get_all($id)
    {
        $this->load($id);
        if ($this->obj->get_id() > 0)
        {
            return array(
                    "id" => $this->obj->get_id(),
                    "title" => $this->obj->get_title(),
                    "content" => $this->obj->get_content(),
                    "date_creation" => $this->obj->get_date_creation(),
                    "page_refid" => $this->obj->get_page_refid(),
                    "author" => $this->obj->get_author(),
                    "author_refid" => $this->obj->get_author_refid(),
                    "valid" => $this->obj->get_valid()
            );
        } else {
            return false;
        }
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
     * @desc Get date of creation
     * @param int $id
     * @return datetime $date_creation
     */
    public function get_date_creation($id)
    {
        $this->load($id);
        return $this->obj->get_date_creation();
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
     * @desc Get the page id of the comment
     * @param int $id
     * @return int $page_refid
     */
    public function get_page_refid($id)
    {
        $this->load($id);
        return $this->obj->get_page_refid();
    }
    /**
     * @desc Get author name
     * @param int $id
     * @return string $author
     */
    public function get_author($id)
    {
        $this->load($id);
        return $this->obj->get_author();
    }
    /**
     * @desc Get author id(if registered)
     * @param int $id
     * @return int $user_id
     */
    public function get_author_refid($id)
    {
        $this->load($id);
        return $this->obj->get_author_refid();
    }
    /**
     * @desc Get author for display
     * @param int $id
     * @return string $author
     */
    public function get_author_html($id)
    {
        $this->load($id);
        return $this->obj->get_author_html();
    }
    /**
     * @desc Get valid status
     * @param int $id
     * @return int $valid
     */
    public function get_valid($id)
    {
        $this->load($id);
        return $this->obj->get_valid();
    }

    /**
     * @desc Set title of the comment
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
     * @desc Set author of the comment
     * @param int $id
     * @param string $author
     * @return bool $res
     */
    public function set_author($id,$author)
    {
        $this->load($id);
        return $this->obj->set_author($author);
    }
    /**
     * @desc Set author id of the comment
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
     * @desc Set page id of the comment
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
     * @desc Set comment content
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
     * @desc Set valid status of the comment
     * @param int $id
     * @param int $valid
     * @return bool $res
     */
    public function set_valid($id,$valid)
    {
        $this->load($id);
        return $this->obj->set_valid($valid);
    }
    /**
     * @desc Delete a comment
     * @param int $id
     * @return bool $res
     */
    public function del($id)
    {
        $this->load($id);
        return $this->obj->del($id);
    }
    /**
     * @desc Create an empty comment
     * @return int $id
     */
    public function create()
    {
        return $this->obj->create();
    }
}
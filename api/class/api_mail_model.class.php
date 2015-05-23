<?php
load_alternative_class('class/common_soap_object.class.php');
class api_mail_model extends common_soap_object
{
    private $pId;
    private $pTitle;
    private $pContent;
    private $pActive;

    public $id;
    public $title;
    public $content;
    public $active;

    protected $table = 'mail_model';

    /**
     * @desc List all mail model
     * @return array $datas[]
     */

    public function list_all()
    {
        $requete = "SELECT *
                      FROM ".$this->table;
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[$res->id] = array(
                'id' => $res->id,
                'title' => $res->title
            );
        }
        return $a;
    }

    /**
     * @param int $id
     * @desc Get all data about a mail model
     * @return array $datas[]
     */
    public function get_all($id)
    {
        $this->load($id);
        $a = array();
        if ($this->obj->get_id() > 0)
        {
            $a['id'] = $this->obj->get_id();
            $a['content'] = $this->obj->get_content();
            $a['active'] = $this->obj->get_active();
            $a['title'] = $this->obj->get_title();
        }
        return $a;
    }
    /**
     * @return int $id
     * @desc Create a new mail model
     */
    public function create()
    {
        return $this->obj->create();
    }
    /**
     * @param int $id
     * @desc Delete a mail model
     * @return bool $res
     */
    public function del($id)
    {
        $this->load($id);
        return $this->obj->del();
    }
    /**
     * @desc Find a free title for a mail model
     * @param string $title
     * @return string $new_title
     */
    public function free_title($title)
    {
        $new_title = $this->obj->free_title($title);
        return $new_title;
    }
    /**
     @desc        Prepare a mail to send it
     @param        int             $id
     @param        int             $to_id
     @param        int             $from_id
     @param        array           $data
     @return       bool            $res
     */
    public function prepare_mail($id,$to_id,$from_id,$data=false)
    {
        $this->load($id);
        return $this->obj->prepare_mail($to_id,$from_id,$data=false);
    }
    /**
     @desc         Prepare a mail to send (external mode)
     @param        int              $id
     @param        int              $from_id
     @param        array            $data
     @return       bool             $res
     */
    public function prepare_mail_external($id,$from_id,$data=false)
    {
        $this->load($id);
        return $this->obj->prepare_mail_external($from_id,$data=false);
    }
    /**
     @desc        Prepare a mail to send (external mode 2)
     @param        int             $id
     @param        int             $to
     @param        int             $from
     @param        data            $data
     @return       bool            True si OK; False si KO
     */
    public function prepare_external_2($id,$to,$from,$data=false)
    {
        $this->load($id);
        return $this->obj->prepare_external_2($to,$from,$data=false);
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
     * @desc Get active
     * @param int $id
     * @return bool $active
     */
    public function get_active($id)
    {
        $this->load($id);
        return $this->obj->get_active();
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
     * @desc Set active
     * @param int $id
     * @param bool $active
     * @return bool $res
     */
    public function set_active($id,$active)
    {
        $this->load($id);
        return $this->obj->set_active($active);
    }
}
<?php
load_alternative_class('class/common_soap_object.class.php');
class api_form extends common_soap_object
{
    protected $db;
    protected $table = "forms";

    private $pId;
    private $pContent;
    private $pPage_refid;
    private $pTitle;
    private $pJsonData;
    private $pLang;
    private $pIs_publish;
    private $pType_connector;
    private $pConnector_option;

    public $id;
    public $content;
    public $page_refid;
    public $title;
    public $jsonData;
    public $lang;
    public $is_publish;
    public $type_connector;
    public $connector_option;

    /**
     * @desc Get all forms
     * @return array $datas[]
     */
    public function list_all()
    {
        $requete = "SELECT *
                      FROM " . $this->table;
        $sql = $this->db->query($requete);
        $a = array();
        while ($res = $this->db->fetch_object($sql))
        {
            $a[$res->id]=array(
                    'id' => $res->id,
                    "title" => $res->title,
                    "lang" => $res->lang
            );
        }
        return $a;
    }

    /**
     * @desc Get all datas
     * @return array $datas[]
     * @param int $id
     * @param string $lang
     */
    public function get_all($id,$lang=false)
    {
        $this->load($id,$lang);
        if ($this->get_id()>0)
        {
            $a = array();
            $a['id'] = $this->obj->get_id();
            $a['content'] = $this->obj->get_content();
            $a['page_refid'] = $this->obj->get_page_refid();
            $a['title'] = $this->obj->get_title();
            $a['jsonData'] = $this->obj->get_jsonData();
            $a['lang'] = $this->obj->get_lang();
            $a['is_publish'] = $this->obj->get_is_publish();
            $a['type_connector'] = $this->obj->get_type_connector();
            $a['connector_option'] = $this->obj->get_connector_option();
            return $a;
        } else {
            return false;
        }
    }
    /**
     * @desc Delete a form
     * @return bool $res
     * @param int $id
     * @param string $lang
     */
    public function del($id,$lang)
    {
        $this->load($id,$lang);
        return $this->obj->del();
    }
    /**
     * @desc Create a form
     * @return bool $res
     * @param string $content
     * @param string $title
     * @param string $jsonData
     */
    public function add($content,$title,$jsonData=false)
    {
        return $this->obj->add($content,$title,$jsonData=false);
    }
    /**
     * @desc Copy a form
     * @return bool $res
     * @param int $id
     * @param string $lang
     */
    public function clone_form($id,$lang)
    {
        $this->load($id,$lang);
        return $this->obj->clone_form();
    }
    /**
     * @desc Stop publication of a form on it's page
     * @return bool $res
     * @param int $id
     */
    public function unpubli($id)
    {
        $this->load($id,$lang);
        return $this->obj->unpubli();
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
     * @desc Get jsonData
     * @param int $id
     * @return string $jsonData
     */
    public function get_jsonData($id)
    {
        $this->load($id);
        return $this->obj->get_jsonData();
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
     * @desc Get page id where the form is publish
     * @param int $id
     * @return int $page_refid
     */
    public function get_page_refid($id)
    {
        $this->load($id);
        return $this->obj->get_page_refid();
    }
    /**
     * @desc Get is publish
     * @param int $id
     * @return bool $is_publish
     */
    public function get_is_publish($id)
    {
        $this->load($id);
        return $this->obj->get_is_publish();
    }
    /**
     * @desc Get type of form connector
     * @param int $id
     * @return string $type_connector
     */
    public function get_type_connector($id)
    {
        $this->load($id);
        return $this->obj->get_type_connector();
    }
    /**
     * @desc Get form connector option
     * @param int $id
     * @return string $connector_option
     */
    public function get_connector_option($id)
    {
        $this->load($id);
        return $this->obj->get_connector_option();
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
     * @desc Set jsonData
     * @param int $id
     * @param string $jsonData
     * @return bool $res
     */
    public function set_jsonData($id,$jsonData)
    {
        $this->load($id);
        return $this->obj->set_jsonData($jsonData);
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
     * @desc Set is publish
     * @param int $id
     * @param bool $is_publish
     * @return bool $res
     */
    public function set_is_publish($id,$is_publish)
    {
        $this->load($id);
        return $this->obj->set_is_publish($is_publish);
    }
    /**
     * @desc Set connector option
     * @param int $id
     * @param string $connector_option
     * @return bool $res
     */
    public function set_connector_option($id,$connector_option)
    {
        $this->load($id);
        return $this->obj->set_connector_option($connector_option);
    }
    /**
     * @desc Set type connector
     * @param int $id
     * @param string $type_connector
     * @return bool $res
     */
    public function set_type_connector($id,$type_connector)
    {
        $this->load($id);
        return $this->obj->set_type_connector($type_connector);
    }

}
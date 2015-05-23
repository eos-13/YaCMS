<?php
class form extends common_object
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

    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->id = $id;
            $this->pId = $id;
            $this->pContent = $res->content;
            $this->pPage_refid = $res->page_refid;
            $this->pTitle = $res->title;
            $this->pJsonData = $res->jsonData;
            $this->pLang = $res->lang;
            $this->pIs_publish = $res->is_publish;
            $this->pType_connector = $res->type_connector;
            $this->pConnector_option = $res->connector_option;
            return $this;
        } else {
            return false;
        }
    }
    public function get_all()
    {
        if ($this->get_id()>0)
        {
            $this->id = $this->pId;
            $this->content = $this->pContent;
            $this->page_refid = $this->pPage_refid;
            $this->title = $this->pTitle;
            $this->jsonData = $this->pJsonData;
            $this->lang = $this->pLang;
            $this->is_publish = $this->pIs_publish;
            $this->type_connector = $this->pType_connector;
            $this->connector_option = $this->pConnector_option;
            return $this;
        } else {
            return false;
        }
    }
    public function del()
    {
        if ($this->get_id() > 0)
        {
            $requete = "DELETE FROM ".$this->table."
                              WHERE id = ".$this->get_id();
            $sql = $this->db->query($requete);
            return ($sql);
        } else {
            return false;
        }
    }
    public function add($content,$title,$jsonData=false)
    {
        if (!$jsonData)
            $jsonData = '[{
                    "title": "Form Name",
                    "fields": {
                        "name": {
                            "label": "Formulaire",
                            "type": "input",
                            "value": "Mon formulaire"
                        }
                    }
                }]';
        global $editlang;
        $requete = "INSERT INTO ".$this->table."
                                (content,title,jsonData,lang)
                         VALUES ('".addslashes($content)."','".addslashes($title)."','".addslashes($jsonData)."','".addslashes($editlang)."')";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $id = $this->db->last_insert_id($this->table);
            return ($id);
        } else {
            return false;
        }
    }
    public function clone_form()
    {
        if ($this->get_id()>0)
            return $this->add($this->get_content(),_("Clone")."_".$this->get_title(),$this->get_jsonData());
        else
            return false;
    }
    public function get_id()
    {
        if ($this->pId > 0)
        {
            return $this->pId;
        } else {
            return false;
        }
    }
    public function get_content()
    {
        if ($this->get_id() > 0)
        {
            return $this->pContent;
        } else {
            return false;
        }
    }
    public function get_title()
    {
        if ($this->get_id() > 0)
        {
            return $this->pTitle;
        } else {
            return false;
        }
    }
    public function get_jsonData()
    {
        if ($this->get_id() > 0)
        {
            return $this->pJsonData;
        } else {
            return false;
        }
    }
    public function get_lang()
    {
        if ($this->get_id() > 0)
        {
            return $this->pLang;
        } else {
            return false;
        }
    }
    public function get_page_refid()
    {
        if ($this->get_id() > 0)
        {
            return $this->pPage_refid;
        } else {
            return false;
        }
    }
    public function get_is_publish()
    {
        if ($this->get_id() > 0)
        {
            return $this->pIs_publish;
        } else {
            return false;
        }
    }
    public function get_type_connector()
    {
        if ($this->get_id() > 0)
        {
            return $this->pType_connector;
        } else {
            return false;
        }
    }
    public function get_connector_option()
    {
        if ($this->get_id() > 0)
        {
            return $this->pConnector_option;
        } else {
            return false;
        }
    }
    public function set_content($content)
    {
        if ($this->get_id() > 0)
        {
            $res = $this->update_field('content', $content);
            return $res;
        } else {
            return false;
        }
    }
    public function set_title($title)
    {
        if ($this->get_id() > 0)
        {
            $res = $this->update_field('title', $title);
            return $res;
        } else {
            return false;
        }
    }
    public function set_page_refid($page_refid)
    {
        if ($this->get_id() > 0)
        {
            $res = $this->update_field('page_refid', $page_refid);
            return $res;
        } else {
            return false;
        }
    }
    public function set_jsonData($jsonData)
    {
        if ($this->get_id() > 0)
        {
            $res = $this->update_field('jsonData', $jsonData);
            return $res;
        } else {
            return false;
        }
    }
    public function set_lang($lang)
    {
        if ($this->get_id() > 0)
        {
            $res = $this->update_field('lang', $lang);
            return $res;
        } else {
            return false;
        }
    }
    public function set_is_publish($is_publish)
    {
        if ($this->get_id() > 0)
        {
            $res = $this->update_field('is_publish', $is_publish);
            return $res;
        } else {
            return false;
        }
    }
    public function set_connector_option($connector_option)
    {
        if ($this->get_id() > 0)
        {
            $res = $this->update_field('connector_option', $connector_option);
            return $res;
        } else {
            return false;
        }
    }
    public function set_type_connector($type_connector)
    {
        if ($this->get_id() > 0)
        {
            $res = $this->update_field('type_connector', $type_connector);
            return $res;
        } else {
            return false;
        }
    }

    function get_restit_js()
    {
        $validate_locale = false;
        global $lang,$conf;
        $tmpLang = substr($lang,0,2);
        if ($tmpLang != "en")
        {
            if (is_file(make_path('js', "validation/localization/messages_".$lang, "js")))
            {
                $validate_locale = 'validation/localization/messages_'.$lang.'.js';
            } elseif (is_file(make_path('js', "validation/localization/messages_".$tmpLang, "js")))
            {
                $validate_locale = 'validation/localization/messages_'.$tmpLang.'.js';
            } else {
                $tmpLang = substr($conf->default_lang,0,2);
                if (is_file(make_path('js', "validation/localization/messages_".$conf->default_lang, "js")))
                {
                    $validate_locale = 'validation/localization/messages_'.$conf->default_lang.'.js';
                } else {
                    $validate_locale = 'validation/localization/messages_'.$tmpLang.'.js';
                }
            }
        }
        return array('forms_restit','validation/jquery.validate',$validate_locale);
    }
    function get_restit_css()
    {
        return array('forms_restit');
    }
    public function unpubli()
    {
        if ($this->get_id() > 0 && $this->get_page_refid() > 0)
        {
            load_alternative_class('class/page.class.php');
            $p = new page($this->db);
            $p->fetch($this->get_page_refid());
            $res1 = $p->set_form_refid("null");
            $res2 = $this->update_field('page_refid', "null");
            $res3 = $this->update_field('is_publish', "0");
            if ($res1 && $res2 && $res3)
            {
                return true;
            } else {
                return false;
            }
        }
    }
}
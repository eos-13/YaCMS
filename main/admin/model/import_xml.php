<?php
class model_import_xml extends admin_common
{
    public $id;
    public $message = false;
    public $data;

    public function run()
    {
        return (true);
    }
    public function import_file($post)
    {
        $target_dir = "/tmp/";
        $target_file = $target_dir . basename($_FILES["xml"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"]))
        {
            $xml = file_get_contents($_FILES["xml"]["tmp_name"]);
            $this->process_xml($xml);
            $this->message = _("Import data done, now verify");
        }
    }
    public function process_file($post)
    {
        $xml = html_entity_decode($post['xml_content']);
        $this->process_xml($xml);
        //Model
        $requete = "SELECT *
                      FROM model
                     WHERE name = '".addslashes($this->data['template']['name'])."' ";
        $sql = $this->db->query($requete);
        $template_id=false;
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $template_id = $res->id;
        } else {
            load_alternative_class('class/template.class.php');
            $t = new template($this->db);
            $template_id = $t->create();
            $t->fetch($template_id);
            if (isset($this->data['template']['name']))
                $t->set_name($this->data['template']['name']);
            if (isset($this->data['template']['content']))
                $t->set_content($this->data['template']['content']);
            if (isset($this->data['template']['path']))
                $t->set_path($this->data['template']['path']);
            if (isset($this->data['template']['plugins']))
                $t->set_plugins($this->data['template']['plugins']);
            if (isset($this->data['template']['extra_params']))
                $t->set_extra_params($this->data['template']['extra_params']);
        }
        //Page
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $pid = $p->add(($this->data['std']['lang']."x"!="x"?$this->data['std']:false));
        $p->fetch($pid);
        $p->set_model_refid($template_id);

        $p->set_active("0");
        $p->set_content($this->data['std']['content']);
        $p->set_title($this->data['std']['title']);
        $ptest = new page($this->db);
        if (!$ptest->fetch_by_url($this->data['std']['url']))
        {
            $p->set_url($this->data['std']['url']);
        } else {
            $newUrl = $p->find_new_url($this->data['std']['url'],$this->data['std']['url'],false,$lang=false,($this->data['std']['lang']."x"!="x"?$this->data['std']:false));
            $p->set_url($newUrl);
        }
        $p->set_extra_params_field($this->data['std']['extra_params']);
        $p->set_plugins($this->data['std']['plugins_data']);
        $u = new user($this->db);
        if ($u->fetch_by_username($this->data['std']['author_login']))
        {
            $p->set_author_refid($u->get_id());
        } else {
            global $current_user;
            $p->set_author_refid($current_user->get_id());
        }
        //Properties
        foreach($this->data['properties'] as $key => $val)
        {
            if ($key == "url") continue;
            if ($key == "model_refid") continue;
            $func = "set_".$key;
            if ($val."x" == "x" || $val == '""')
            {
                $p->$func("null");
            } else {
                if (is_array(json_decode($val)))
                {
                    $p->$func($val);
                } else {
                    $p->$func(json_decode($val));
                }

            }

        }

        //Sections
        load_alternative_class('class/section.class.php');
        foreach($this->data['sections'] as $key=>$val)
        {
            $s = new section($this->db);
            $sid = $s->add($pid,($this->data['std']['lang']."x"!="x"?$this->data['std']:false));
            foreach($val as $key1=>$val1)
            {
                $func = "set_".$key1;
                $s->$func($val1);
            }
        }
        if ($pid > 0)
        {
            $this->message = _("Import done");
            return $pid;
        } else {
            $this->message = _("Import failed");
        }

    }
    private function process_xml($xml)
    {
        //echo "<pre>".htmlentities($xml)."</pre>";
        //$xml =  simplexml_load_string($xml, null, LIBXML_NOCDATA);
        $dom = new DOMDocument();
        $a = $dom->loadXML($xml);
        $content = $dom->getElementsByTagName("content")->item(0)->textContent;
        $title = $dom->getElementsByTagName("title")->item(0)->textContent;
        $url = $dom->getElementsByTagName("url")->item(0)->textContent;
        $sections = $dom->getElementsByTagName("sections")->item(0);
        $s = array();
        foreach ($sections->childNodes as $section)
        {
            if ($section->nodeName == "section")
            {
                $tmp=array();
                foreach($section->childNodes as $val)
                {
                    if ($val->nodeName == "#text") continue;
                    $tmp[$val->nodeName]=$val->nodeValue;
                }

                $s[]=$tmp;
            }
        }
        $sections = $s;
        $properties = $dom->getElementsByTagName("properties")->item(0);
        $s = array();
        foreach ($properties->childNodes as $property)
        {
            if ($property->nodeName == "#text") continue;
            $nname = ($property->nodeName);
            $tmp=array();
            foreach($property->childNodes as $val)
            {
                if ($val->nodeName == "#text") continue;
                $tmp=$val->nodeValue;
            }

            $s[$nname]=$tmp;

        }
        $properties = $s;
        $extra_params = $dom->getElementsByTagName("extra_params")->item(0)->textContent;
        $plugins_data = $dom->getElementsByTagName("plugins_data")->item(0)->textContent;
        $author_email = $dom->getElementsByTagName("author_email")->item(0)->textContent;
        $author_login = $dom->getElementsByTagName("author_login")->item(0)->textContent;
        $lang = $dom->getElementsByTagName("lang")->item(0)->textContent;
        $template = $dom->getElementsByTagName("template")->item(0);
        $s = array();
        foreach ($template->childNodes as $property)
        {
            if ($property->nodeName == "#text") continue;
            $nname = ($property->nodeName);
            $tmp=array();
            foreach($property->childNodes as $val)
            {
                if ($val->nodeName == "#text") continue;
                $tmp=$val->nodeValue;
            }

            $s[$nname]=$tmp;

        }
        $template = $s;
        $this->data['std']=array(
                'content' => $content,
                'title' => $title,
                'url' => $url,
                'extra_params' => $extra_params,
                'plugins_data' => $plugins_data,
                'author_email' => $author_email,
                'author_login' => $author_login,
                'lang' => $lang
        );
        $this->data['sections'] = $sections;
        $this->data['properties'] = $properties;
        $this->data['template'] = $template;
        $this->data['xml'] = htmlentities($xml);
    }
}
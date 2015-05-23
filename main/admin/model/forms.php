<?php
class model_forms extends admin_common
{
    public $id;
    public $message = false;
    public $list;
    public $form;
    public $all_page;

    public function run()
    {
        $this->list = $this->list_all();
        if ($this->id > 0)
        {
            $this->form = $this->get_datas();
            $this->all_page = $this->make_form_all_page();
        }
        return (true);
    }
    private function make_form_all_page()
    {
        $html = "";
        $a = $this->list_all_page();
        foreach($a as $key=>$val)
        {
            $html .= "<optgroup label='".$key."'> ";
            foreach($val as $key1 => $val1)
            {
                $html .= "<option value='".$val1["id"]."'>".$val1["title"]." (/".$val1['url'].")"."</option>";
            }
            $html .= "</optgroup>";
        }
        return $html;
    }
    private function get_datas()
    {
        if ($this->id > 0)
        {
            load_alternative_class('class/form.class.php');
            $f = new form($this->db);
            $f->fetch($this->id);
            return (array(
                    'id' => $f->get_id(),
                    'jsonData' => $f->get_jsonData(),
                    'is_publish' => $f->get_is_publish(),
                    'page_refid' => $f->get_page_refid(),
            ));
        }
    }
    private function list_all()
    {
        global $editlang;
        $requete = "SELECT *
                      FROM forms
                     WHERE lang='".addslashes($editlang)."'";
        $sql = $this->db->query($requete);
        $a = array();
        $b = array();
        while($res = $this->db->fetch_object($sql))
        {
            $publish = false;
            $tmp=array(
                'title' => $res->title,
                'id' => $res->id,
            );
            if (isset($res->page_refid) && $res->page_refid > 0 )
            {
                load_alternative_class('class/page.class.php');
                $p = new page($this->db);
                $p->fetch($res->page_refid);
                $tmp["page_title"] = $p->get_title();
                $publish = true;
            }
            if ($publish)
                $a[] = $tmp;
            else
                $b[] = $tmp;
        }
        return array('publish' => $a, 'unpublish' => $b);
    }
    private function list_all_page()
    {
        global $editlang;
        $requete = "SELECT *
                      FROM page
                  ORDER BY lang,title";
        $sql = $this->db->query($requete);
        $a = array();
        global $conf;
        while($res = $this->db->fetch_object($sql))
        {
            $publish = false;
            $tmp=array(
                    'title' => $res->title,
                    'id' => $res->id,
                    'url' => $res->url,
                    'lang' => ($res->lang."x"=="x"?$conf->default_lang:$res->lang),
            );
            $a[($res->lang."x"=="x"?$conf->default_lang:$res->lang)][] = $tmp;
        }
        return $a;
    }
    public function save($post)
    {
        if (isset($post['id']) && $post['id'] > 0)
        {

            load_alternative_class('class/form.class.php');
            $f = new form($this->db);
            $f -> fetch($post['id']);
            $res = $f->set_content($this->decode($post['content']));
            $res1 = $f->set_title($this->decode($post['title']));
            $res2 = $f->set_jsonData(json_encode(json_decode($this->decode($post['jsonData']))));
            if ($res && $res1 && $res2) $this->message = _("Opération effectuée");
            else $this->message = _("Opération échouée");
            if ($res && $res1 && $res2)
                return array("message" => $this->message,"id" => $post['id']);
            else
                return array("message" => $this->message,"id" => $post['id']);
        }
        return array("message" => $this->message,"id" => false);
    }
    public function del($post)
    {
        if (isset($post['id']) && $post['id'] > 0)
        {
            load_alternative_class('class/form.class.php');
            $f = new form($this->db);
            $f -> fetch($post['id']);
            $res = $f->del();
            if ($res) $this->message = _("Opération effectuée");
            else $this->message = _("Opération échouée");
            if ($res)
                return array("message" => $this->message,"id" => true);
            else
                return array("message" => $this->message,"id" => $post['id']);
        }
        return array("message" => $this->message,"id" => false);
    }
    public function publish($post)
    {
        if (isset($post['id']) && $post['id'] > 0)
        {
            load_alternative_class('class/form.class.php');
            $f = new form($this->db);
            $f->fetch($post['id']);
            $res = $f->set_content($this->decode($post['content']));
            $res1 = $f->set_title($this->decode($post['title']));
            $res2 = $f->set_page_refid($post['page_refid']);
            $res3 = $f->set_jsonData(json_encode(json_decode($this->decode($post['jsonData']))));
            $res4 = false;
            if ($res2)
            {
                $res4 = $f->set_is_publish(1);
            }
            load_alternative_class('class/page.class.php');
            $p = new page($this->db);
            $p->fetch($post['page_refid']);
            $res5 = $p->set_form_refid($post['id']);
            if ($res && $res1 && $res2 && $res3 && $res4 && $res5)
                $this->message = _("Opération effectuée");
            else $this->message = _("Opération échouée");
            if ($res && $res1 && $res2 && $res3 && $res4 && $res5)
            {
                return array("message" => $this->message,"id" => $post['id']);
            } else {
                return array("message" => $this->message,"id" => false);
            }
        } else {
            $this->message = _("Opération échouée");
            return array("message" => $this->message,"id" => false);
        }
    }
    public function clone_form($post)
    {
        if (isset($post["id"]) && $post['id'] > 0)
        {
            load_alternative_class('class/form.class.php');
            $f = new form($this->db);
            $f->fetch($post['id']);
            $res = $f->clone_form();
            if ($res) $this->message = _("Opération effectuée");
            else $this->message = _("Opération échouée");
            return array("message" => $this->message,"id" => $res);
        } else {
            $this->message = _("Opération échouée");
            return array("message" => $this->message,"id" => $res);
        }
    }
    public function unpubli($post)
    {
        if (isset($post["id"]) && $post['id'] > 0)
        {
            load_alternative_class('class/form.class.php');
            $f = new form($this->db);
            $f->fetch($post['id']);
            $res = $f->unpubli();
            if ($res) $this->message = _("Opération effectuée");
            else $this->message = _("Opération échouée");
            return array("message" => $this->message,"id" => $res);
        } else {
            $this->message = _("Opération échouée");
            return array("message" => $this->message,"id" => $res);
        }
    }
    private function decode($in)
    {
        $in = urldecode($in);
        return $in;
    }
    public function add($post)
    {
        load_alternative_class('class/form.class.php');
        $f = new form($this->db);
        $res = $f->add("", _("Nouveau formulaire"));
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération échouée");
        return array("message" => $this->message,"id" => $res);
    }
}
<?php
class model_mail_model extends admin_common
{
    public $id;
    public $message = false;

    public function run()
    {
        $requete = "SELECT *
                      FROM mail_model
                  ORDER BY title";
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $list_mail_model[$res->id]=array('id'=>$res->id, 'title'=>$res->title);
        }
        return $list_mail_model;
    }
    public function get_content($post)
    {
        load_alternative_class('class/mail_model.class.php');
        $mm = new mail_model($this->db);
        $mm->fetch($post['id']);
        $html = "<table style='border-collapse: collapse;' CELLPADDING=10 border='0' width='100%'>";
        $html .= "<tr class='background-td-odd'><th>Title</th><td>";
        $html .= "<input type='text' style='width:100%' required name='title' id='title' value='".$mm->get_title()."' />";
        $html .= "</td></tr>";
        $html .= "<tr class='background-td-even'><th>Content</th><td>";
        $html .= "<textarea id='content' name='content'>".$mm->get_content()."</textarea>";
        $html .= "</td></tr>";
        $html .= "</table>";
        return $html;

    }
    public function add()
    {
        load_alternative_class('class/mail_model.class.php');
        $c = new mail_model($this->db);
        $id = $c->create();
        $c->fetch($id);
        $newTitle = $c->free_title("New");
        $res = $c->set_title($newTitle);
        $res1 = $c->set_active(1);
        if ($id>0 && $res1 && $res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return array('id'=>$id);
    }
    public function clone_mail_model($post)
    {
        load_alternative_class('class/mail_model.class.php');
        $mm = new mail_model($this->db);
        $mm->fetch($this->id);
        $c = new mail_model($this->db);
        $cloneid = $mm->create();
        $c->fetch($cloneid);
        $res = $c->set_content($mm->get_content());
        $newTitle = $mm->free_title("Clone ".$mm->get_title());
        $res1 = $c->set_title($newTitle);
        $res2 = $c->set_active(1);
        if ($cloneid>0 && $res && $res1 && $res2) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return array('id'=>$cloneid);
    }
    public function del()
    {
        load_alternative_class('class/mail_model.class.php');
        $mm = new mail_model($this->db);
        $mm->fetch($this->id);
        $res = $mm->del();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return true;
    }
    public function validate($post)
    {
        $requete = "SELECT *
                      FROM `mail_model`
                     WHERE `title` = '".addslashes($post['title'])."'
                      AND `id` <> ".$post['id'];
        $sql = $this->db->query($requete);
        if (!$sql) {
            return ('DB Error') ;
        }
        if ($this->db->num_rows($sql) > 0 ){
            return ('Ce nom existe déjà');
        } else {
            return (true);
        }
    }
    public function update($post)
    {
        load_alternative_class('class/mail_model.class.php');
        $mm = new mail_model($this->db);
        $mm->fetch($this->id);
        $res = $mm->set_content($post['content']);
        $res1 = $mm->set_title($post['title']);
        $res2 = $mm->set_active(1);
        if ($res && $res1 && $res2) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return array('id'=>$this->id);
    }
}
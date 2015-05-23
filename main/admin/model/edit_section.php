<?php
class model_edit_section extends admin_common
{
    public $id;
    public $can_unlock = false;
    public $can_edit = false;
    public $message = false;

    public function run()
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $r = $s->fetch($this->id);
        $this->can_unlock = $this->can_unlock($s->get_is_locked_for_edition());
        $this->can_edit = $this->can_edit($s);

        return $r;
    }
    public function update($post)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->fetch($this->id);
        $s->id = $this->id;
        $error = false;
        $res = $s->set_content($post['content']);
        if (!$res) $error = true;
        $res = $s->set_title($post['title']);
        if (!$res) $error = true;
        if ($post['section_image_val'])
        {
            $res = $s->set_associated_img($post['section_image_val']);
            if (!$res) $error = true;
        }
        if (!$error) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function activate()
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->fetch($this->id);
        $s->id = $this->id;
        $res = $s->set_active(1);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function deactivate()
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->fetch($this->id);
        $s->id = $this->id;
        $res = $s->set_active("0");
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function delete()
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $res = $s->delete($this->id);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function section_clone()
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->fetch($this->id);
        $s->id = $this->id;
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $s->section_clone();
    }
    public function clone_to_page($new_page)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->fetch($this->id);
        $s->id = $this->id;
        $res = $s->section_clone($new_page);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }

    public function change_page($new_page)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->fetch($this->id);
        $s->id = $this->id;
        $res =  $s->set_page_refid($new_page);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }
    public function all_page($exclude_id)
    {
        $requete = "SELECT id,title
                      FROM page
                     WHERE id <> ".$exclude_id."
                  ORDER BY title";
        $sql = $this->db->query($requete);
        $array = false;
        while($res=$this->db->fetch_object($sql)){
            $array[]=array("id"=>$res->id,"title"=>$res->title);
        }
        return $array;
    }
    public function info_page($id)
    {
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($id);
        return array( "id"=>$id, "title" => $p->get_title() );
    }
    public function lock_page($post)
    {
        load_alternative_class('class/section.class.php');
        global $current_user;
        $s = new section($this->db);
        $s->fetch($post['id']);
        $res = $s->set_is_locked_for_edition($current_user->get_id());
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }
    public function unlock_page($post)
    {
        load_alternative_class('class/section.class.php');
        $s = new section($this->db);
        $s->fetch($post['id']);
        $res = $s->set_is_locked_for_edition("0");
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }
    public function can_unlock($who_lock)
    {
        global $current_user;
        if ($current_user->get_id() > 0)
        {
            if ($who_lock == $current_user->get_id())
            {
                return true;
            }
        }
        if ($current_user->has_right('admin'))
        {
            return true;
        }
        if ($current_user->has_right('publication'))
        {
            return true;
        }
        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_user
                     WHERE user_refid = ".$current_user->get_id(). "
                       AND group_publication.id = group_publication_user.group_publication_refid";
        $sql = $this->db->query($requete);
        $a = array();
        while($res=$this->db->fetch_object($sql))
        {
            if ($res->is_admin == 1) return true;
            $a[]=$res->id;
        }
        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_user
                     WHERE user_refid = ".$who_lock. "
                       AND group_publication.id = group_publication_user.group_publication_refid
                       AND group_publication.id IN (".join(',',$a).") ";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0) return true;
        return false;
    }
    public function can_edit($s)
    {
        global $current_user;
        if ($current_user->has_right('admin'))
        {
            return true;
        }
        if ($current_user->get_id() > 0)
        {
            if ($s->get_author_refid() == $current_user->get_id())
            {
                return true;
            }
        }
        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($s->get_page_refid());
        if ($p->has_a_draft())
        {
            return false;
        }
        if ($current_user->has_right('publication'))
        {
            return true;
        }
        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_user
                     WHERE user_refid = ".$current_user->get_id(). "
                       AND group_publication.id = group_publication_user.group_publication_refid";
        $sql = $this->db->query($requete);
        $a = array();
        while($res=$this->db->fetch_object($sql))
        {
            if ($res->is_admin == 1) return true;
            $a[]=$res->id;
        }
        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_user
                     WHERE user_refid = ".$s->get_author_refid. "
                       AND group_publication.id = group_publication_user.group_publication_refid
                       AND group_publication.id IN (".join(',',$a).") ";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0) return true;
        return false;
    }

}
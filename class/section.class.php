<?php
class section extends common_object
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

    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM section
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $id;
            $this->id = $id;
            $this->pTitle = $res->title;
            $this->pContent = $res->content;
            $this->pPagerefid = $res->page_refid;
            $this->pOrder = $res->order;
            $this->pActive = $res->active;
            $this->pAuthor_refid = $res->author_refid;
            $this->pIs_locked_for_edition = $res->is_locked_for_edition;
            $this->pAssociated_img = $res->associated_img;
            $this->pLast_modif_by = $res->last_modif_by;
            $this->pDate_derniere_maj = $res->date_derniere_maj;
            $this->pDate_creation = $res->date_creation;
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
    public function get_lang()
    {
        if ($this->get_id() > 0)
        {
            return $this->pLang;
        } else
            return false;
    }
    public function link($section_id,$page_id)
    {
        $this->id = $section_id;
        $this->set_log_modif_page('Section:page_refid',$this->get_page_refid(),$page_id);
        $ret =  $this->update_field('page_refid', $page_id);
        $this->refresh_sitemap();
        return $ret;
    }
    public function set_title($title,$reindex=true)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            $this->set_log_modif_page('Section:title',$this->get_title(),$title);
            $ret = $this->update_field('title',$title);
            if ($reindex)
            {
                $this->refresh_sitemap();
                $this->update_sphinx();
            }
            return $ret;
        } else {
            return false;
        }
    }
    public function set_content($content,$reindex=true)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            $this->set_log_modif_page('Section:content',$this->get_content(),$content);
            $ret = $this->update_field('content',$content);
            if ($reindex)
            {
                $this->refresh_sitemap();
                $this->update_sphinx();
            }
            return $ret;
        } else {
            return false;
        }
    }
    public function force_reindex()
    {
        if ($this->get_id()>0)
        {
            $this->refresh_sitemap();
            $this->update_sphinx();
        }
        return true;
    }
    public function set_order($order)
    {
        if ($this->get_id() > 0)
        {
            if (!$order || empty($order) || $order."x" == "x") $order = "0";
            $this->id = $this->get_id();
            $ret = $this->update_field('order',$order);
            $this->set_log_modif_page('Section:order',$this->get_order(),$order);
            $this->refresh_sitemap();
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    public function set_active($active)
    {
        if ($this->get_id() > 0){
            if (!$active || empty($active) || is_null($active))
            {
                $active = "0";
            }
            $this->id = $this->get_id();
            $this->set_log_modif_page('Section:active',$this->get_active(),$active);
            $ret = $this->update_field('active',$active);
            $this->refresh_sitemap();
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    public function set_page_refid($page_refid)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            $max = $this->get_next_order($page_refid);
            $this->set_order($max);
            $this->set_log_modif_page('Section:page_refid',$this->get_page_refid(),$page_refid);
            $ret =  $this->update_field('page_refid',$page_refid);
            $this->refresh_sitemap();
            $this->pPagerefid=$page_refid;
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    public function set_author_refid($author_refid)
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->get_id();
            $this->set_log_modif_page('Section:author_refid',$this->get_author_refid(),$author_refid);
            $ret = $this->update_field('author_refid',$author_refid);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_is_locked_for_edition($is_locked_for_edition)
    {
        if ($this->get_id() > 0)
        {
            if (!$is_locked_for_edition || empty($is_locked_for_edition) || is_null($is_locked_for_edition))
            {
                $is_locked_for_edition = "0";
            }
            $this->set_log_modif_page('Section:is_locked_for_edition',$this->get_is_locked_for_edition(),$is_locked_for_edition);
            $ret = $this->update_field('is_locked_for_edition',$is_locked_for_edition);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_associated_img($associated_img)
    {
        if ($this->get_id() > 0)
        {
            $this->set_log_modif_page('Section:associated_img',$this->get_associated_img(),$associated_img);
            $ret = $this->update_field('associated_img',$associated_img);
            return $ret;
        } else {
            return false;
        }
    }
    public function set_last_modif_by($last_modif_by)
    {
        if ($this->get_id() > 0)
        {
            $ret = $this->update_field('last_modif_by',$last_modif_by);
            return $ret;
        } else {
            return false;
        }
    }
    public function reset_pos($page_id)
    {
        $requete = "UPDATE ".$this->table."
                       SET `order` = NULL
                     WHERE `page_refid` = ".$page_id;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        return ($sql);
    }
    private function get_next_order($page_id)
    {
        $requete = "SELECT ifnull(max(`order`)+1,0) as m
                      FROM ".$this->table. "
                     WHERE `page_refid` = ".$page_id;
        $sql = $this->db->query($requete);
        $res = $this->db->fetch_object($sql);
        $max = $res->m;
        return ($max);
    }
    public function set_lang($lang)
    {
        if ($this->get_id() > 0){
            $this->id = $this->get_id();
            $this->set_log_modif_page('Section:lang',$this->get_lang(),$lang);
            $ret = $this->update_field('lang',$lang);
            $this->refresh_sitemap();
            $this->update_sphinx();
            return $ret;
        } else {
            return false;
        }
    }
    public function add($page_id,$lang=false)
    {
        global $current_user;
        $section_lang = ($lang?"'".addslashes($lang)."'":'null');
        $max = $this->get_next_order($page_id);
        $requete = "INSERT INTO ".$this->table. " (`page_refid`,`order`,`date_creation`,`author_refid`,`lang`)
                         VALUES (".$page_id.",".$max.",now(),".$current_user->get_id().",".$section_lang." ) ";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $newId = $this->db->last_insert_id($this->table);
        $this->set_log_modif_page('Section:addSection',"",$newId);
        $this->fetch($newId);
        $this->refresh_sitemap();
        $this->update_sphinx();
        return $newId;
    }
    public function delete($id)
    {
        $this->fetch($id);
        $this->pPagerefid = $this->get_page_refid();
        $requete = "DELETE FROM ".$this->table. "
                          WHERE `id` =  ".$id;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->set_log_modif_page('Section:delSection',$this->get_id(),"");
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $this->refresh_sitemap();
        $this->update_sphinx($this->pPagerefid);
        return $sql;
    }
    public function section_clone($newPage=false,$lang=false)
    {
        global $current_user;
        $max = $this->get_next_order(($newPage?$newPage:$this->get_page_refid()));
        $lang_section = "null";
        if ($lang)
        {
            $lang_section = "'".addslashes($lang)."'";
        } elseif ($this->get_lang() && $this->get_lang()."x" != "x") {
            $lang_section = "'".addslashes($this->get_lang())."'";
        }
        $requete = "INSERT INTO ".$this->table. "
                                (`page_refid`,`content`,`title`,`order`,`active`,`date_creation`, `author_refid`,`lang`)
                          VALUES (".($newPage?$newPage:$this->pPagerefid).",'".addslashes($this->pContent)."','".addslashes("Clone ".$this->pTitle)."',".$max.",".$this->pActive.",now(),".$current_user->get_id().",".$lang_section.")";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $newid = $this->db->last_insert_id($this->table);
        $this->refresh_sitemap();

        if ($max == $this->get_page_refid()){
            if ($newid) $this->set_log_modif_page('Section:cloneSection',"Section: ".$this->get_id(),"Nouvelle section:".$newid);
        } else {
            if ($newid) $this->set_log_modif_page('Section:clopySection',"Vers page:".$max,$this->get_id(),"Section: ".$this->get_id()." Nouvelle section".$newid);
        }
        $this->pPagerefid=($newPage?$newPage:$this->get_page_refid());
        $this->update_sphinx();
        return $newid;
    }
    private function update_sphinx($force_page_id = false)
    {

        if ($force_page_id) $page_id = $force_page_id;
        else $page_id = $this->get_page_refid();

        load_alternative_class('class/page.class.php');
        $p = new page($this->db);
        $p->fetch($page_id);
        $p->update_sphinx();
    }
    private function set_log_modif_page($what_modified,$old_value,$new_value)
    {
        global $current_user;
        $what_modified = "Section: ".$this->get_id()." ".$what_modified;
        $this->set_last_modif_by($current_user->get_id());
        $requete = "INSERT INTO log_modif_page
                                (page_refid, user_refid, type_modif, old_value, new_value)
                         VALUES (".$this->get_page_refid().",".$current_user->get_id().",'".addslashes($what_modified)."', '".addslashes($old_value)."','".addslashes($new_value)."')";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        return $sql;
    }

}
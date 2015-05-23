<?php
class model_revision extends admin_common
{
    public $page_refid;
    public $message = false;

    public function run()
    {
        $a['all_revisions'] = $this->all_revisions();
        return $a;
    }
    private function all_revisions()
    {
        $requete = "SELECT page_revision.id,
                           page_revision.date_derniere_maj,
                           user.name,
                           user.firstname
                      FROM page_revision,
                           user
                     WHERE page_refid = ".$this->page_refid."
                       AND user.id = page_revision.last_modif_by
                  ORDER BY id DESC";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[] = array('id'=>$res->id,
                         'date_derniere_maj'=>convert_time_us_to_fr($res->date_derniere_maj),
                         'firstname'=>$res->firstname,
                         'name'=>$res->name,
                    );
        }
        return $a;
    }
    public function delete($post)
    {
        load_alternative_class('class/revision.class.php');
        $rev = new revision($this->db);
        $rev->fetch($post['id']);
        $res = $rev->del();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");

        return $res;

    }
}
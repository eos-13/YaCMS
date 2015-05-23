<?php
class model_histo_preview extends admin_common
{
    public $page_refid;
    public $message = false;

    public function run()
    {
        $a['all_previews'] = $this->all_previews();
        return $a;
    }
    private function all_previews()
    {
        $requete = "SELECT html_save.id,
                           date_save,
                           user.name,
                           user.firstname
                      FROM html_save,
                           user
                     WHERE page_refid = ".$this->page_refid."
                       AND user.id = html_save.save_by
                  ORDER BY date_save DESC";
        $sql = $this->db->query($requete);
        $a = array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[] = array('id'=>$res->id,
                         'date_save'=>convert_time_us_to_fr($res->date_save),
                         'firstname'=>$res->firstname,
                         'name'=>$res->name,
                    );
        }
        return $a;
    }
    public function delete($post)
    {
        $requete = "DELETE FROM html_save
                          WHERE id = ".$post['id'];
        $sql = $this->db->query($requete);
        if ($sql) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return  $sql;

    }
    public function view($post)
    {
        $requete = "SELECT *
                      FROM html_save
                     WHERE id=".$post['id'];
        $sql = $this->db->query($requete);
        if($sql)
        {
            $res = $this->db->fetch_object($sql);
            return $res->html;
        } else {
            return "Une erreur c'est produite";
        }
    }

}
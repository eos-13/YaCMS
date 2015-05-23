<?php
load_alternative_class('class/headerfooter.class.php');
class col_droite extends headerfooter
{
    public $log;
    public function __construct($db)
    {
        $this->db = $db;
        $this->set_id(6);
        global $log;
        $this->log = $log;
    }
    /**
     *
     * @param string $lang
     * @return int $id
     * @desc change editlang of display. Return the id of the localized column
     */
    public function change_lang($lang)
    {
        global $conf;
        if ($conf->default_lang == $lang)
        {
            $this->set_id(6);
            return 6;
        }

        $requete = "SELECT id
                      FROM ".$this->table ."
                     WHERE lang= '".addslashes($lang)."'
                       AND name = 'col_droite'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->set_id($res->id);
            return $res->id;
        } elseif ($sql) {
            //on crée un col_droite

            if (in_array($lang, preg_split("/,/", $conf->available_lang)))
            {
                $requete = "INSERT INTO ".$this->table . "
                                         (name,lang)
                                  VALUES ('col_droite','".$lang."')";
                $sql = $this->db->query($requete);
                $newId = $this->db->last_insert_id($this->table);
                $this->set_id($newId);
                return $newId;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * @param string $lang
     * @return int $id
     * @desc Change the display lang
     */
    public function display_lang($lang=false)
    {
        global $conf;
        if ($conf->default_lang == $lang || ! $lang || $lang."x" == "x")
        {
            $this->set_id(6);
            return 6;
        }

        $requete = "SELECT id
                      FROM ".$this->table ."
                     WHERE lang= '".addslashes($lang)."'
                       AND name = 'col_droite'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->set_id($res->id);
            return $res->id;
        }
    }
}
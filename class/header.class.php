<?php
load_alternative_class('class/headerfooter.class.php');
class header extends headerfooter
{
    public $log;
    public function __construct($db)
    {
        $this->db = $db;
        $this->set_id(1);
        global $log;
        $this->log = $log;
    }
    public function change_lang($lang)
    {
        global $conf;
        if ($conf->default_lang == $lang)
        {
            $this->set_id(1);
            return 1;
        }

        $requete = "SELECT id
                      FROM ".$this->table ."
                     WHERE lang= '".addslashes($lang)."'
                       AND name = 'header'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->set_id($res->id);
            return $res->id;
        } elseif ($sql) {
            //on crée un header

            if (in_array($lang, preg_split("/,/", $conf->available_lang)))
            {
                $requete = "INSERT INTO ".$this->table . "
                                         (name,lang)
                                  VALUES ('header','".$lang."') ";
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
    public function display_lang($lang=false)
    {
        global $conf;
        if ($conf->default_lang == $lang || ! $lang || $lang."x" == "x")
        {
            $this->set_id(1);
            return 1;
        }

        $requete = "SELECT id
                      FROM ".$this->table ."
                     WHERE lang= '".addslashes($lang)."'
                       AND name = 'header'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->set_id($res->id);
            return $res->id;
        }
    }
}
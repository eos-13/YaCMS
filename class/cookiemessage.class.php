<?php
load_alternative_class('class/headerfooter.class.php');
class cookiemessage extends headerfooter
{
    public $log;
    public function __construct($db,$id=false)
    {
        $this->db = $db;
        $this->set_id(3);
        global $log;
        $this->log = $log;
    }
    /**
     * @desc Change lang
     * @param string $lang
     * @return int $res
     */
    public function change_lang($lang)
    {
        global $conf;
        if ($conf->default_lang == $lang)
        {
            $this->set_id(3);
            return 3;
        }

        $requete = "SELECT id
                      FROM ".$this->table ."
                     WHERE lang= '".addslashes($lang)."'
                       AND name = 'cookie_message'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->set_id($res->id);
            return $res->id;
        } elseif ($sql) {
            //on crée un cookie_message

            if (in_array($lang, preg_split("/,/", $conf->available_lang)))
            {
                $requete = "INSERT INTO ".$this->table . "
                                         (name,lang)
                                  VALUES ('cookie_message','".$lang."')";
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
     * @desc display the cookie massage in correct lang
     * @param string $lang
     * @return int $res
     */
    public function display_lang($lang=false)
    {
        global $conf;
        if ($conf->default_lang == $lang || ! $lang || $lang."x" == "x")
        {
            $this->set_id(3);
            return 3;
        }

        $requete = "SELECT id
                      FROM ".$this->table ."
                     WHERE lang= '".addslashes($lang)."'
                       AND name = 'cookie_message'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            $res = $this->db->fetch_object($sql);
            $this->set_id($res->id);
            return $res->id;
        }
    }
}
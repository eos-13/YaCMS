<?php
class sendmail
{
    public $db;
    public $log;
    public function __construct($db){
        $this->db = $db;
        global $log;
        $this->log = $log;
    }
    public function run_trigger($action,$object,$user,$data)
    {
        global $conf;
        switch($action)
        {
            case "SEND_MAIL_TMPTOKEN":
            {
                //1 Trouve le modèle d'email
                $requete = "SELECT mail_model.id
                              FROM events,
                                   mail_model
                             WHERE events.id = mail_model.events_refid
                               AND events.active = 1
                               AND mail_model.active = 1
                               AND events.name = '".addslashes($action)."'";
                $sql = $this->db->query($requete);
                $res = $this->db->fetch_object($sql);
                //2 prepare le mail
                require_once($conf->main_document_root."/class/mail_model.class.php");
                require_once($conf->main_document_root."/class/mail.class.php");
                $mm = new mail_model($this->db);
                $mm->fetch($res->id);
                $a = $mm->prepare_mail($object->get_id(), $conf->user_id_from_email ,$object->get_all());
                //3 envoie
                $from = new user($this->db);
                $from->fetch($conf->user_id_from_email);
                $m = new mail();
                $m->send_email($a['subject'], $a['content'], $object->get_email(), $from->get_email());
            }
            break;
            case "SEND_MAIL_ACTIVATION":
            {
                //1 Trouve le modèle d'email
                $requete = "SELECT mail_model.id
                              FROM events,
                                   mail_model
                             WHERE events.id = mail_model.events_refid
                               AND events.active = 1
                               AND mail_model.active = 1
                               AND events.name = '".addslashes($action)."'";
                $sql = $this->db->query($requete);
                $res = $this->db->fetch_object($sql);
                //2 prepare le mail
                require_once($conf->main_document_root."/class/mail_model.class.php");
                require_once($conf->main_document_root."/class/mail.class.php");
                $mm = new mail_model($this->db);
                $mm->fetch($res->id);
                $object->token_activation = md5($object->get_md5());
                $a = $mm->prepare_mail($object->get_id(), $conf->user_id_from_email ,$object->get_all());
                //3 envoie
                $from = new user($this->db);
                $from->fetch($conf->user_id_from_email);
                $m = new mail();
                $m->send_email($a['subject'], $a['content'], $object->get_email(), $from->get_email());
            }
            break;
        }
        return true;
    }

}
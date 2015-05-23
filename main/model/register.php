<?php
class model_register extends common
{
    public $message;
    public function run()
    {
    }
    public function validate($post)
    {
        foreach($post as $key=>$val)
        {
            if ($key=="action") continue;
            $requete = "SELECT *
                          FROM user
                         WHERE `".$key."` = '".addslashes($val)."' ";
            $sql = $this->db->query($requete);
            if (!$sql)
            {
                return ('DB Error') ;
            } elseif ($this->db->num_rows($sql) > 0 ) {
                return ('Ce '.$key.' existe déjà');
            } else {
                return (true);
            }
        }
    }
    public function add($post)
    {
        //On vérifie le mot de passe
        global $conf;
        if ($post['password'] != $post['password_again'])
        {
            $this->message = "Mots de passes ne correspondent pas";
            return false;
        }
        if ($conf->reCaptcha_key."x" != "x")
        {
            $remote_ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:false);
            if (!$remote_ip) { $remote_ip = $_SERVER['REMOTE_ADDR'];}
            $ret = $this->verify_captcha_response($post['g-recaptcha-response'],$remote_ip);
            if (!$ret)
            {
                $this->message="Le captcha n'a pas été saisi correctement";
                return false;
            }
        }
        $u = new user($this->db);
        $id = $u->create();
        $u->fetch($id);
        $this->db->begin();
        $sql = $u->set_active("0");
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        $u->set_name($post['name']);
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        $u->set_firstname($post['firstname']);
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        $u->set_login($post['login']);
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        $u->set_email($post['email']);
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        $u->set_pass(md5($post['password']));
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        $u->set_public_profile($public_profile);
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        //$u->set_avatar_path($avatar_path)
        $u->set_description($post['description']);
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }

        $this->db->commit();
        $this->message = "Le compte a été crée, merci de l'activer";

        global $trigger;
        $trigger->run_trigger("SEND_MAIL_ACTIVATION", $u,$u);

        return true;
    }

}
?>
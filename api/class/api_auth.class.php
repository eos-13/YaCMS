<?php
load_alternative_class('class/common_soap_object.class.php');
class api_auth extends common_soap_object
{
    /**
     * @desc Authentificate a user
     * @param string $key
     * @param string $user
     * @param string $pass
     * @return string $token
     */
    public function auth($key,$user,$pass)
    {
        global $conf;
        if ($key != $conf->api_key) exit(1);
        $requete = "SELECT *
                      FROM user
                     WHERE login = '" . addslashes($user)."'
                       AND pass = '" . addslashes($pass)."'";
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) == 1)
        {
            $token = bin2hex(openssl_random_pseudo_bytes(48));
            $requete = "INSERT INTO api (`token`,`login`)
                              VALUES ('".addslashes($token)."','".addslashes($user)."')
             ON DUPLICATE KEY UPDATE `token` = '".addslashes($token)."' ";
            $sql = $this->db->query($requete);
            setcookie("api",$token);
            if ($sql)
                return $token;
            else
                return false;
        }
        else return false;
    }
}
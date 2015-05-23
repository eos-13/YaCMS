<?php
class model_user extends common
{
    public $id;
    public $user;
    public $message;
    public $action;
    public $success;
    private $extension;
    public function run()
    {
    }
    public function editable()
    {
        global $current_user;
        if (isset($this->user) &&  $current_user->get_id() == $this->user->get_id())
        {
            return true;
        } else {
            if ($current_user->has_right('admin') || $current_user->has_right('user'))
            {
                return true;
            }
        }
        return false;
    }
    public function validate($post)
    {
        foreach($post as $key=>$val)
        {
            if ($key=="action") continue;
            if ($key=="id") continue;

            $requete = "SELECT *
                          FROM user
                         WHERE `".$key."` = '".addslashes($val)."'
                           AND md5 <> '".addslashes($post['id'])."' ";
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
    public function update($post)
    {
        //On vérifie le mot de passe
        global $conf;
        if ($post['password'] != $post['password_again'])
        {
            $this->message = "Les mots de passes ne correspondent pas";
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
        $u->fetch_by_md5($post['id']);
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
        if($post['password']."x" != "x")
        {
            $u->set_pass(md5($post['password']));
            if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        }
        $u->set_public_profile($post['public_profile']);
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }
        //$u->set_avatar_path($avatar_path)
        $u->set_description($post['description']);
        if (!$sql) { $this->db->rollback(); $this->message = "Impossible de créer le compte"; return false; }


        if ($_FILES["avatar_path"]['size'] > 0 && $this->upload_image($post['id']) )
        {
            $u->set_avatar_path('/img/avatars/'.$post['id'].".".$this->extension);
        } elseif ($_FILES["avatar_path"]['size'] > 0) {
            $this->db->rollback();
            return false;
        }

        $this->db->commit();
        $this->message = "Vos informations ont été modifiées";
        if ($_FILES["avatar_path"]['size'] == 0  && $_FILES["avatar_path"]['error'] == 1)
        {
            $this->message = "Vos informations ont été modifiées, toutefois l'avatar dépassait la taille autorisée (".round($conf->max_avatar_size/1024)."ko)";
        }
        return true;
    }
    private function upload_image($id)
    {
        global $conf;
        //Сheck that we have a file
        if((!empty($_FILES["avatar_path"])) && ($_FILES['avatar_path']['error'] == 0)) {
            //Check if the file is JPEG image and it's size is less than 350Kb
            $filename = basename($_FILES['avatar_path']['name']);
            $ext = substr($filename, strrpos($filename, '.') + 1);
            if (($ext == "jpg" || $ext == "png" || $ext == "jpeg") && ($_FILES["avatar_path"]["type"] == "image/jpeg" || $_FILES["avatar_path"]["type"] == "image/png"  ) &&
                    ($_FILES["avatar_path"]["size"] < $conf->max_avatar_size)) {
                        $this->extension = $ext;
                        //Determine the path to which we want to save this file
                        $newname = $conf->main_document_root.'/img/avatars/'.$id.".".$ext;
                        //Attempt to move the uploaded file to it's new place
                        if ((move_uploaded_file($_FILES['avatar_path']['tmp_name'],$newname))) {
                            return true;
                            //echo "It's done! The file has been saved as: ".$newname;
                        } else {
                            $this->message = "Error: A problem occurred during file upload!";
                            return false;
                        }
                    } else {
                        $this->message = "Error: Only .jpg and png images under ".round($conf->max_avatar_size/1024)."kb are accepted for upload";
                        return false;
                    }
        } else {
            $this->message = "Error: No file uploaded";
            return false;
        }
        return false;
    }
}
?>
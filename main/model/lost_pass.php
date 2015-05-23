<?php
class model_lost_pass extends common
{
    public $message;
    public $action;
    public $token;
    public $email;
    public $success;
    public function run()
    {
        return true;
    }
    public function valid_token($post)
    {
        $this->success = false;
        if ($this->email . 'x' != "x" && $this->token."x" != "x" && $post['password'] == $post['password_again'])
        {
            $u = new user($this->db);
            $this->success = $u->valid_token($this->email,$this->token,$post['password']);
        }
    }
    public function gen_tmp_token()
    {
        if ($this->email . 'x' != "x" && !$this->token)
        {
            $u = new user($this->db);
            $r = $u->set_temporary_token($this->email);
            return $r;
        }
        return false;
    }
}
?>
<?php
class model_login extends common{
    protected $db;
    protected $user;
    public function run()
    {
        return true;
    }
    public function login($post)
    {
        global $session;
        $user_name=isset($post['login'])?$post['login']:"";
        $user_password = isset($post['pass'])?$post['pass']:"";
        $set_remember_me_cookie = (isset($post['stayconn']) && $post['stayconn']=="on"?true:false);

        // we do negative-first checks here, for simplicity empty username and empty password in one line
        if (empty($user_name) OR empty($user_password))
        {
            $session->add('feedback_negative', _('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }
        $this->user = new user($this->db);

        // get all data of that user (to later check if password and password_hash fit)
        $result = $this->user->fetch_by_username($user_name);

        // Check if that user exists. We don't give back a cause in the feedback to avoid giving an attacker details.
        if (!$result) {
            $session->add('feedback_negative', _('FEEDBACK_LOGIN_FAILED'));
            return false;
        }

        // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        if (($this->user->get_failed_logins() >= 3) AND (convert_time_us_to_ts($this->user->get_last_failed_login()) > (time() - 30))) {
            $this->inc_failed_login();
            $session->add('feedback_negative', _('FEEDBACK_PASSWORD_WRONG_3_TIMES'));
            return false;
        }

        // if hash of provided password does NOT match the hash in the database: +1 failed-login counter
        if ($user_password != $this->user->get_pass())
        {
            $this->inc_failed_login();
            // we say "password wrong" here, but less details like "login failed" would be better (= less information)
            $session->add('feedback_negative', _('FEEDBACK_PASSWORD_WRONG'));
            return false;
        }

        // from here we assume that the password hash fits the database password hash, as password_verify() was true

        //Locked account
        if ($this->user->get_is_locked() == 1)
        {
            $session->add('feedback_negative', _('FEEDBACK_ACCOUNT_IS_LOCKED'));
            return false;
        }

        // if user is not active (= has not verified account by verification mail)
        if ($this->user->get_active() != 1)
        {
            $session->add('feedback_negative', _('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
            return false;
        }

        // reset the failed login counter for that user (if necessary)
        if ($this->user->get_last_failed_login() > 0)
        {
            $this->set_failed_logins();
        }

        // save timestamp of this login in the database line of that user
        $this->set_last_login();

        // if user has checked the "remember me" checkbox, then write token into database and into cookie
        if ($set_remember_me_cookie) {
            $this->set_remember_me();
        }

        // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
        $this->set_session();

        // return true to make clear the login was successful
        // maybe do this in dependence of set_session ?
        return true;
    }

    public function login_with_cookie($cookie)
    {
        global $session;
        // do we have a cookie ?
        if (!$cookie) {
            $session->add('feedback_negative', _('FEEDBACK_COOKIE_INVALID_NP'));
            return false;
        }

        // check cookie's contents, check if cookie contents belong together
        list ($user_md5, $token, $hash) = explode(':', $cookie);
        if ($hash !== hash('sha256', $user_md5 . ':' . $token)) {
            $session->add('feedback_negative', _('FEEDBACK_COOKIE_INVALID_WR'));
            return false;
        }

        // do not log in when token is empty
        if (empty($token)) {
            $session->add('feedback_negative', _('FEEDBACK_COOKIE_INVALID_NT'));
            return false;
        }

        load_alternative_class('class/user.class.php');
        $user = new User($this->db);
        // get data of user that has this id and this token
        $ret = $user->fetch_user_with_token($user_md5, $token);

        //Locked account
        if ($user->get_is_locked() == 1)
        {
            $session->add('feedback_negative', _('FEEDBACK_ACCOUNT_IS_LOCKED'));
            return false;
        }

        // if user with that id and exactly that cookie token exists in database
        if ($ret && $ret->get_active() == 1)
        {
            $this->user = $user;
            // successfully logged in, so we write all necessary data into the session and set "user_logged_in" to true
            $this->set_session();
            // save timestamp of this login in the database line of that user
            $this->set_last_login();

            // NOTE: we don't set another remember_me-cookie here as the current cookie should always
            // be invalid after a certain amount of time, so the user has to login with username/password
            // again from time to time. This is good and safe ! ;)

            $session->add('feedback_positive', _('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));
            return true;
        } else {
            $session->add('feedback_negative', _('FEEDBACK_COOKIE_INVALID_NU'));
            return false;
        }
    }
    public function logout()
    {
        global $session;
        $this->delete_cookie();
        $session->destroy();
    }
    public function set_session()
    {
        global $session;
        $session->init();
        $session->set('user_md5', $this->user->get_md5());
        $session->set('user_name', $this->user->get_login());
        $session->set('user_email', $this->user->get_email());

        // get and set avatars
        $session->set('user_avatar_file', $this->user->get_avatar_path());

        // finally, set user as logged-in
        $session->set('user_logged_in', true);
    }

    public function inc_failed_login()
    {
        $this->user->set_failed_logins("1");
        $this->user->set_last_failed_login();
    }
    public function set_failed_logins()
    {
        $this->user->set_failed_logins(0);
    }
    public function set_last_login()
    {
        $this->user->set_last_login();
    }
    public function set_remember_me()
    {
        // generate 64 char random string
        $random_token_string = hash('sha256', mt_rand());

        // write that token into database
        $this->user->set_remember_me($random_token_string);

        // generate cookie string that consists of user md5, random string and combined hash of both
        $cookie_string_first_part = $this->user->get_md5() . ':' . $random_token_string;
        $cookie_string_hash = hash('sha256', $cookie_string_first_part);
        $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

        // set cookie
        global $conf;
        setcookie('remember_me', $cookie_string, time() + $conf->cookie_runtime, $conf->cookie_path );
    }
    public function delete_cookie()
    {
        global $conf;
        setcookie('remember_me', false, time() - (3600 * 24 * 3650), $conf->cookie_path);
    }
    public function is_user_logged_in()
    {
        global $session;
        return $session->userIsLoggedIn();
    }
}

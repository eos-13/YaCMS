<?php

class Session
{
    public static function init()
    {
        // if no session exist, start the session
        if (session_id() == '')
        {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    public static function add($key, $value)
    {
        $_SESSION[$key][] = $value;
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function userIsLoggedIn()
    {
        return ($this->get('user_logged_in') ? true : false);
    }
}

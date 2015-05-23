<?php
class common_soap_object
{
    protected $db;
    public $log;
    public $obj;

    public function __construct($db,$class=false)
    {
        $this->db = $db;
        global $log;
        $this->log = $log;
        if ($class && $class != "auth")
            $this->obj = new $class($this->db);
    }
    protected function load($id=false,$lang=false)
    {
        if ($lang && method_exists($this->obj, 'display_lang'))
        {
            $this->obj->display_lang($lang);
        }
        if ($id && method_exists($this->obj, 'set_id'))
        {
            $this->obj->set_id($id);
        }
        if ($id && method_exists($this->obj, 'fetch'))
        {
            $this->obj->fetch($id);
        }
    }
}
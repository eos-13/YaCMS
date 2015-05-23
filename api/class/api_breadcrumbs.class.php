<?php
load_alternative_class('class/common_soap_object.class.php');
class api_breadcrumbs extends common_soap_object
{
    protected $db;
    public $menu;
    public $log;
    public $breadcrumbs=array();
    private $page;
    private $b;
    public function __construct($db,$id)
    {
        $this->db = $db;
        global $log;
        $this->log = $log;
        load_alternative_class("class/breadcrumbs.class.php");
        $this->b = new breadcrumbs($this->db);
        load_alternative_class("class/page.class.php");
        $p = new page($this->db);
        $p->fetch($id);
        $this->b->set_page($p);
    }
    /**
     *
     * @param string $c
     * @return array $pages[]
     * @desc Take the class of the MVC view and build breadcrumbs
     */
    public function get_breadcrumbs($c)
    {
        return $this->b->get_breadcrumbs($c);
    }
    /**
     *
     * @return array $js[]
     * @desc return needed js lib to display breadcrumbs
     */
    public function get_js()
    {
        return $this->b->get_js();
    }
    /**
     *
     * @return array $css[]
     * @desc return needed css files to display breadcrumbs
     */
    public function get_css()
    {
        return $this->b->get_css();
    }
    /**
     * @return string $code
     * @desc Return needed js code to display breadcrumbs
     */
    public function get_js_code()
    {
        return $this->b->get_js_code();
    }
    /**
     * @return string $code
     * @desc Return needed css code to display breadcrumbs
     */
    public function get_css_code()
    {
        return $this->b->get_css_code();
    }
}
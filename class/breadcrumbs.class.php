<?php
class breadcrumbs
{
    protected $db;
    public $menu;
    public $log;
    public $breadcrumbs=array();
    private $page;


    public function __construct($db)
    {
        $this->db = $db;
        global $log;
        $this->log = $log;
    }
    /**
     *
     * @param object $page
     * @desc set a page
     */
    public function set_page($page)
    {
        $this->page = $page;
    }

    /**
     *
     * @param string $c
     * @return array $pages[]
     * @desc Take the class of the MVC view and build breadcrumbs
     */
    public function get_breadcrumbs($c)
    {

        $this->iter=0;
        global $conf;
        $this->breadcrumbs=false;
        if (preg_match('/view_/',$c))
        {
            if (isset($this->page)  && $this->page && $this->page->get_id()>0)
            {
                $requete = "SELECT parent_refid,
                                   url,
                                   title,
                                   lang
                              FROM page
                             WHERE id = ".$this->page->get_id();
                $sql = $this->db->query($requete);
                $res = $this->db->fetch_object($sql);
                if (isset($res->parent_refid) && $res->parent_refid > 0)
                    $this->get_breadcrumbs_recurs($res->parent_refid,$res->url,$res->title);
                $this->breadcrumbs[]="<a href='".$conf->main_base_path.""."'>".$conf->site_name."</a>";// $this->page->get_id();
                $this->breadcrumbs = array_reverse($this->breadcrumbs);
            }
        }
        return $this->breadcrumbs;
    }
    private function get_breadcrumbs_recurs($parent_refid,$url,$titre)
    {
        global $conf;
        $this->breadcrumbs[]="<a " .($this->iter==0?"class='active'":"") ." href='".preg_replace('/\/$/','',$conf->main_base_path)."/". $url."'>".$titre."</a>";
        $this->iter++;
        $requete = "SELECT parent_refid,
                           url,
                           title,
                           lang
                      FROM page
                     WHERE id = ".$parent_refid;
        $sql = $this->db->query($requete);
        $res = $this->db->fetch_object($sql);
        if (isset($res->parent_refid) && $res->parent_refid > 0)
            $this->get_breadcrumbs_recurs($res->parent_refid,$res->url,$res->title);
    }
    /**
     *
     * @return array $js[]
     * @desc return needed js lib to display breadcrumbs
     */
    public function get_js()
    {
        return array('jquery');
    }
    /**
     * @return array $css[]
     * @desc return needed css files to display breadcrumbs
     */
    public function get_css()
    {
        return array('breadcrumbs');
    }
    /**
     * @return string $code
     * @desc Return needed js code to display
     */
    public function get_js_code()
    {
    }
    /**
     * @return string $code
     * @desc Return needed css code to display
     */
    public function get_css_code()
    {
    }
}
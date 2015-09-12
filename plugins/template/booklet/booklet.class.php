<?php
load_alternative_class('class/plugins.class.php');
class booklet extends plugins
{
    protected $name = "booklet";
    protected $version = "1.0";
    protected $path = "booklet";
    private $params;

    public function make_form($url,$val,$form_tab_num,$data=false,$pid=false)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");
        //$html = "<form action=\"".$url."\" METHOD='post'>";
        //$html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html = "<div>".dcgettext('plugins',"Booklet plugin",LC_ALL)."</div>";
//         $html .= $this->params_form_builder($this->get_all_params(),$val);
//
        //$html .= "</form>";
        $html .= "<form action=\"".$url."\" METHOD='post'>";
        $html .= "<div>";
        $requete = "SELECT id,
                           title
                      FROM section
                     WHERE page_refid = ".$pid."
                  ORDER BY `order`";
        $html .= "</div>";
        $html .= "<div>";
        $html .= "<input type='hidden' name='load' value='".$form_tab_num."'/>";
        $html .= "<input name='process' value='save_booklets' type='hidden' />";
        $html .= "<select name='chapList[]' multiple size='5' id='multiple_select'>";
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $selected = false;
            foreach($val as $key1=>$val1)
            {
                foreach($val1 as $val2)
                {
                    if ($val2 == $res->id)
                    {
                        $selected = true;
                        break;
                    }
                }
            }
            $html .= "<option ".($selected?"selected":"")." value='".$res->id."'>".$res->title."</option>";
        }
        $html .= "</select>";
        $html .= "</div>";
        $html .= "<br/><button>".dcgettext('plugins','OK',LC_ALL)."</button>";
        $html .= "</form>";
        textdomain("messages");
        return $html;
    }
    public function save_booklets($post,$allplugins_data)
    {
        if (isset($allplugins_data->chapList) &&  is_object($allplugins_data->chapList))
        {
            $booklets_data = json_decode($allplugins_data->chapList);
        } else {
            $booklets_data = json_decode('{"chapList":[]}',false);
        }
        //$allplugins_data = json_decode($allplugins_data);
        //On récupère tous les paramètres enregistrés
        foreach($post as $key => $val)
        {
            if (preg_match('/^chapList$/',$key))
            {
                $booklets_data->chapList=$val;
            }
        }
        return json_encode($booklets_data);
    }
    public function get_display_js()
    {

        return array('jquery','jquery.booklet.1.1.0.min','jquery.easing');
    }
    public function get_js_code()
    {
        $js = <<<EOF
        jQuery(document).ready(function(){
            jQuery("select[multiple]").multiselect({header: i18n.translate("Choose an Option!").fetch()});
        }
EOF;
        return $js;
    }
    public function get_js()
    {
        return array('jquery','jquery-ui-multiselect');
    }
    public function get_display_css()
    {
        return array('booklet');
    }

    private function get_all_params()
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");
        $array = array(

        );
        textdomain("messages");
        return $array;
    }
    public function get_params($page)
    {
        bindtextdomain("plugins", 'plugins/template/'.$this->path.'/lang');
        textdomain("plugins");
        $ret = false;
        $default = $this->get_all_params();
        foreach ($default as $key=>$val)
        {
            $ret[$key]=$val['default'];
        }
        $params_json = $page->get_plugins();
        $params_json = json_decode($params_json);
        if ($params_json)
        {
            $params = json_decode($params_json->booklet);
            foreach ($params as $key=>$val)
            {
                if ($val)
                    $ret[$key]=$val;
            }
        }
        $this->params = $ret;
        textdomain("messages");
        return $ret;
    }
}
?>
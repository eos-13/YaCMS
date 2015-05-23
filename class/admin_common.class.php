<?php
class admin_common extends common
{
    protected $db;
    public $menu = "";
    public $css = array();
    public $js = array();
    public $css_code = false;
    public $js_code = false;
    public $meta = false;
    protected $req;
    protected $page;
    protected $needrights=array();
    public $log;

    public function __construct($db,$req,$page=false,$run=true,$search_mode=false)
    {
        global $log;
        $this->log = $log;
        global $current_user;
        $this->access_rights();
        $granted = $this->is_granted();
        if (!$current_user->get_id() > 0 )
        {
            global $conf;
            header('location:'.$conf->main_url_root.'/login?url='.urlencode($conf->main_url_root.'/admin/'.$req));
        } else if (!$granted)
        {
            global $conf;
            include_once('errors/401.php');
            exit;
        }
        $this->db = $db;
        $this->req=$req;
        $this->set_title($this->req);
        $this->page = $page;
        $this->set_extra_render('leftmenu', $this->left_menu($req));
        $this->set_css($req);
        $this->set_js($req);
        $this->set_template_file(make_path('template',$this->req,'html'));
        $this->search_mode = $search_mode;
        if ($page)
            $this->page = $page;

        if (function_exists('apc_add')) { $this->set_cache('apc'); }
        elseif (function_exists( 'memcache_set' ) ) { $this->set_cache('memcache'); }

        $this->log->log(get_class($this). ' Uid: ' .$current_user->id.' ',LOG_DEBUG);
        $this->tinymce_add_style();
        if ($run)
            $this->run();
    }

    public function tinymce_add_style()
    {
        $c = get_class($this);
        if (!preg_match('/^view_/',$c)) return false;
        global $conf;
        if (isset($conf->tinymce_custom_style) && $conf->tinymce_custom_style."x" != "x")
        {
            $this->set_js_code("var custom_menu = ".$conf->tinymce_custom_style.";");
        } else {
            $this->set_js_code("var custom_menu = false;");
        }
    }
    public function left_menu()
    {
        $this->set_js(array('jquery', 'jquery.sidr.min'));
        $this->set_css(array('jquery.sidr.light','admin'));
        $html="";

        global $advanced_mode;
        $this->set_css_code('
            #mobile-header {
                display: none;
            }
            @media only screen and (max-width: 767px)
            {
                #mobile-header {
                    display: block;
                }
            }
        ');

        $html = '';
        $html .= '<div id="mobile-header">';
        //$html .= '<a id="responsive-menu-button" href="#sidr-main">Menu</a>';
        $html .= '</div>';

        $html .= '<div id="navigation" style="display: none;">';
        $html .= '<nav class="nav">';
        $html .= '<ul>';
        $plugins = array();
        global $conf;
        $dir = new DirectoryIterator($conf->main_document_root."/plugins/template");
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                if (is_dir($conf->main_document_root."/plugins/template/".$fileinfo->getFilename()))
                {
                    $plugins[] = array("url" => "plugins?id=".basename($fileinfo->getFilename()),
                                       "title" => $fileinfo->getFilename(),
                                       "adv" => true
                    );
                }
            }
        }
        $cur_url = preg_replace('/\?advanced_mode=[0-9]/','',curPageURL());
        $cur_url = preg_replace('/\&advanced_mode=[0-9]/','',$cur_url);
        $cur_url = preg_replace('/\?&/','?',$cur_url);
        $cur_url = preg_replace('/\&&/','&',$cur_url);
        if (preg_match('/\?/',$cur_url))
        {
            $advanced_mode_url = $cur_url."&advanced_mode=".($advanced_mode?0:1);
        } else {
            $advanced_mode_url = '?advanced_mode='.($advanced_mode?0:1);
        }
        $allnode=array(
                array(
                        "url"=>'index',
                        "title" => _("Index"),
                        "adv" => true
                ),
                array(
                        "url" => $conf->main_url_root,
                        "title" => _("Site"),
                        "adv" => true
                ),
                array(
                        "url" => 'lang',
                        "title" => _("Langue"),
                        "adv" => true
                ),
                array(
                        "url" => $advanced_mode_url,
                        "title" =>($advanced_mode? _("Mode simple"):  _("Mode expert")),
                        "adv" => true
                ),
                array(
                        "url" => "map",
                        "title" => _("Edition"),
                        "adv" => true,
                        "children" =>
                        array (
                                array(
                                        'url' => 'map',
                                        'title' => _('Map'),
                                        "adv" => true
                                ),
                                array(
                                        'url' => 'template',
                                        'title' => _('Modèle'),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'hierarchie',
                                        'title' => _('Hierarchie'),
                                        "adv" => true
                                ),
                                array(
                                        'url' => 'header',
                                        'title' => _("En tête"),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'footer',
                                        'title' => _('Pied de page'),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'col_gauche',
                                        'title' => _('Colonne de gauche'),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'col_droite',
                                        'title' => _('Colonne de droite'),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'edit',
                                        'title' => _('CSS/JS'),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'submenu',
                                        'title' => _("Sous-menu"),
                                        "adv" => false
                                ),

                                array(
                                        'url' => 'pageondisk',
                                        'title' => _('Page sur le disque'),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'forms',
                                        'title' => _('Formulaires'),
                                        "adv" => false
                                ),
                              )
                ),
                array(
                        "url" => 'manage',
                        "title" => _("Gestion"),
                        "adv" => true,
                        "children" =>
                        array(
                                array(
                                        "url" => 'conf',
                                        "title" => _("Configuration"),
                                        "adv" => false
                                ),
                                array(
                                        "url" => 'robot',
                                        "title" => _("Robot.txt"),
                                        "adv" => false
                                ),
                                array(
                                        "url" => 'files',
                                        "title" => _("Fichiers"),
                                        "adv" => true
                                ),
                                array(
                                        'url' => 'manage',
                                        'title' => _("Gestion"),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'message',
                                        'title' => _("Bandeau de messages" ),
                                        "adv" => true
                                ),
                                array(
                                        'url' => 'cookiemessage',
                                        'title' => _("Messages cookies" ),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'cache',
                                        'title' => _("Gestion des caches" ),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'image',
                                        'title' => _("Redimenssionnement des images" ),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'data_file',
                                        'title' => _("Metadonnées des fichiers" ),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'import_xml',
                                        'title' => _("Import XML" ),
                                        "adv" => false
                                ),
                        ),
                ),
                array(
                        "url" => 'user',
                        "title" => _('Utilisateur'),
                        "adv" => false,
                        "children" =>
                        array(
                                array(
                                        "url" => 'user',
                                        "title" => _("Utilisateurs"),
                                        "adv" => false
                                ),
                                array(
                                        "url" => 'rights',
                                        "title" => _("Droits"),
                                        "adv" => false
                                ),
                                array(
                                        "url" => 'group',
                                        "title" => _("Groupes"),
                                        "adv" => false
                                ),
                                array(
                                        "url" => 'group_rights',
                                        "title" => _("Droits de groupes"),
                                        "adv" => false
                                )
                        ),
                ),
                array(
                        "url" => 'comment',
                        "title" => _("Commentaires"),
                        "adv" => true,
                        "children" =>
                        array(
                                array(
                                        'url' => 'comment',
                                        "title" => _("Modération"),
                                        "adv" => true
                                ),
                                array(
                                        'url' => 'akismet',
                                        "title" => _("Spam protection"),
                                        "adv" => false
                                ),
                        ),
                ),
                array(
                        "url" => "forms",
                        "title" => _("Formulaires"),
                        "adv" => false,
                        "children" =>
                        array (
                                array(
                                        'url' => 'forms',
                                        'title' => _('Formulaires'),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'result_forms',
                                        'title' => _('Résultats'),
                                        "adv" => false
                                ),
                        )
                ),
                array(
                        "url" => 'internal_msg',
                        "title" => _("Messages internes"),
                        "adv" => false,
                        "children" =>
                        array(
                                array(
                                        'url' => 'internal_msg',
                                        "title" => _("Messages internes"),
                                        "adv" => false
                                ),
                        ),
                ),
                array(
                        "url" => 'external_msg',
                        "title" => _("Messages externes"),
                        "adv" => false,
                        "children" =>
                        array(
                                array(
                                        'url' => 'external_msg',
                                        "title" => _("Messages externes"),
                                        "adv" => false
                                ),
                        ),
                ),
                array(
                        "url" => 'publication',
                        "title" => _("Publication"),
                        "adv" => false,
                        "children" =>
                        array(
                                array(
                                        'url' => 'group_publication',
                                        "title" => _("Groupes"),
                                        "adv" => false
                                ),
                                array(
                                        'url' => 'message_publication',
                                        "title" => _("Message aux groupes"),
                                        "adv" => false
                                ),
                        ),
                ),
                array(
                        "url" => 'mail_model',
                        "title" => _("Mails"),
                        "adv" => false,
                        "children" =>
                        array(
                                array(
                                        'url' => 'mail_model',
                                        "title" => _("Modèle email"),
                                        "adv" => false
                                ),
                        ),
                ),
                array(
                        "url" => 'php_info',
                        "title" => _("Infos"),
                        "adv" => false,
                        "children" =>
                        array(
                                array(
                                        'url' => 'php_info',
                                        "title" => _("Infos PHP"),
                                        "adv" => false
                                ),
                        ),
                ),
                array(
                        "url" => 'plugins',
                        "title" => _("Plugins"),
                        "adv" => true,
                        "children" => $plugins
                ),
        );
        global $advanced_mode;
        foreach($allnode as $key=>$val)
        {
            if (is_array($val))
            {
                if (!$val["adv"] && ! $advanced_mode) continue;
                $tmp1 = "<li><a href='".$val['url']."'>".$val['title']."</a>";
                if (isset($val['children']) &&  is_array($val['children']))
                {
                    $tmp = false;
                    $first = false;
                    foreach($val['children'] as $val1)
                    {
                        $granted = $this->is_granted($this->access_rights($val1['url']));
                        if ($granted && ! $advanced_mode == true && ! $val1['adv']) $granted = false;
                        if ($granted && $advanced_mode == true) $granted = true;
                        if ($granted && ! $advanced_mode == true  && $val1['adv']) $granted = true;
                        if (!$granted)
                        {
                            continue;
                        }
                        $active = ($this->req == $val1['url']?'class="active"':"");
                        $span   = ($this->req == $val1['url']?'<span class="active_menu" style="float:right;">&gt;</span>':"");
                        $tmp .= "<ul>";
                        $tmp .= "<li ".$active.">".$span."<a href='".$val1['url']."'>".$val1['title']."</a></li>";
                        if (!$first) $first = "<li><a href='".$val1['url']."'>".$val['title']."</a></li>";
                        $tmp .= "</ul>";
                    }
                    $granted_1=$this->is_granted($this->access_rights($val['url']));
                    if ($granted_1 && ! $advanced_mode && ! $val['adv']) $granted_1 = false;
                    if ($granted_1 && $advanced_mode) $granted_1 = true;
                    if ($granted_1 && ! $advanced_mode  && $val['adv']) $granted_1 = true;
                    if (!$granted_1) $tmp1 = $first; //Si pas d'accès à l'entete
                    //if (!$tmp && $granted_1) $html .= $tmp;
                    if ($tmp) $html.= $tmp1 . $tmp; //Si c'est pas vide
                    else $html .= $tmp1;
                } else {
                    $granted_1=$this->is_granted($this->access_rights($val['url']));
                    if ($granted_1 && ! $advanced_mode && ! $val['adv']) $granted_1 = false;
                    if ($granted_1 && $advanced_mode) $granted_1 = true;
                    if ($granted_1 && ! $advanced_mode  && $val['adv']) $granted_1 = true;

                    if ($granted_1) $html .= $tmp1; //Si d'accès à l'entete
                }
                $html .= "</li>";
            }
        }
        $html .= '</ul>';
        $html .= '</nav></div>';
        $this->set_js_code('
        jQuery(document).ready(function(){
            jQuery("#responsive-menu-button").sidr({
                name: "sidr-main",
                source: "#navigation",
                speed: 0,
                body: false
            });
            jQuery.sidr("open", "sidr-main");
        });
        ');
        return $html;
    }

    public function get_css()
    {
        $this->css = array_unique($this->css);
        return $this->css;
    }
    public function get_js()
    {
        $this->js = array_unique($this->js);
        return $this->js;
    }
    public function set_css($in)
    {
        if(is_array($in))
        {
            foreach($in as $val)
            {
                $tmp = make_path('css',$val,"css",true,true);
                if ($tmp) $this->css[]=$tmp;
            }
        } else {
            $tmp = make_path('css',$in,"css",true,true);
            if ($tmp) $this->css[]=$tmp;
        }
    }
    public function set_js($in)
    {
        if(is_array($in))
        {
            foreach($in as $val)
            {
                $tmp = make_path('js',$val,"js",true,true);
                if ($tmp) $this->js[]=$tmp;
            }
        } else {
            $tmp = make_path('js',$in,"js",true,true);
            if ($tmp) $this->js[]=$tmp;
        }

    }
    public function gen_error_page($msg,$debug_data=array())
    {
        $tpl_file = make_path('template',"error_admin",'html');
        try{
            global $conf,$current_user;
            $render_array = array(
                    'leftmenu' => $this->left_menu(),
                    'css' => $this->get_css(),
                    'id' => (isset($this->id)?$this->id:false),
                    'js' => $this->get_js(),
                    'js_code' => $this->get_js_code(),
                    'css_code' => $this->get_css_code(),
                    'title' => "Error from ".$this->req,
                    "cur_user" => $current_user->get_all(),
                    "conf" => $conf,
                    "main" => $this->req,
                    "msg" => $msg,
                    "debug" => 0,
                    "all_vars" => get_defined_vars()

            );
            foreach($debug_data as $key=>$val){
                if (is_object($val))
                {
                    $render_array[get_class($val)] = $val;
                }
            }
            $cache = "file";
            if (function_exists('apc_add')) { $cache = 'apc'; }
            elseif (function_exists( 'memcache_set' ) ) { $cache = 'memcache'; }
            $template = new H2o($tpl_file, array( 'cache' => $cache ));
            $fiche = $template->render($render_array);
            echo $fiche;
        } catch (Exception $e){
            echo 'Exception fatale reçue : ',  $e->getMessage(), "\n";
        }
    }
    public function get_file_content($path)
    {
        $content=false;
        if (is_file($path) && is_writable($path))
        {
            $content = file_get_contents($path);
        }
        return $content;
    }
    public function set_file_content($path,$content)
    {
        if (is_file($path) && is_writable($path))
        {
            file_put_contents($path, $content);
        }
    }
    public function make_formpart($arr,$val,$can_edit=true)
    {
        $html = "";
        $js=false;
        $js_code=false;
        switch ($arr['type'])
        {
            case "select":
                {
                    $opt = (isset($arr["option"])?"multiple size='5'":"");
                    $html = "<SELECT ".($can_edit?"":"DISABLED")." name='".$arr['field'].(isset($arr["option"]) && $arr['option']=="multiple"?"[]":"" )."' id='".$arr['field']."' ".$opt.">";
                    if(isset($arr['source_dir']))
                    {
                        $optGroup_open=false;
                        foreach(array("main","customer") as $dir_root)
                        {
                            if(isset($arr['optgroup']) && $arr['optgroup']==1)
                            {
                                $html.="<optgroup label='".$dir_root."'>";
                                $optGroup_open= true;
                            }
                            if (is_dir($dir_root."/".$arr['source_dir']))
                            {
                                $dir = new DirectoryIterator($dir_root."/".$arr['source_dir']);
                                foreach ($dir as $fileinfo)
                                {
                                    if (!$fileinfo->isDot())
                                    {
                                        if (is_array($val) && in_array($fileinfo->getFilename(), $val))
                                        {
                                            $html.= "<option SELECTED=\"true\" value='".$fileinfo->getFilename()."'>".$fileinfo->getFilename()."</option>";
                                        } else {
                                            $html.= "<option value='".$fileinfo->getFilename()."'>".$fileinfo->getFilename()."</option>";
                                        }
                                    }
                                }
                            }
                            if($arr['optgroup']==1)
                            {
                                $html.="</optgroup>";
                                $optGroup_open=false;
                            }
                            if ($arr['optgroup']==1 && $optGroup_open=false) $html.="</optgroup>";
                        }
                    } else if($arr['source_table']){
                        $requete="SELECT ".$arr['selectid']." as i,".$arr['selectname']." as n
                                FROM ".$arr['source_table']."
                            ORDER BY ".$arr['order'];
                        $sql = $this->db->query($requete);
                        while($res = $this->db->fetch_object($sql))
                        {
                            if ($res->i == $val)
                            {
                                $html .= "<option SELECTED value='".$res->i."'>".$res->n."</option>";
                            } else {
                                $html .= "<option value='".$res->i."'>".$res->n."</option>";
                            }

                        }
                    }
                    $html .= "</SELECT>";
                }
                break;
            case "checkbox":
                {
                    $ck = ($val == 1?"checked":"");
                    $html='<input '.($can_edit?"":"DISABLED").' type="checkbox" id="'.$arr["field"].'"  name="'.$arr["field"].'" '.$ck.' />';
                }
                break;
            case "text":
                {
                    $html='<input '.($can_edit?"":"DISABLED").' type="text" id="'.$arr["field"].'"  name="'.$arr["field"].'" value="'.$val.'"/>';
                }
                break;
            case "textarea":
                {
                    $html='<textarea  '.($can_edit?"":"DISABLED").' id="'.$arr["field"].'"  name="'.$arr["field"].'">'.$val.'</textarea>';
                }
                break;
            case "slider":
                {
                    $html='<span style= "float:left;" id="slider_info_'.$arr["field"].'">'.preg_replace('/,/','.',($val?$val:$arr['default'] )).'</span><div style="margin-left: 2em;" id="slider_'.$arr["field"].'" ></div>';
                    $html.= '<input type="hidden" id="'.$arr["field"].'" name="'.$arr["field"].'" value="'.$val.'"/>';
                    $js = array('jquery','jquery-ui.min');
                    $js_code = 'jQuery(document).ready(function(){';
                    $js_code .= 'jQuery("#slider_'.$arr["field"].'").slider({';
                    $js_code .= ' value:'. preg_replace('/,/','.',($val?$val:$arr['default'] )) .',';
                    $js_code .= ' min: '.preg_replace('/,/','.',$arr['min']).',';
                    $js_code .= ' max: '.preg_replace('/,/','.',( $arr['max'] + $arr['step'] / 2)) .',';
                    $js_code .= ' step: '.preg_replace('/,/','.',$arr['step']).',';
                    $js_code .= ' slide: function( event, ui ) {';
                    $js_code .= '    jQuery( "#'.$arr["field"].'" ).val(  ui.value );';
                    $js_code .= '    jQuery( "#slider_info_'.$arr["field"].'" ).text(  ui.value );';
                    $js_code .= ' }';
                    $js_code .= '});';
                    $js_code .= ' jQuery( "#'.$arr["field"].'" ).val( jQuery( "#slider_'.$arr["field"].'" ).slider( "value" ) );';
                    $js_code .= ' jQuery( "#slider_info_'.$arr["field"].'" ).text( jQuery( "#slider_'.$arr["field"].'" ).slider( "value" ) );';
                    $js_code .= '});';
                }
                break;
        }
        if ($js){
            return array("html" => $html, "js" => $js,'js_code' => $js_code);
        } else
            return $html;
    }
    public function get_display_js($in)
    {
        return false;
    }
    public function get_display_css($in)
    {
        return false;
    }
    public function get_plugins_datas()
    {
        return false;
    }
    public function get_available_lang()
    {
        $add_sep = "?";
        if (preg_match('/\?/',curPageURL()))
        {
            $add_sep = "&";
        }
        global $conf,$lang;
        $a = preg_split('/,/',$conf->available_lang);
        $html = "<div class='flags_switch'>";
        foreach($a as $key=>$val)
        {
            if (is_file($conf->main_document_root .'/img/flags/'.strtolower(substr($val,3,2)).'.png'))
            {
                if (strtolower(substr($val,3,2)) == strtolower(substr($lang,3,2)))
                {
                    $html.='<span><a title="'.$val.'" href="'.curPageURL().$add_sep.'editlang='.$val.'"><img class="current_lang" src ="'.$conf->main_base_path.'/img/flags/'.strtolower(substr($val,3,2)).'.png" /></a></span>';
                } else {
                    $html.='<span><a title="'.$val.'" href="'.curPageURL().$add_sep.'editlang='.$val.'"><img src ="'.$conf->main_base_path.'/img/flags/'.strtolower(substr($val,3,2)).'.png" /></a></span>';
                }
            }
        }
        $html.="</div>";
        return array('html' => $html,"list" => $a);
    }
    public function get_available_editlang()
    {
        $add_sep = "?";
        if (preg_match('/\?/',curPageURL()))
        {
            $add_sep = "&";
        }
        global $conf,$editlang;
        $a = preg_split('/,/',$conf->available_lang);
        $html = "<div class='flags_switch'>";
        foreach($a as $key=>$val)
        {
            if (is_file($conf->main_document_root .'/img/flags/'.strtolower(substr($val,3,2)).'.png'))
            {
                if (strtolower(substr($val,3,2)) == strtolower(substr($editlang,3,2)))
                {
                    $html.='<span><a title="'.$val.'" href="'.curPageURL().$add_sep.'editlang='.$val.'"><img class="current_lang" src ="'.$conf->main_base_path.'/img/flags/'.strtolower(substr($val,3,2)).'.png" /></a></span>';
                } else {
                    $html.='<span><a title="'.$val.'" href="'.curPageURL().$add_sep.'editlang='.$val.'"><img src ="'.$conf->main_base_path.'/img/flags/'.strtolower(substr($val,3,2)).'.png" /></a></span>';
                }
            }
        }
        $html.="</div>";
        return array('html' => $html,"list" => $a);
    }
    public function get_available_editlang_page($pages,$id)
    {
        $add_sep = "&";
        $add_sep_1 = "?";
        if (preg_match('/\?/',curPageURL_filter_get()))
        {
            $add_sep_1 = "&";
        }
        global $conf,$editlang;
        $a = preg_split('/,/',$conf->available_lang);
        $html = "<div class='flags_switch'>";
        foreach($a as $key=>$val)
        {
            if (is_file($conf->main_document_root .'/img/flags/'.strtolower(substr($val,3,2)).'.png'))
            {
                if (strtolower(substr($val,3,2)) == strtolower(substr($editlang,3,2)))
                {
                    $html.='<span><a title="'.$val.'" href="'.curPageURL_filter_get().$add_sep_1.($pages[$val] > 0?'id='.$pages[$val]:'from_id='.$id).$add_sep.'editlang='.$val.'&current_edit_lang='.$editlang.'&action=switch_lang"><img class="current_lang" src ="'.$conf->main_base_path.'/img/flags/'.strtolower(substr($val,3,2)).'.png" /></a></span>';
                } else {
                    $html.='<span><a title="'.$val.'" href="'.curPageURL_filter_get().$add_sep_1.($pages[$val] > 0?'id='.$pages[$val]:'from_id='.$id).$add_sep.'editlang='.$val.'&current_edit_lang='.$editlang.'&action=switch_lang"><img src ="'.$conf->main_base_path.'/img/flags/'.strtolower(substr($val,3,2)).'.png" /></a></span>';
                }
            }
        }
        $html.="</div>";
        return array('html' => $html,"list" => $a);
    }
}
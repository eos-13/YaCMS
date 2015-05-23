<?php
ini_set('max_execution_time',3600);

class view_edit_page extends admin_common
{
    public $can_unlock = false;
    public $can_edit = false;
    protected $page;
    public $message = false;
    public $pages_lang;

    private function js_for_page($load=0)
    {
        $js = "var id = '".$this->id."';";
        $js .= 'jQuery(document).ready(function()
                {
                    jQuery("#tabs").tabs( "option", "active", '.$load.' );
                })
        ';
        return $js;
    }
    private function set_tinymce_vars($section,$p)
    {
        $tmp = 'var all_tinymce=" ';
        $tmpA = array('tinymce');
        foreach($section as $key=>$val)
        {
            $tmpA[]= 'section_'.$val['id'];
        }
        $tmp .= join(',',$tmpA);
        $tmp .='";';
        global $conf;
        $tmp .= 'var base_root_path="'.$conf->main_base_path.'"';
        $this->set_js_code($tmp);
        $array=false;
        $array=json_decode($conf->site_css);
        if (is_array($p) && is_array($array))
            $array = array_merge($array,$p);
        if (is_array($p) && !is_array($array))
            $array = $p;
        if (!is_array($p) && !is_array($array))
            $array=array();
        $array = array_unique($array);
        foreach($array as $key=>$val)
        {
            $array[$key] = make_path('css', $val, 'css',false,true);
        }
        //$array[]=
        $this->set_js_code ( 'var content_css_set="' . join ( ',', $array ) . '";' );
        if (! $this->can_edit)
        {
            $this->set_js_code ( 'var readonly="1";' );
        } else {
            $this->set_js_code ( 'var readonly=false;' );
        }
        $this->set_js_code('var usewrapper = true;');
        $this->set_js_code('var custom_main_css_class = "'.$conf->custom_main_css_class.'" ;');
        $this->set_js_code('var custom_section_css_class = "'.$conf->custom_section_css_class.'" ;');
    }
    public function run($content,$section,$property,$group_publi,$has_draft,$can_approuve,$last_edit,$id,$page,$load=0,$allpage)
    {
        $this->page = $page;
        $this->id = $id;
        $main_append="";

        //$this->no_use_cache=true;
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min','jquery-ui-multiselect','jqueryFileTree','farbtastic'));
        $this->set_js(array('tinymce_var',"jquery.growl",'admin', 'jquery','jquery-ui.min','farbtastic','tinymce/tinymce.min','tinymce/jquery.tinymce.min','jquery-ui-multiselect','jquery.base64','validation/jquery.validate.min','jqueryFileTree', $this->req));
        global $lang,$conf;
        $tmpLang = substr($lang,0,2);
        if ($tmpLang != "en")
        {
            if (is_file(make_path('js', "validation/localization/messages_".$lang, "js")))
            {
                $this->set_js('validation/localization/messages_'.$lang.'.js');
            } elseif (is_file(make_path('js', "validation/localization/messages_".$tmpLang, "js")))
            {
                $this->set_js('validation/localization/messages_'.$tmpLang.'.js');
            } else {
                $tmpLang = substr($conf->default_lang,0,2);
                if (is_file(make_path('js', "validation/localization/messages_".$conf->default_lang, "js")))
                {
                    $this->set_js('validation/localization/messages_'.$conf->default_lang.'.js');
                } else {
                    $this->set_js('validation/localization/messages_'.$tmpLang.'.js');
                }
            }
        }
        $this->set_js_code($this->js_for_page($load));
        $this->set_tinymce_vars($section,$property['css']['value']);
        $this->set_js(array (
        'tinymce/themes/modern/theme.min',
        'tinymce/plugins/pagebreak/plugin.min',
        'tinymce/plugins/layer/plugin.min',
        'tinymce/plugins/table/plugin.min',
        'tinymce/plugins/save/plugin.min',
        'tinymce/plugins/emoticons/plugin.min',
        'tinymce/plugins/insertdatetime/plugin.min',
        'tinymce/plugins/preview/plugin.min',
        'tinymce/plugins/media/plugin.min',
        'tinymce/plugins/searchreplace/plugin.min',
        'tinymce/plugins/print/plugin.min',
        'tinymce/plugins/contextmenu/plugin.min',
        'tinymce/plugins/paste/plugin.min',
        'tinymce/plugins/directionality/plugin.min',
        'tinymce/plugins/fullscreen/plugin.min',
        'tinymce/plugins/hr/plugin.min',
        'tinymce/plugins/wordcount/plugin.min',
        'tinymce/plugins/noneditable/plugin.min',
        'tinymce/plugins/visualchars/plugin.min',
        'tinymce/plugins/nonbreaking/plugin.min',
        'tinymce/plugins/template/plugin.min',
        'tinymce/plugins/image/plugin.min',
        'tinymce/plugins/code/plugin.min',
        'tinymce/plugins/advlist/plugin.min',
        'tinymce/plugins/anchor/plugin.min',
        'tinymce/plugins/autolink/plugin.min',
        'tinymce/plugins/bbcode/plugin.min',
        'tinymce/plugins/colorpicker/plugin.min',
        'tinymce/plugins/contextmenu/plugin.min',
        'tinymce/plugins/directionality/plugin.min',
        'tinymce/plugins/emoticons/plugin.min',
        'tinymce/plugins/fullpage/plugin.min',
        'tinymce/plugins/fullscreen/plugin.min',
        'tinymce/plugins/link/plugin.min',
        'tinymce/plugins/lists/plugin.min',
        'tinymce/plugins/media/plugin.min',
        'tinymce/plugins/nonbreaking/plugin.min',
        'tinymce/plugins/noneditable/plugin.min',
        'tinymce/plugins/pagebreak/plugin.min',
        'tinymce/plugins/paste/plugin.min',
        'tinymce/plugins/searchreplace/plugin.min',
        'tinymce/plugins/tabfocus/plugin.min',
        'tinymce/plugins/table/plugin.min',
        'tinymce/plugins/template/plugin.min',
        'tinymce/plugins/textcolor/plugin.min',
        'tinymce/plugins/textpattern/plugin.min',
        'tinymce/plugins/visualblocks/plugin.min'
                ));

        global $lang,$conf;
        $tmpLang = substr($lang,0,2);
        if (is_file(make_path('js', "tinymce/langs/".$lang, "js")))
        {
            $this->set_js('tinymce/langs/'.$lang.'.js');
        } elseif (is_file(make_path('js', "tinymce/langs/".$tmpLang, "js")))
        {
            $this->set_js('tinymce/langs/'.$tmpLang.'.js');
        } else {
            $tmpLang = substr($conf->default_lang,0,2);
            if (is_file(make_path('js', "tinymce/langs/".$conf->default_lang, "js")))
            {
                $this->set_js('tinymce/langs/'.$conf->default_lang.'.js');
            } else {
                $this->set_js('tinymce/langs/'.$tmpLang.'.js');
            }
        }
        foreach($property as $key => $val)
        {
            if (is_array($val['formpart']))
            {
                $this->set_js_code($val['formpart']['js_code']);
                $this->set_js($val['formpart']['js']);
                $html = $val['formpart']['html'];
                $property[$key]['formpart']=$html;
            }
        }

        $this->set_title(_('Edition de page'));
        $this->set_extra_render('id',$id);
        $this->set_extra_render('section_list',$section);
        $this->set_extra_render("property",$property);
        $this->set_extra_render("page",$page->get_all());
        $this->set_extra_render('allpage',$allpage);
        $this->set_extra_render('group_publication',$group_publi['group_publication']);
        $this->set_extra_render('group_publication_refid',$group_publi['group_publication_refid']);
        $this->set_extra_render("last_edit", $last_edit);
        $available_lang_html = $this->get_available_editlang_page($this->pages_lang,$id);
        $this->set_extra_render('available_editlang_page',$available_lang_html['html']);
        load_alternative_class('class/template.class.php');
        $t = new template($this->db);
        $t->fetch($page->get_model_refid());
        $iter=3;
        $has_extra = false;
        if ($t->get_extra_params()."x" != "x")
        {
            load_alternative_class('class/template_extra.class.php');
            $mep = new template_extra($this->db,"edit_page?id=".$id."&action=save_extra_params");

            $a = json_decode($t->get_extra_params());
            $e = $mep->available_extra_params();
            foreach( $a as $key=>$val)
            {
                $a[$key] = $e[$val];
            }

            $this->set_extra_render('extra_params', $a);
            $html = "";
            $main_append = "";
            $extra_val_page = json_decode($page->get_extra_params());

            $has_extra = true;

            foreach (json_decode($t->get_extra_params()) as $key=>$val)
            {
                if ($val == 'image_section')
                {
                    $this->set_extra_render('image_section', true);
                    $this->set_browse_dialog();
                }

                $tmp = false;
                if (isset($extra_val_page->$val)) { $tmp = $extra_val_page->$val; } ;
                $tmp1 = false;

                $val_cache = $val."_use_cache";
                if (isset($extra_val_page->$val_cache)) { $tmp1['use_cache'] = $extra_val_page->$val_cache; } ;
                if (isset($extra_val_page->thb_prefix)) { $tmp1['thb_prefix'] = $extra_val_page->thb_prefix; } ;
                if (isset($extra_val_page->thb_path)) { $tmp1['thb_path'] = $extra_val_page->thb_path; } ;
                if (isset($extra_val_page->thumb)) { $tmp1['thumb'] = $extra_val_page->thumb; } ;
                if (isset($extra_val_page->thb_path_use_cache)) { $tmp1['thb_path_use_cache'] = $extra_val_page->thb_path_use_cache; } ;
                $part = $mep->$val($tmp,$iter,$tmp1);
                $html .= "<div id='model-tabs-".$val."'>".$part['html']."</div>";
                if ($part['js_code'])
                    $this->set_js_code($part['js_code']);
                $iter++;
            }
            $this->set_extra_render('extra_params_div', $html);
        }
        if ($t->get_plugins()."x" != "x")
        {
            $plugins = json_decode($t->get_plugins());
            $list_plugins = array();
            $html = "";
            foreach($plugins as $plugin_name)
            {
                require_once('plugins/template/'.$plugin_name."/".$plugin_name.".class.php");
                $plugin = new $plugin_name($this->db);
                $list_plugins[] = array(
                    "id"=> $plugin_name,
                    "name" => $plugin->get_full_name()
                );
                $all_plugins_data = json_decode($t->get_plugins());
                $plugins_values = json_decode($page->get_plugins());
                foreach (json_decode($t->get_plugins()) as $key=>$val)
                {
                    $values=false;
                    if (isset($plugins_values->$val))
                        $values = json_decode($plugins_values->$val);

                    $part = $plugin->make_form("edit_page?id=".$id."&action=save_plugin&plugin=".$plugin_name, $values ,$iter, false);
                    $html .= "<div id='plugin-tabs-".$val."'>".$part."</div>";
                    if ($plugin->get_js_code())
                    {
                        $this->set_js_code($plugin->get_js_code());
                    }
                    if ($plugin->get_css_code())
                        $this->set_css_code($plugin->get_css_code());
                    if ($plugin->get_js())
                        $this->set_js($plugin->get_js());
                    if ($plugin->get_css())
                        $this->set_css($plugin->get_css());
                    if ($plugin->get_js_external())
                        $this->set_js_external($plugin->get_js_external());

                    $iter++;
                }
            }
            $this->set_extra_render('plugins_div', $html);
            $this->set_extra_render('plugins_list', $list_plugins);
            $has_extra = true;
        }
        $this->set_extra_render('has_extra', $has_extra);
        if ($page->get_is_locked_for_edition() > 0)
        {
            $u = new user($this->db);
            $u->fetch($page->get_is_locked_for_edition() );
            $u->get_all();
            $this->set_extra_render('user_locked_page', $u);
            $this->set_extra_render('can_unlock', $this->can_unlock);
        }
        $this->set_extra_render('can_edit', $this->can_edit);

        $this->set_main($content);
        if ($this->page->get_is_a_draft_for() > 0)
        {
            $this->set_extra_render('is_a_draft', true);
        } else {
            $this->set_extra_render('is_a_draft', false);
        }
        if ($has_draft)
        {
            $this->set_extra_render('has_draft', $has_draft);
        }
        if ($can_approuve)
        {
            $this->set_extra_render('can_approuve',$can_approuve);
        }
        if ($this->page->get_form_refid()> 0)
        {
            $this->set_extra_render('has_form', true);
            $this->set_extra_render('form_tab_num', $iter);
            $this->set_extra_render('form_id', $this->page->get_form_refid());
            $this->set_extra_render('form', $this->page->get_form()->get_all());
            $this->set_extra_render('form_config', json_decode($this->page->get_form()->get_connector_option()));
            $requete = "SELECT id,
                               name,
                               firstname
                          FROM user
                         WHERE active = 1
                      ORDER BY name, firstname";
            $sql = $this->db->query($requete);
            $user_list = array();
            while($res = $this->db->fetch_object($sql))
            {
                $user_list[]=array(
                        "id" => $res->id,
                        "name" => $res->name,
                        "firstname" => $res->firstname
                );
            }
            $requete = "SELECT id,
                               title
                          FROM mail_model
                         WHERE active = 1
                      ORDER BY title";
            $sql = $this->db->query($requete);
            $mail_model_list = array();
            while($res = $this->db->fetch_object($sql))
            {
                $mail_model_list[]=array(
                        "id" => $res->id,
                        "title" => $res->title
                );
            }
            $this->set_extra_render('user_list', $user_list);
            $this->set_extra_render('mail_model', $mail_model_list);
            $iter++;
        } else {
            $this->set_extra_render('has_form', false);
        }

        if ($this->message)
        {
            $this->set_js_code('
                    jQuery(document).ready(function(){
                        jQuery.growl({
                            title: "'._("Résultat").'",
                            message: "'.$this->message.'",
                            location: "tr",
                            duration: 3200
                        });
                    });
                ');
        }
        echo $this->gen_page();
    }
    public function return_result($result)
    {
        echo json_encode(array('result' => $result));
        exit;
    }
    public function return_raw($result)
    {
        echo $result;
        exit;
    }
    private function set_browse_dialog()
    {
        global $conf;
        $this->set_js_code('var  base_path="'.$conf->main_base_path.'"; ');
        $html = "<div style='display:none' id ='dialog_file_browser'>";
        $html .= "<table width='400' CELLPADDING='5'><tr><td width='205'>";
        $html .= "  <div id='filetree' class='fileTree style_filetree'></div>";
        $html .= "</td><td align='center'>";
        $html .= "  <div id='dialog_display' style='background-position: center center; background-image: url(\"".$conf->main_base_path."/img/No_Image.png\"); background-repeat: no-repeat; background-size: contain; height: 180px;'></div>";
        $html .= "</td></tr></table>";
        $html .= " </div>";
        $this->set_extra_render('dialog', $html);
        return $html;
    }
}
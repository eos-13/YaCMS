<?php

class view_edit_section extends admin_common
{
    public $id;
    public $can_unlock = false;
    public $can_edit = false;
    public $message = false;


    private function set_tinymce_vars($section,$page)
    {
        $tmp = 'var all_tinymce=" ';
        $tmpA = array('tinymce');
        foreach($section as $key=>$val)
        {
            $tmpA[]= 'section_'.$val['id'].'';
        }
        $tmp .= join(',',$tmpA);
        $tmp .='";';
        global $conf;
        $tmp .= 'var base_root_path="'.$conf->main_base_path.'";';
        $this->set_js_code($tmp);

        load_alternative_class('class/page.class.php');
        $page_obj = new page($this->db);
        $page_obj->fetch($page['id']);

        $p = $page_obj->get_css();

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
        $this->set_js_code (  'default_tinymce = load_tiny_mce_var(all_tinymce,content_css_set,base_root_path,readonly);' );
    }

    public function run($section,$all_page,$current_page)
    {
        $section->get_all();
        $css_code=false;
        $this->set_css(array('admin',$this->req,'jquery-ui.min','jqueryFileTree','jquery.growl'));
        $this->set_js(array('admin', 'jquery','jquery-ui.min',"jquery.growl",'jqueryFileTree','tinymce_var','tinymce/tinymce.min','tinymce/jquery.tinymce.min',$this->req));
        if ($section->get_is_locked_for_edition() > 0)
        {
            $u = new user($this->db);
            $u->fetch($section->get_is_locked_for_edition() );
            $u->get_all();
            $this->set_extra_render('user_locked_section', $u);
            $this->can_edit = 0;
        }
        $this->set_tinymce_vars(array(),$current_page);
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
                'tinymce/plugins/code/plugin.min'));
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
        $this->set_title(_("Gestion des sections"));
        $this->set_extra_render('id' , $this->id);
        $this->set_extra_render('s' , $section);
        $this->set_extra_render('all_page' , $all_page);
        $this->set_extra_render('page' , $current_page);
        $this->set_extra_render('can_edit', $this->can_edit);

        load_alternative_class('class/page.class.php');
        $page = new page($this->db);
        $page->fetch($section->get_page_refid());
        load_alternative_class('class/template.class.php');
        $t = new template($this->db);
        $t->fetch($page->get_model_refid());
        if ($t->get_extra_params()."x" != "x")
        {
            load_alternative_class('class/template_extra.class.php');
            $mep = new template_extra($this->db,false);
            $this->set_extra_render('has_extra', true);
            $a = json_decode($t->get_extra_params());
            $e = $mep->available_extra_params();
            foreach (json_decode($t->get_extra_params()) as $key=>$val)
            {
                if ($val == 'image_section')
                {
                    $this->set_extra_render('image_section', true);
                    $this->set_browse_dialog();
                }
            }
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
    public function json($ret)
    {
        echo json_encode($ret);
        exit;
    }
    private function set_browse_dialog()
    {
        global $conf;
        $this->set_js_code('var  base_path="'.$conf->main_base_path.'"; ');
        $html = "<div style='display:none' id ='dialog_file_browser'>";
        $html .= "<table width=400 CELLPADDING=5><tr><td width=205>";
        $html .= "  <div id='filetree' class='fileTree style_filetree'></div>";
        $html .= "</td><td algin='center'>";
        $html .= "  <div id='dialog_display' style='background-position: center center; background-image: url(\"".$conf->main_base_path."/img/No_Image.png\"); background-repeat: no-repeat; background-size: contain; height: 180px;'></div>";
        $html .= "</td></tr></table>";
        $html .= " </div>";
        $this->set_extra_render('dialog', $html);
        return $html;
    }
}

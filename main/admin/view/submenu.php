<?php
class view_submenu extends admin_common
{
    public $message = false;
    public $submenu;

    private function set_tinymce_vars($p)
    {
        $tmp = 'var all_tinymce=" ';
        $tmp .= join(',',$p);
        $tmp .='";';

        global $conf;
        $tmp .= 'var base_root_path="'.$conf->main_base_path.'"';
        $this->set_js_code($tmp);
        $array=false;
        $array=json_decode($conf->site_css);
        $array = array_unique($array);
        foreach($array as $key=>$val)
        {
            $array[$key] = make_path('css', $val, 'css',false,true);
        }
        //$array[]=
        $this->set_js_code ( 'var content_css_set="' . join ( ',', $array ) . '";' );
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
    public function run()
    {
        //$this->no_use_cache=true;
        $this->set_title(_("Sous-Menu"));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min','jquery-ui-multiselect','jqueryFileTree','farbtastic'));
        $this->set_js(array('tinymce_var',"jquery.growl",'admin', 'jquery','jquery-ui.min','tinymce/tinymce.min','tinymce/jquery.tinymce.min','jquery-ui-multiselect','jqueryFileTree','farbtastic',$this->req));
        $p = array();
        foreach($this->submenu as $key=>$val)
        {
            $p[] = "content-".$val['id'];
        }
        $this->set_tinymce_vars($p);
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
        //$this->set_main($content);
        $this->set_extra_render("submenu_build",$this->submenu);
        $langbar = $this->get_available_lang();
        $this->set_browse_dialog();

        $this->set_extra_render("available_lang", $langbar['html']);

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
}
<?php

class view_result_forms extends admin_common
{
    public $message = false;
    public $display_jgGrid = false;
    public $forms_list;
    public $display_form = false;
    public $id;
    public $model_return;
    public $action;
    public $stats;

    public function run()
    {
        $this->set_title(_("Résultats des formulaires"));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min'));
        $this->set_js(array('admin',"jquery.growl", 'jquery', 'jquery-ui.min',$this->req));

        $main = false;
        if ($this->display_jgGrid)
        {
            $this->set_css('jqGrid/ui.jqgrid');
            $this->set_js('jquery.fmatter');
            global $lang,$conf;
            $tmpLang = substr($lang,0,2);
            if (is_file(make_path('js', "jqGrid/js/i18n/grid.locale-".$lang, "js")))
            {
                $this->set_js('jqGrid/js/i18n/grid.locale-'.$lang.'.js');
            } elseif (is_file(make_path('js', "jqGrid/js/i18n/grid.locale-".$tmpLang, "js")))
            {
                $this->set_js('jqGrid/js/i18n/grid.locale-'.$tmpLang.'.js');
            } else {
                $tmpLang = substr($conf->default_lang,0,2);
                if (is_file(make_path('js', "jqGrid/js/i18n/grid.locale-".$conf->default_lang, "js")))
                {
                    $this->set_js('jqGrid/js/i18n/grid.locale-'.$conf->default_lang.'.js');
                } else {
                    $this->set_js('jqGrid/js/i18n/grid.locale-en.js');
                }
            }

            $this->set_js(array('jqGrid/js/jquery.jqGrid.min','jqGrid/plugins/jquery.contextmenu'));
            $this->set_js_code($this->model_return);
            $main = "<section><table id='jqGrid'></table><div id='jqGridPager'></div></section>";
            $this->set_extra_render('show_del_button', true);
        } elseif($this->stats) {
            $main = _("Statistiques:");
            $this->set_extra_render("stats", $this->stats);
            $this->set_extra_render('show_del_button', true);
        }
        $this->set_extra_render("forms_list", $this->forms_list);
        $this->set_extra_render("id", $this->id);
        $this->set_extra_render("action", $this->action);

        $this->set_main($main);
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
    public function json($result)
    {
        header('application/json');
        echo $result;
        exit;
    }
    public function html($result)
    {
        header('text/html');
        echo $result;
        exit;
    }
}

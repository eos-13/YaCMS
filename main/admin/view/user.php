<?php

class view_user extends admin_common
{
    public $message = false;

    public function run()
    {
        $this->set_title(_('User Management'));
        $this->set_css(array('admin',$this->req,'jquery-ui.min','jqGrid/ui.jqgrid',"jquery.growl"));
        $this->set_js(array('admin', 'jquery','jquery-ui.min',"jquery.growl",$this->req));
        $this->set_main("<table id='jqGrid'></table><div id='jqGridPager'></div>");
        $this->set_css_code('#FrmGrid_jqGrid textarea { min-height: 150px; min-width: 400px;}' );
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
}

<?php

class view_group extends admin_common
{
    public $datas;
    public $message = false;

    public function run()
    {
        $this->set_title(_('Group Management'));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min','jqGrid/ui.jqgrid','jquery-ui-multiselect'));
        $this->set_js(array('admin',"jquery.growl", 'jquery','jquery-ui.min','jquery-ui-multiselect',$this->req));
        $this->set_main("<table id='jqGrid'></table><div id='jqGridPager'></div>");

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

        $this->set_extra_render('users', $this->datas['users']);
        $this->set_extra_render('groups', $this->datas['groups']);
        $this->set_extra_render('group_member', $this->datas['group_members']);
        $this->set_css_code('#FrmGrid_jqGrid textarea { min-height: 150px; min-width: 400px;}' );
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

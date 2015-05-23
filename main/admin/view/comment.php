<?php

class view_comment extends admin_common
{
    public $message = false;

    public function run()
    {
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min','jqGrid/ui.jqgrid'));
        $this->set_js(array('admin',"jquery.growl",'jquery','jquery-ui.min',$this->req));

        $this->set_title(_('Commentaires'));
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

        $this->set_main("<table id='jqGrid'></table><div id='jqGridPager'></div>");
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

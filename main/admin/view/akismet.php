<?php

class view_akismet extends admin_common
{
    public $message = false;

    public $check_key=false;

    public function run()
    {
        $this->set_title(_("Aksimet"));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min','jqGrid/ui.jqgrid'));
        $this->set_js(array('admin',"jquery.growl", 'jquery','jquery.fmatter', 'jquery-ui.min',$this->req));

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

        $key_valid = _("La clef akismet est : ");
        $key_valid .= ($this->check_key? _("Valide"):_("Invalide"));
        if ($this->check_key)
        {
            $main .= "<section><table id='jqGrid'></table><div id='jqGridPager'></div></section>";
        }
        $this->set_extra_render('key_valid', $key_valid);
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

<?php

class view_files extends admin_common
{
    public $message = false;

    private function js_for_page()
    {
        global $conf;
        $tmp = 'var connector_url="'.$conf->main_base_path.'/lib/elfinder/php/connector.php";';
        $tmp .= <<<EOF
            jQuery(document).ready(function() {
            jQuery('#finder').elfinder({
                requestType : 'post',
                url : connector_url,
                width: "auto",
                height: "auto",
                handlers : {
                    select : function(event, elfinderInstance)
                    {
                        var selected = event.data.selected;
                        if (selected.length) {
                            // console.log(elfinderInstance.file(selected[0]))
                        }

                    }
                },
                lang : 'fr',
                rememberLastDir : true,
                ui : ['tree', 'toolbar','path','stat'],
            });
        });

EOF;
        return $tmp;
    }
    public function run()
    {
        $this->set_css_code('.button {
            width: 100px;
            position:relative;
            display: -moz-inline-stack;
            display: inline-block;
            vertical-align: top;
            zoom: 1;
            *display: inline;
            margin:0 3px 3px 0;
            padding:1px 0;
            text-align:center;
            border:1px solid #ccc;
            background-color:#eee;
            margin:1em .5em;
            padding:.3em .7em;
            border-radius:5px;
            -moz-border-radius:5px;
            -webkit-border-radius:5px;
            cursor:pointer;
        }');
        $this->set_title("Gestion des fichiers");
        $this->set_template_file(make_path('template',$this->req,'html'));
        $this->set_css(array("admin","jquery.growl",
                             $this->req,
                             "jquery-ui.structure.min",
                             "jquery-ui.theme.min",
                             "jquery-ui.min",
                             "elfinder/common",
                             "elfinder/dialog",
                             "elfinder/toolbar",
                             "elfinder/navbar",
                             "elfinder/statusbar",
                             "elfinder/contextmenu",
                             "elfinder/cwd",
                             "elfinder/quicklook",
                             "elfinder/commands",
                             "elfinder/fonts",
                             "elfinder/theme"
                             )
                       );
        $this->set_js(array
                (
                'admin',"jquery.growl",
                $this->req,
                'jquery',
                'jquery-ui.min',
                "elfinder/jquery.elfinder",
                "elfinder/elFinder",
                "elfinder/elFinder.version",
                "elfinder/elFinder.resources",
                "elfinder/elFinder.options",
                "elfinder/elFinder.history",
                "elfinder/elFinder.command",
                "elfinder/ui/overlay",
                "elfinder/ui/workzone",
                "elfinder/ui/navbar",
                "elfinder/ui/dialog",
                "elfinder/ui/tree",
                "elfinder/ui/cwd",
                "elfinder/ui/toolbar",
                "elfinder/ui/button",
                "elfinder/ui/uploadButton",
                "elfinder/ui/viewbutton",
                "elfinder/ui/searchbutton",
                "elfinder/ui/sortbutton",
                "elfinder/ui/panel",
                "elfinder/ui/contextmenu",
                "elfinder/ui/path",
                "elfinder/ui/stat",
                "elfinder/ui/places",
                "elfinder/commands/back",
                "elfinder/commands/forward",
                "elfinder/commands/reload",
                "elfinder/commands/up",
                "elfinder/commands/home",
                "elfinder/commands/copy",
                "elfinder/commands/cut",
                "elfinder/commands/paste",
                "elfinder/commands/open",
                "elfinder/commands/rm",
                "elfinder/commands/info",
                "elfinder/commands/duplicate",
                "elfinder/commands/rename",
                "elfinder/commands/help",
                "elfinder/commands/getfile",
                "elfinder/commands/mkdir",
                "elfinder/commands/mkfile",
                "elfinder/commands/upload",
                "elfinder/commands/download",
                "elfinder/commands/edit",
                "elfinder/commands/quicklook",
                "elfinder/commands/quicklook.plugins",
                "elfinder/commands/extract",
                "elfinder/commands/archive",
                "elfinder/commands/search",
                "elfinder/commands/view",
                "elfinder/commands/resize",
                "elfinder/commands/sort",
                "elfinder/commands/netmount",
                "elfinder/jquery.dialogelfinder",
                "elfinder/proxy/elFinderSupportVer1"
                 )
        );
        global $lang,$conf;
        $tmpLang = substr($lang,0,2);
        if (is_file(make_path('js', "elfinder/i18n/elfinder.".$tmpLang, "js")))
        {
            $this->set_js("elfinder/i18n/elfinder.".$tmpLang);
        } else {
            $tmpLang = substr($conf->default_lang,0,2);
            if (is_file(make_path('js', "elfinder/i18n/elfinder.".$tmpLang, "js")))
            {
                $this->set_js("elfinder/i18n/elfinder.".$tmpLang);
            } else {
                $this->set_js("elfinder/i18n/elfinder.en");
            }
        }
        $this->set_js_code($this->js_for_page());

        $this->set_main("<div id='result'></div>");
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
}
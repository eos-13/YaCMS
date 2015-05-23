<?php

class view_plugins extends admin_common
{
    public $message = false;

    public function run()
    {
        $this->set_title(_("Plugins"));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min'));
        $this->set_js(array('admin',"jquery.growl", 'jquery', 'jquery-ui.min',$this->req));

        $this->set_js_code('jQuery(document).ready(function(){ jQuery("#tabs").tabs();})');

        $main = "";
        global $conf;
        if (!isset($_REQUEST['id']))
        {
            $main = "<ul style='padding:10px; list-style: none;'>";
            $plugins = array();
            $dir = new DirectoryIterator($conf->main_document_root."/plugins/template");
            $main .= "<h1>"._("All installed plugins")."</h1>";
            foreach ($dir as $fileinfo)
            {
                if (!$fileinfo->isDot())
                {
                    if (is_dir($conf->main_document_root."/plugins/template/".$fileinfo->getFilename()))
                    {
                        bindtextdomain("plugins", 'plugins/template/'.$fileinfo->getFilename().'/lang');
                        $shortDesc=false;
                        $version=false;
                        $main.="<li style='line-height: 30px; height: 30px; padding:10px; border: 1px Solid #dfdfdf;'>";
                        if (is_file($conf->main_document_root."/plugins/template/".$fileinfo->getFilename()."/info/info.php"))
                        {
                            include_once $conf->main_document_root."/plugins/template/".$fileinfo->getFilename()."/info/info.php";
                            $main .= "<div style='float: left; width: 220px; max-width:220px; white-space: nowrap;'><a href='plugins?id=".basename($fileinfo->getFilename())."'>".$name."</a></div>";
                            $main .= "<div style='float: left; padding-left: 10px;'>".$shortDesc."</div>";
                            $main .= "<div style='float: left; padding-left: 10px;'>".$version."</div>";
                        } else {
                            $main .= "<div style='float: left; width: 220px; max-width:220px; white-space: nowrap;'><a href='plugins?id=".basename($fileinfo->getFilename())."'>".$fileinfo->getFilename()."</a></div>";
                        }
                        $main .= "</li>";
                    }
                }
            }
            $main .= "</ul>";
        } else {
            $main .= "<div id='tabs'>";
            $main .= "<ul><li><a href='#info'>Info</a></li>";
            $main .= "    <li><a href='#model'>Template</a/<li></ul>";
            $main .= "<div id='info'>";
            $info = _("No info provided");
            if (is_file($conf->main_document_root.'/plugins/template/'.$_REQUEST['id']."/info/info.php"))
            {
                include_once($conf->main_document_root.'/plugins/template/'.$_REQUEST['id']."/info/info.php");
            }
            $main .= "<div id='info'>". $info."</div>";
            $main .= "</div>";
            $model = _("No sample model");
            if (is_file($conf->main_document_root.'/plugins/template/'.$_REQUEST['id']."/info/model.php"))
            {
                include_once($conf->main_document_root.'/plugins/template/'.$_REQUEST['id']."/info/model.php");
            }
            $main .= "<div id='model'><H1>"._("Sample Model")."</H1><code>";
            $this->set_extra_render("model", $model);
            $main2 = "</code></div>";
            $main2 .= '</div>';
            $this->set_extra_render("main2", $main2);

        }

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
<?php

class view_map extends admin_common
{

    public $message = false;
    public $root;

    public function json($json)
    {
        header('Content-Type: application/json');
        echo $json;
    }
    public function run($noparentArray,$allPageArray)
    {
        $this->set_title(_("Map"));
        $this->set_css(array("admin",$this->req,"jquery-ui.structure.min","jquery-ui.theme.min","jquery-ui.min","jquery.growl"));
        $this->set_js(array('admin',$this->req,'d3.min','jquery','jquery-ui.min',"jquery.growl"));
        $this->set_js_code("jQuery(document).ready(function(){load_map_js('map?action=data'); });");
        $this->set_main("<div id='result'></div>");
        $this->set_extra_render('leftmenu',$this->left_menu());
        $this->set_extra_render('noparent' , $noparentArray);
        $this->set_extra_render('allPage' , $allPageArray);
        $langbar = $this->get_available_editlang();
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


}

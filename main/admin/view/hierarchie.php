<?php

class view_hierarchie extends admin_common
{
    public $message = false;
    public $all_page;
    public $all_page_meta;
    public $root = 1;

    public function run()
    {
        $this->set_title(_("Hierarchie"));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery-ui.min'));
        $this->set_js(array('admin',"jquery.growl", 'jquery','jquery-ui.min','jquery.ui.touch-punch-0.2.2.min'));
        $this->set_js(array(
                "jsPlumb/jsBezier-0.6",
                "jsPlumb/mottle-0.5",
                "jsPlumb/biltong-0.2",
                "jsPlumb/util",
                "jsPlumb/browser-util",
                "jsPlumb/dom-adapter",
                "jsPlumb/jsPlumb",
                "jsPlumb/overlay-component",
                "jsPlumb/endpoint",
                "jsPlumb/connection",
                "jsPlumb/anchors",
                "jsPlumb/defaults",
                "jsPlumb/connectors-bezier",
                "jsPlumb/connectors-statemachine",
                "jsPlumb/connectors-flowchart",
                "jsPlumb/connector-editors",
                "jsPlumb/renderers-svg",
                "jsPlumb/renderers-vml",
                "jsPlumb/base-library-adapter",
                "jsPlumb/jquery.jsPlumb",
                $this->req
        ));
        $iter_empty = 1;
        $iter_level = array();
        $arrElemTop = array();
        foreach($this->all_page as $key=>$val)
        {
            if ($val['id'] == 1) continue;
            if (isset($val["parent_page_refid"]) && $val["parent_page_refid"] >0)
            {
                $this->set_css_code('#flowchartWindow'.$val['id']." { top: ".$val['top']."px; left:".$val['left']."px} ");
            } else {
                $this->set_css_code('#flowchartWindow'.$val['id']." { right: 1em; top:".($iter_empty)."em} ");
                $this->set_js_code('jQuery(document).ready(function(){ jQuery("#flowchartWindow'.$val['id'].'").css("left",jQuery("#flowchartWindow'.$val['id'].'").css("left"));  }) ');
                $iter_empty+=4;
            }
        }
        $js = "";
        foreach($this->all_page as $key=>$val)
        {
            if ($val['id'] != $this->root)
                $js .= '_addEndpoints("Window'.$val['id'].'", [ "BottomCenter", "RightMiddle" ], ["LeftMiddle"]);';
            if (isset($val['parent_page_refid']) && $val['parent_page_refid'] > 0)
            {
                $endpoint = $val["endpoint_pos"];
                $js .= 'instance.connect({uuids: ["Window'.$val['parent_page_refid'].$endpoint.'", "Window'.$val['id'].'LeftMiddle"], editable: true});'."\n";
            }
        }
        $langbar = $this->get_available_editlang();
        $this->set_extra_render("available_lang", $langbar['html']);

        $this->set_js_code( "jQuery(document).ready(function(){". $js."});");
        $main = "";
        foreach($this->all_page as $key=>$val)
        {
            if ($val['id'] == 1) continue;
            $main .= '<div class="window flowchart" id="flowchartWindow'.$val['id'].'" data-test="'.$val['id'].'">'.$val['title'].'</div>';
        }
        $this->set_main($main);
        $this->set_extra_render("rootId", $this->root);
        if ($this->message)
        {
            $this->set_js_code('
                    jQuery(document).ready(function()
                    {
                        jQuery.growl(
                        {
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
    public function send_json($result)
    {
        header('application/json');
        echo json_encode($result);
        exit;
    }
    public function send_html($result)
    {
        header('text/html');
        echo $result;
        exit;
    }
}
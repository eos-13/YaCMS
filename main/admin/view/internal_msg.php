<?php

class view_internal_msg extends admin_common
{
    public $message;

    private function make_editable_jqgrid_list($jsVarName='list',$table='status',$orderby='ordre',$key='id')
    {
        $requete =" SELECT *
                      FROM ".$table;
        if ($orderby) $requete .= " ORDER BY ".$orderby;
        $sql = $this->db->query($requete);
        $arr=array();
        while($res=$this->db->fetch_object($sql)){
            //editoptions: { value: “FE:FedEx; IN:InTime; TN:TNT” }
            $arr[]=$res->$key.":".$res->name;
        }
        return ('var '.$jsVarName.'="'. join(';',$arr) .'";');

    }
    public function run()
    {
        $this->set_title(_('Messages internes'));
        $this->set_css(array('admin',$this->req,'jquery-ui.min','jqGrid/ui.jqgrid',"jquery.growl"));
        $this->set_js(array('admin', 'jquery','jquery-ui.min','tinymce/tinymce.min','tinymce/jquery.tinymce.min',"jquery.growl",$this->req));
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
        $this->set_js(array("tinymce/themes/modern/theme.min",'tinymce/plugins/emoticons/plugin.min','tinymce/plugins/hr/plugin.min','tinymce/plugins/visualchars/plugin.min','tinymce/plugins/nonbreaking/plugin.min'));
        $this->set_main("<table id='jqGrid'></table><div id='jqGridPager'></div>");
        $this->set_css_code('#FrmGrid_jqGrid textarea { min-height: 150px; min-width: 400px;}' );
        $this->set_js_code(
                $this->make_editable_jqgrid_list('listAllStatus','internal_msg_status','name','name')
        );
        $this->set_js_code(
                $this->make_editable_jqgrid_list('listAllType','internal_msg_type','name','name')
        );
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
        global $conf;
        $this->set_js_code( 'var base_path="'.$conf->main_base_path.'/main/";' );
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
    public function send_json($result)
    {
        header('application/json');
        echo $result;
        exit;
    }
    public function send_html($result)
    {
        echo $result;
        exit;
    }
}

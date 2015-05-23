<?php
class view_forms extends admin_common
{
    public $list;
    public $id;
    public $message = false;
    public $form;
    public $all_page;

    public function run()
    {
        //$this->no_use_cache=true;
        $this->set_title(_('Form Builder'));
        $this->set_css(array('admin',"jquery.growl",$this->req,'jquery','jquery-ui.min'));
        $this->set_js(array('admin',"jquery.growl", 'jquery','jquery-ui.min',"jquery.base64",$this->req));


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
        global $editlang;
        $jsform = make_path("js","Bootstrap-Form-Builder-gh-pages/assets/js/main_".$editlang,"js");
        if (!$jsform)
            $this->set_main("<h2>"._("Form builder isn't available in your current language")."</h2>");
        if ($jsform)
        {
            $this->set_extra_render('js_form', $jsform);
            $this->set_extra_render('requirejs', make_path('js',"Bootstrap-Form-Builder-gh-pages/assets/js/lib/require","js"));
            $this->set_extra_render('all_page', $this->all_page);
            $this->set_extra_render('id', $this->id);
        }
        $langbar = $this->get_available_lang();
        $this->set_extra_render("available_lang", $langbar['html']);
        $this->set_extra_render("is_publish", ($this->form['is_publish']==1?true:false));
        $this->set_extra_render('list', $this->list);
        if (isset($this->form) && isset($this->form['jsonData']))
            $this->set_extra_render('formJSON', $this->form['jsonData']);

        if ($this->form['page_refid'] > 0)
        {
            load_alternative_class("class/page.class.php");
            $p = new page($this->db);
            $p->fetch($this->form['page_refid']);
            if ($p->get_id()>0)
            {
                global $conf;
                $this->set_extra_render('page_publi_link',"<a href='".preg_replace('/\/$/','',$conf->main_base_path)."/".$p->get_url()."'>".$p->get_url()."</a>");
                $this->set_extra_render('page_publi_edit_link',"<a href='".preg_replace('/\/$/','',$conf->main_base_path)."/".$conf->admin_keyword."/edit_page?id=".$p->get_id()."'>"._("Editer la page")."</a>");
            }
        }


        echo $this->gen_page();
    }
    public function json($result)
    {
        header('application/json');
        echo json_encode($result);
        exit;
    }
}
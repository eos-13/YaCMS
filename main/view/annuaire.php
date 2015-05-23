<?php

class view_annuaire extends common
{
    public $id;
    public $list_user;
    public function run()
    {
        global $conf;
        $this->set_main(_("Annuaire"));
        $this->set_title(_("Annuaire"));

        $this->set_js(array('jquery','jquery-ui.min',$this->req));
        $this->set_css(array('jquery-ui.structure','jquery-ui.min','jquery-ui.theme.min',$this->req));

        $this->set_extra_render('list_user', $this->list_user);
        echo $this->gen_page();
    }
}


?>
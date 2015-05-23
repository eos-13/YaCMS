<?php

class view_lr extends common
{

    public function run()
    {
        global $conf;
        $this->set_title(_('Résultat de la recherche'));
        $this->set_main(_("Search result:")."<br/>");
        $this->set_js("jquery.growl");
        $this->set_extra_render('lr', $this->lr);
        echo $this->gen_page();
    }
}

?>

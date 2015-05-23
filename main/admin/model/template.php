<?php
class model_template extends admin_common
{
    public $id;
    public $bdd_data;
    public $hd_data;
    public $message;

    public function run()
    {
        load_alternative_class('class/template.class.php');
        $obj = new template($this->db);
        if ($this->id)
        {
            $obj->fetch($this->id);
        }
        $obj->bdd_data = $this->get_bdd_data();
        $obj->hd_data_main = $this->get_hd_data("main");
        $obj->hd_data_custom = $this->get_hd_data("customer");
        return $obj;
    }
    private function get_hd_data($where)
    {
        //List all template in main and customer
        if (!is_dir($where."/"."template/")) return array();
        $dir = new DirectoryIterator($where."/"."template/");
        foreach ($dir as $fileinfo)
        {
            if (!$fileinfo->isDot())
            {
                $arr[]=array('id'=>$fileinfo->getFilename(),
                             'name'=>$fileinfo->getFilename(),
                            );
            }
        }
        return $arr;
    }
    private function get_bdd_data()
    {
        $requete = "SELECT id
                      FROM model
                  ORDER BY name";
        $sql = $this->db->query($requete);
        $arr = false;
        while ($res = $this->db->fetch_object($sql))
        {
            $m = new template($this->db);
            $m->fetch($res->id);
            $m->get_all();
            $arr[]=$m;
        }
        return $arr;
    }
    public function get_template_file_content($type,$path)
    {
        if ($type == 'disk_main'){ $type = 'main/'; }
        else $type = 'customer';
        $path = $type."/template/".$path ;
        $res = parent::get_file_content($path);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }
    public function set_file_content($path, $content)
    {
        $res =  file_put_contents($path, $content);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }
    public function get_bdd_content()
    {
        load_alternative_class('class/template.class.php');
        $t = new template($this->db);
        $t->fetch($this->id);
        return array('content' => $t->get_content(),
                     "path" => $t->get_path(),
                     "name" => $t->get_name(),
                     "extra_params" => json_decode($t->get_extra_params()),
                     "plugins" => json_decode($t->get_plugins())
         );
    }
    public function add()
    {
        load_alternative_class('class/template.class.php');
        $t = new template($this->db);
        $this->id = $t->create();
        if ($this->id) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return array('id'=>$this->id);
    }
    public function clone_template($post)
    {
        load_alternative_class('class/template.class.php');
        if ($post['type'] == "bdd")
        {
            $t = new template($this->db);
            $t->fetch($this->id);
            $c = new template($this->db);
            $cloneid = $t->create();
            $c->fetch($cloneid);
            $res = $c->set_content($t->get_content());
            $res1 = $c->set_path($t->get_path());
            $res2 = $c->set_name("Clone ".$t->get_name());
            $res3 = $c->set_extra_params($t->get_extra_params());
            $res4 = $c->set_plugins($t->get_plugins());
            if ($cloneid && $res && $res1 && $res2 && $res3 && $res4) $this->message = _("Opération effectuée");
            else $this->message = _("Opération echouée");
            return array('id'=>$cloneid);
        } else {
            $c = new template($this->db);
            $cloneid = $c->create();
            $c->fetch($cloneid);
            $res1 = $c->set_name("Clone ".$this->id);
            $res2 = $c->set_content( $this->get_template_file_content($post['type'],$this->id) );
            if ($res1 && $res2) $this->message = _("Opération effectuée");
            else $this->message = _("Opération echouée");
            return array('id'=>$cloneid);
        }
    }

    public function save_bdd($post)
    {
        load_alternative_class('class/template.class.php');
        $t = new template($this->db);
        $t->fetch($this->id);
        $res = $t->set_content(html_entity_decode($post['content']));
        $res1 = $t->set_name($post['name']);
        $res2 = $t->set_path($post['file']);
        if (isset($post['extra_params']))
        {
            $res3 = $t->set_extra_params(json_encode($post['extra_params']));
        } else {
            $res3 = true;
        }
        $res4 = $t->set_plugins(json_encode($post['plugins']));
        if ($res && $res1 && $res2 && $res3 && $res4) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return true;
    }
    public function del()
    {
        load_alternative_class('class/template.class.php');
        $t = new template($this->db);
        $t->fetch($this->id);
        $res = $t->del();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return true;
    }
    public function del_file()
    {
        global $conf;
        $file = "customer/template/".$this->id;
        $res = unlink($conf->main_document_root."/".$file);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function validate($post)
    {
        $requete = "SELECT *
                      FROM `model`
                     WHERE `name` = '".addslashes($post['name'])."'
                       AND id <> ".$post['id'];
        $sql = $this->db->query($requete);
        if (!$sql) {
            return ('DB Error') ;
        }
        if ($this->db->num_rows($sql) > 0 ){
            return ('Ce modèle existe déjà');
        } else {
            return (true);
        }
    }
}
?>
<?php
class model_message_publication extends admin_common
{
    public $id;
    public $message = false;

    public function run()
    {
        if ($this->id && $this->id > 0)
        {
            load_alternative_class('class/message_publication.class.php');
            $h = new message_publication($this->db);
            $h->set_id($this->id);
            $res = $h->fetch();
            $array = array();


            $array['content']=$h->get_content();
            $array['property']=$this->get_all_properties();
            $array['allgroup_publi'] = $this->get_all_group_publi();
            return $array;
        } else {

            $array['allgroup_publi'] = $this->get_all_group_publi();
            return $array;
        }
    }
    private function get_all_group_publi()
    {
        $requete = "SELECT id,name
                      FROM group_publication
                  ORDER BY name";
        $sql = $this->db->query($requete);
        $a=array();
        while($res = $this->db->fetch_object($sql))
        {
            $a[]=array('name'=>$res->name, 'id'=>$res->id);
        }
        return $a;
    }
    private function get_all_properties()
    {
        load_alternative_class('class/message_publication.class.php');
        $h = new message_publication($this->db);
        $h->set_id($this->id);
        $h->fetch();
        $arr = $h->list_properties();
        foreach($arr as $key=>$val)
        {
            $value = $h->get_property($val['field']);
            $arr[$key]['value']=$value;
            $ret=$this->make_formpart($val,$value);
            if (is_array($ret))
            {
                $arr[$key]['formpart']=array('html' => $ret['html'],
                                             'js' => $ret['js'],
                                             'js_code' => $ret['js_code']);
            } else {
                $arr[$key]['formpart']=$ret;
            }

        }
        return $arr;
    }


    public function edit($post)
    {
        $this->create_if_needed();
        load_alternative_class('class/message_publication.class.php');
        $h = new message_publication($this->db);
        $h->set_id($this->id);
        $res = $h->set_content($post['content']);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function edit_properties($post)
    {
        $this->create_if_needed();
        load_alternative_class("class/message_publication.class.php");
        $h = new message_publication($this->db);
        $h->set_id($this->id);
        $res = $h->parse_POST_and_update($post);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    private function create_if_needed()
    {
        if ($this->id > 0)
        {
            load_alternative_class('class/message_publication.class.php');
            $h = new message_publication($this->db);
            $h->set_id($this->id);
            $h->create();
        }
    }

}
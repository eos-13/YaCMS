<?php
class model_view_revision extends admin_common
{
    public $id;
    public $can_unlock;
    public $can_edit;
    private $page_access;
    public $message = false;

    public function run()
    {
        load_alternative_class('class/revision.class.php');
        $p = new revision($this->db);
        $res = $p->fetch($this->id);
        $this->page_access = $p;
        $this->can_edit = $this->can_edit($p);

        if (!$res) return false;
        $array = array();
        $array['content']['content']=$p->get_content();
        $array['content']['title']=$p->get_title();
        $array['section']=$p->get_all_section();
        $array['property']=$this->get_all_properties();
        $array['last_edit']= $this->last_edit_data();
        return $array;
    }
    private function last_edit_data()
    {
        $by = $this->page_access->get_last_modif_by_user_obj()->get_name()." ".$this->page_access->get_last_modif_by_user_obj()->get_firstname();
        return array('by'=>$by, 'date'=>convert_time_us_to_fr($this->page_access->get_date_derniere_maj()));
    }
    private function get_all_properties()
    {
        load_alternative_class('class/revision.class.php');
        $p = new revision($this->db);
        $p->fetch($this->id);
        $arr = $p->list_properties();
        foreach($arr as $key=>$val)
        {
            $value = $p->get_property($val['field']);
            $arr[$key]['value']=$value;
            $ret=$this->make_formpart($val,$value,$this->can_edit);
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

    public function can_edit($p)
    {
        return false;
    }
    public function replace($post)
    {
        load_alternative_class('class/revision.class.php');
        $rev = new revision($this->db);
        $rev->fetch($post['id']);
        $res = $rev->make_a_page();
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;
    }
}
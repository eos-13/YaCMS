<?php
class model_data_file extends admin_common
{
    public $message;
    public function run()
    {
        return ;
    }
    public function get_info($post)
    {
        load_alternative_class('class/data_file.class.php');
        $df = new data_file($this->db);
        $is_image = false;
        if (preg_match("/jpg/i", $post['file'])) $is_image=true;
        if (preg_match("/gif/i", $post['file'])) $is_image=true;
        if (preg_match("/png/i", $post['file'])) $is_image=true;
        if (preg_match("/jpeg/i", $post['file'])) $is_image=true;

        $md = $df->fetch_by_file_path($post['file']);
        $ret = array();
        $ret['is_image']=$is_image;
        $ret['data'] = $md;
        return $ret;
    }
    public function add_key($post)
    {
        load_alternative_class('class/data_file.class.php');
        $df = new data_file($this->db);
        $ret = $df->add($post['path'], $post['key'], $post['val']);
        if($ret == -1) $this->message= "Cette clef existe déjà";
        elseif ($ret) $this->message = "OK";
        else $this->message = "KO";
    }
    public function edit($post)
    {
        load_alternative_class('class/data_file.class.php');
        $df = new data_file($this->db);
        $df->fetch($post["id"]);
        $ret1 = $df->set_data_name($post['data_name']);
        $ret = $df->set_data_value($post['data_value']);
        if ($ret && $ret1) $this->message = "OK";
        else $this->message = "KO";
    }
    public function del($post)
    {
        load_alternative_class('class/data_file.class.php');
        $df = new data_file($this->db);
        $df->fetch($post["id"]);
        $ret = $df->del();
        if ($ret) $this->message = "OK";
        else $this->message = "KO";
    }
}
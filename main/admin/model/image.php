<?php
class model_image extends admin_common
{
    public $message = false;

    public function run()
    {
        return (true);
    }
    public function redim_path($post)
    {
        //List les fichers
        global $conf;
        $directory = $conf->main_document_root."/files/".$post['rep'];
        load_alternative_class('class/image_function.class.php');
        $if = new image_function();
        $tot = 0;
        $tot_ok = 0;
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file)
        {
            if (!preg_match('/^\./',$file->getFilename()) && preg_match('/(png|jpeg|jpg)$/i',$file->getFilename()))
            {
                $ret = $if->fctredimimage($post['larg'], $post['haut'], $directory, $file->getFilename(), $directory, $file->getFilename());
                if ($ret) $tot_ok ++;
                $tot ++;
            }
        }
        $this->message= $tot_ok. " images redimmensionnées sur ".$tot;

    }
    public function make_thumb($post)
    {
        //List les fichers
        global $conf;
        $directory = $conf->main_document_root."/files/".(isset($post['rep'])?$post['rep']:"");
        $dest_directory = $conf->main_document_root."/files/".(isset($post['rep_dest'])?$post['rep_dest']:"")."/thumb/";
        if (!is_dir($dest_directory)) mkdir ($dest_directory, 0700,true);
        load_alternative_class('class/image_function.class.php');
        $if = new image_function();
        $tot = 0;
        $tot_ok = 0;
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file)
        {
            if (!preg_match('/^thumb/',$file->getFilename()) && !preg_match('/^\./',$file->getFilename()) && preg_match('/(png|jpeg|jpg)$/i',$file->getFilename()))
            {
                $ret = $if->fctredimimage($post['larg'], $post['haut'], $dest_directory, $post['prefix']. $file->getFilename(), $directory, $file->getFilename());
                if ($ret) $tot_ok ++;
                $tot ++;
            }
        }
        $this->message= $tot_ok. " thumbnail généré sur ".$tot;
    }
}
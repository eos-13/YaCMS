<?php
class model_manage extends admin_common
{
    protected $graph;
    private $false_cnt;
    private $result;
    private $archive;
    private $archive_file;
    private $dumpsql_file;
    public $message = false;

    public function run()
    {
        $ret['offline'] = $this->get_offline();
        if ($ret['offline']) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");

        return $ret;
    }
    public function set_offline($post)
    {
        global $conf;
        $res = file_put_contents($conf->main_document_root."/.offline"," ");
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return true;
    }
    public function unset_offline($post)
    {
        global $conf;
        $file = $conf->main_document_root."/.offline";
        if (is_file)
        {
            $res = unlink($file );
            if ($res) $this->message = _("Opération effectuée");
            else $this->message = _("Opération echouée");
        }
        return true;
    }
    public function get_offline()
    {
        global $conf;
        if (is_file($conf->main_document_root."/.offline"))
            return true;
        else
            return false;
    }
    public function gen_dot()
    {
        try{
            require_once 'Image/GraphViz.php';
            $this->graph = new Image_GraphViz();
            global $conf;
            $this->graph->binPath=$conf->dot_path;
            $this->graph->addNode(
                    'Racine',
                    array(
                            'label' => 'HomePage',
                            'shape' => 'box'
                    )
            );
            require_once make_path('model','map','php');
            $mmap = new model_map($this->db,$this->req,false,false,false);
            $a = $mmap->json();
            $j=json_decode($a['json']);
            $this->false_cnt = 0;
            foreach($j->children as $key=>$val)
            {
                $uid = $val->uid;
                if (!$uid)
                {
                    $uid = "u".$this->false_cnt;
                    $this->false_cnt++;
                }
                $shape = "box";
                if ($val->style=="section"){
                    $uid = "s".$val->uid;
                    $shape = "oval";
                }
                $this->graph->addNode($uid,array('label' => $val->name,"shape"=>$shape));
                if ($uid){
                    $this->graph->addEdge(
                            array(
                               'Racine' => $uid
                            )
                    );
                }

                if (is_array($val->children))
                {
                    $this->recurs_parse($uid, $val->children);
                }

            }
            $img = $this->graph->fetch('png');
            header("Content-type: application/octet-stream" );

            header("Content-Length: " . strlen($img)*8);
            header("Content-Disposition: attachment; filename=map.png");
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

            echo $img;
            exit;
        } catch (Exception $e){
            $this->message = _("Opération echouée");

            return "Module 'Image/GraphViz.php' not available";
        }
    }
    private function recurs_parse($parent_name,$data)
    {
        foreach($data as $key =>$val)
        {
            $uid = $val->uid;
            if (!$uid)
            {
                $uid = "u".$this->false_cnt;
                $this->false_cnt++;
            }
            $shape = "box";
            if ($val->style=="section"){
                $uid = "s".$val->uid;
                $shape = "oval";
            }
            $this->graph->addNode($uid,array('label' => $val->name,"shape"=>$shape));
            if ($parent_name && $uid){
                $this->graph->addEdge(
                        array(
                                $parent_name => $uid,
                        )
                );
            }
            if (is_array($val->children))
                $this->recurs_parse($uid, $val->children);
        }
    }
    public function backup($post)
    {
        set_time_limit(3600);
        global $conf;
        $this->archive_file = '/tmp/archive_'.sanatize_string($conf->main_url_root).'.tar';

        $this->archive = new PharData($this->archive_file);

        if ($post['backup_type'] == "Full")
        {
            $this->prepare_mysql();
            $this->prepare_arbo("full");
        } elseif($post['backup_type'] == "Data") {
            $this->prepare_mysql();
            $this->prepare_arbo("Data");

        } elseif($post['backup_type'] == "Sql") {
            $this->prepare_mysql();
        }
        $this->make_archive();
        header("Content-type: application/octet-stream" );

        header("Content-Length: " . filesize($this->archive_file));
        header("Content-Disposition: attachment; filename=".basename($this->archive_file));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        echo file_get_contents($this->archive_file);
        exit;
    }
    private function prepare_mysql()
    {
        global $conf;
        $this->dumpsql_file = "/tmp/mysql_".sanatize_string($conf->main_db_name).".sql";
        if (is_file($this->dumpsql_file)) unlink($this->dumpsql_file);
        $commande = $conf->mysqldump_path."mysqldump -u ".$conf->main_db_user ." ". ($conf->main_db_pass."x" != "x"?"-p ".$conf->main_db_pass:"") ." ".$conf->main_db_name." > ".$this->dumpsql_file;
        exec($commande);
        $this->result['File'][]=array($this->dumpsql_file => "sql_archive/backup_bdd.sql");
    }
    private function prepare_arbo($type)
    {
        $path = false;
        if ($type == "full") { $path = "."; }
        else { $path = array('/img','/customer','/files','/conf'); }
        global $conf;
        if (is_array($path))
        {
            foreach($path as $val) { $this->result['Dir'][]=array($conf->main_document_root.$val => $conf->main_document_root.$val); }
        } else {
            $this->result['Dir'][]=array($conf->main_document_root."" => $conf->main_document_root."");
        }
    }
    private function make_archive()
    {
        global $conf;
            //chdir($conf->main_document_root."/..");
            try
            {
                if (is_file($this->archive_file)) unlink($this->archive_file);
                if (is_file($this->archive_file.".gz")) unlink($this->archive_file.".gz");
                $a = new PharData($this->archive_file);
                foreach($this->result as $key=>$val)
                {
                    if ($key == "Dir"){
                        foreach($val as $key1=>$val1)
                        {
                            foreach($val1 as $key2=>$val2)
                                $a->buildFromDirectory($val2,"/[^cache\/.+]/");
                        }
                    } else {
                        foreach($val as $key1=>$val1)
                        {
                            foreach($val1 as $key2=>$val2)
                                $a->addFile($key2,$val2);
                        }
                    }
                }
                // COMPRESS archive.tar FILE. COMPRESSED FILE WILL BE archive.tar.gz
                $a->compress(Phar::GZ);
            } catch (Exception $e){
                global $debug;
                $debug->collect($e);
            }
            // NOTE THAT BOTH FILES WILL EXISTS. SO IF YOU WANT YOU CAN UNLINK archive.tar
            unlink($this->archive_file);
            unlink($this->dumpsql_file);
            $this->archive_file = $this->archive_file.".gz";
    }
    public function upload_ico($post)
    {
        global $conf;
        //Me faut le typemime
        $target_dir = "/tmp/";
        $target_file = $target_dir . basename($_FILES["favico"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"]))
        {
            $check = getimagesize($_FILES["favico"]["tmp_name"]);
            if($check !== false)
            {
                $normalico = false;
                if (isset($check["3"]) && $check['3']."x" != "x")
                {
                    $t = preg_split('/ /',$check['3']);
                    $t1['width']=preg_split('/=/',$t[0]);
                    $t1['height']=preg_split('/=/',$t[1]);
                    $checksize = false;
                    if ($t1['width']["1"] == 16 && $t1['height']["1"] == 16 )
                    {
                        $checksize=true;
                    }
                    if ($imageFileType == "ico" || $check['mime'] == "image/vnd.microsoft.icon" && $checksize)
                    {
                        $normalico = true;
                    }
                    if ($check['mime'] == "image/vnd.microsoft.icon")
                    {
                        $check['mime'] = "image/ico";
                    }
                    move_uploaded_file($_FILES["favico"]["tmp_name"], $conf->main_document_root."/favicon.ico");
                    $conf->set_value('favicon','favicon.ico');
                    $conf->set_value('favicon_rel',($normalico?"icon":"shortcut icon"));
                    $conf->set_value('favicon_type',$check['mime']);


                }


                $this->message = _("Opération effectuée");
            } else {
                $this->message = _("Format non reconnu");
            }
        } else {
            $this->message = _("Opération echouée");
        }
    }
}
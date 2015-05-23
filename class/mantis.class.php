<?php
class mantis {

    public $db;
    public $log;

    public function __construct($db){
        $this->db = $db;
        global $log;
        $this->log = $log;
    }

    public function insert($id, $title, $message)
    {
        global $conf;
        $url = $conf->mantis_url;
        // set soap client
        try {
            $mantis = new SoapClient($url, array('encoding'=>'UTF8'));
            // add issue to mantisbt
            $issueData = array(
                "project"      => array("id" => $conf->mantis_project),
                "category"     => $conf->mantis_category,
                "reporter"     => array('name' => "site ".$conf->site_name),
                "summary"      => $title,
                "description"  => $message,
                "additional_information" => "msg id:: ".$id
            );
            $issueID = $mantis->mc_issue_add($conf->mantis_login,$conf->mantis_pass,$issueData);
            if ($issueID)
            {
                $this->log->log(get_class($this)." - ".print_r($issueData,1) . " res: ".print_r($issueID,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".print_r($issueData,1) . " res: ".print_r($issueID,1),LOG_ERR);
            }
            return $issueID;
        }
        catch (SoapFault $e)
        {
            echo $e->faultstring;
            $this->log->log(get_class($this)." - ".$e->faultstring ." - ".print_r($e,1),LOG_ERR);
            return false;
            exit();
        }

    }
}


?>
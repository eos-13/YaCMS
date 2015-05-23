<?php

    class test_cron
    {
        public $db;
        public $interval = 86400; // Interal de repetition du script
        public $Description;

        public function __construct($DB){
            $this->db = $DB;
            $this->Description = 'Test Script';
            $this->Version = "1.0";
        }
        public function description(){
            return ($this->Description);
        }
        public function version(){
            return ($this->Version);
        }
        public function do_action()
        {
            $result = 0;
            //Recherche les actions concernees
            $requete = "SELECT cron_schedule.id
                          FROM cron_schedule, cron
                         WHERE nextRun > 0
                           AND nextRun is not null
                           AND nextRun < now()
                           AND cron_schedule.cron_refid = cron.id
                           AND cron.object = 'test_cron'";
            $sql = $this->db->query($requete);
            while ($res = $this->db->fetch_object($sql))
            {
                $requete = "SELECT * FROM page";
                $sql1 = $this->db->query($requete);
                while($res1=$this->db->fetch_object($sql1))
                {
                    print $res1->id." ".$res1->title."\n";
                }
                $result = 1;
                $this->update_Result($result,$res->id);
            }
        }
        public function when_run()
        {
            return($this->getNextRun());
        }
        private function getNextRun()
        {
            $requete = "SELECT nextRun,  cron_schedule.id
                          FROM cron_schedule,
                               cron
                         WHERE cron.object = 'testScript'
                           AND cron_schedule.cron_refid = cron.id";
            $sql = $this->db->query($requete);
            $arr = array();
            while ($res = $this->db->fetch_object())
            {
                $arr[$res->id]=$res->nextRun;
                $sql1 = $this->db->query("SELECT * FROM page");
                if ($sql1)
                {
                    $this->update_Result(1,$res->id);
                } else {
                    $this->update_Result(0,$res->id);
                }
            }
            return $arr;
        }
        private function setNextRun($id)
        {
            $nextRun = $this->setNextRun_1($id);
//            print 'step'.$id.'<br/>';
            $requete = "UPDATE cron_schedule
                           SET nextRun = FROM_UNIXTIME(".$nextRun . ")
                         WHERE id =".$id;
            $sql = $this->db->query($requete);
//            $nextRun = $this->setNextRun_2($id);
//            $requete = "UPDATE cron_schedule
//                           SET nextRun = ".$nextRun . "
//                         WHERE id =".$id;
//            $sql = $this->db->query($requete);
            return($sql);

        }
        private function setNextRun_2($id)
        {
            //Next Monday
            $requete = "SELECT UNIX_TIMESTAMP(lastRun) as lastRun
                          FROM cron_schedule
                         WHERE cron_schedule.id=".$id;
            $sql = $this->db->query($requete);

            $res = $this->db->fetch_object();
            $lastRun = $res->lastRun;
            $now = time();
            $nextRun = 0;
            while($nextRun < $now && date('N',$nextRun != 1))
            {
                $nextRun = $nextRun + $lastRun + (3600 * 24);
                $lastRun=0;
            }
            return($nextRun);
        }
        private function setNextRun_1($id)
        {
            //Last Run + n second
            $requete = "SELECT UNIX_TIMESTAMP(lastRun) as lastRun
                          FROM cron_schedule
                         WHERE cron_schedule.id=".$id;
            $sql = $this->db->query($requete);

            $res = $this->db->fetch_object();
            $lastRun = $res->lastRun;
            $now = time();
            $nextRun = 0;
            while($nextRun < $now)
            {
                $nextRun = $nextRun + $lastRun + $this->interval;
                $nextRun = mktime(12,0,0,date('m',$nextRun),date('d',$nextRun),date('Y',$nextRun));
                $lastRun=0;
            }
            $nextRun -= 12 * 3600;
            return($nextRun);
        }
        private function update_Result($result,$id)
        {
            $requete = "UPDATE cron_schedule
                           SET lastRun=now(),
                               has_run = has_run + 1 ,
                               last_result = ".$result . "
                         WHERE id =".$id;
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $sql1 = $this->setNextRun($id);
                return($sql1);
            } else {
                return false;
            }
        }
        public function init()
        {
            $requete = " INSERT INTO cron
                                    (nom,
                                     description,
                                     object,
                                     active)
                            VALUES ( 'TestScript - Demo' ,
                                     '".$this->Description."' ,
                                     'test_cron' ,
                                     0 );";
            $this->db->query($requete);
            $lastId = $this->db->last_insert_id('cron');
            $requete = " INSERT INTO cron_schedule
                                   ( cron_refid )
                            VALUES ( ".$lastId." )";
            $this->db->query($requete);
            $lastId2 = $this->db->last_insert_id('cron_schedule');
            $this->setNextRun($lastId2);
        }
        public function delete()
        {
            $requete = "DELETE FROM cron
                              WHERE object = 'test_cron'";
            $sql = $this->db->query($requete);
            return($sql);
        }

        public function activate()
        {
            $requete = "UPDATE cron
                          SET active = 1
                        WHERE object='test_cron'";
            $sql =$this->db->query($requete);
            return($sql);
        }
        public function deactivate()
        {
            $requete = "UPDATE cron
                           SET active = 0
                         WHERE object='test_cron'";
            $sql =$this->db->query($requete);
            return($sql);
        }
  }
?>
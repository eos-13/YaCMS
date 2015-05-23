<?php

class trigger extends common_object{
    //! Handler de la base de données
    public $db;
    //! Handler syslog
    public $log;
    //! Array contenant la liste des triggers enregistré en base de données
    public $list=array();
    //! Nom de la table SQL des triggers
    public $table = 'events';

    /**
     \brief        Constructeur de l'objet.  Set la variable $db
     \param        db           Handler de la base de données
     \return       void         vide
     */
     public function __construct($db)
    {
        $this->db = $db;
        global $log;
        $this->log = $log;
    }
    /**
     \brief        Constructeur de l'objet.  Set la variable $db
     \return       array         Array contenant la liste des évènements
     */
    public function list_all()
    {
        return $this->make_list($this->db,
                                $this->table,
                                "display_name",
                                array('active'=>array(1)),
                                'id',
                                'display_name');
    }

        /**
         *   Function called when an event occurs
         *   This function call all qualified triggers.
         *
         *   @param     string        $action     Trigger event code
         *   @param     object        $object     Objet concern
         *   @param     User          $user       Objet user
         *   @return    int                       Nb of triggers ran if no error, -Nb of triggers with errors otherwise.
         */
        function run_trigger($action,$object,$user=false,$data=false)
        {
            global $conf;
            // Check parameters
            if (! is_object($object) || ! is_object($conf))    // Error
            {
                $this->log->log(get_class($this).'::run_triggers was called with wrong parameters action='.$action.' object='.is_object($object).' user='.is_object($user), LOG_ERR);
                return -1;
            }
            if (!$user)
            {
                global $current_user;
                $user = $current_user;
            }
            if (! is_object($user))    // Warning
            {
                $this->log->log(get_class($this).'::run_triggers was called with wrong parameters action='.$action.' object='.is_object($object).' user='.is_object($user), LOG_WARNING);
            }

            $nbfile = $nbtotal = $nbok = $nbko = 0;

            $orders = array();
            $i=0;

            $dirtriggers=array($conf->main_document_root.'/triggers/');
            foreach($dirtriggers as $dir)
            {
                if (! is_dir($dir)) continue;

                $handle=opendir($dir);
                if (is_resource($handle))
                {
                    while (($file = readdir($handle))!==false)
                    {
                        if (is_readable($dir."/".$file) && preg_match('/^(.+)\.trigger\.class\.php$/i',$file,$reg))
                        {
                            if (preg_match('/NORUN$/i',$file)) continue;
                            $part1=strtolower($reg[1]);
                            $nbfile++;
                            include_once $dir.'/'.$file;
                            $orders[]=$part1;

                            $i++;
                        }
                    }
                }
            }

            // Loop on each trigger
            foreach ($orders as $key => $value)
            {
                $objMod = new $value($this->db);
                if ($objMod)
                {
                    $this->log->log(get_class($this)."::run_triggers action=".$action." Launch triggers '".$value."'", LOG_INFO);
                    $result=$objMod->run_trigger($action,$object,$user,$data);
                    if ($result > 0)
                    {
                        // Action OK
                        $nbtotal++;
                        $nbok++;
                    }
                    if ($result == 0)
                    {
                        // Aucune action faite
                        $nbtotal++;
                    }
                    if ($result < 0)
                    {
                        // Action KO
                        $nbtotal++;
                        $nbko++;
                        if (! empty($objMod->errors)) $this->errors=array_merge($this->errors,$objMod->errors);
                        else if (! empty($objMod->error))  $this->errors[]=$objMod->error;
                    }
                } else {
                    $this->log->log(get_class($this)."::run_triggers action=".$action." Failed to instantiate trigger for file '".$files[$key]."'", LOG_ERR);
                }
            }

            if ($nbko)
            {
                $this->log->log(get_class($this)."::run_triggers action=".$action." Files found: ".$nbfile.", Files launched: ".$nbtotal.", Done: ".$nbok.", Failed: ".$nbko, LOG_ERR);
                return -$nbko;
            } else {
                $this->log->log(get_class($this)."::run_triggers Files found: ".$nbfile.", Files launched: ".$nbtotal.", Done: ".$nbok.", Failed: ".$nbko, LOG_DEBUG);
                return $nbok;
            }
        }
}
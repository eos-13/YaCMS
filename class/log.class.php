<?php

class log
{
    //! Prefixe pour les logs syslog
    private $prefix="[iZend2]";
    /**
     \brief        Constructeur de l'objet
     \return       void
     */
    public function __construct()
    {
        global $conf;
        $this->prefix="[".$conf->syslog_prefix."]";
    }
    /**
     \brief        Envoie les log vers syslog
     \param        message       Le message à envoyer à syslog
     \param        prio          La priorité syslog du message
     \return       void
     */
    public function log($message,$prio=LOG_WARNING)
    {
        $message = $this->prefix." ".$message;
        syslog($prio, $message);
    }
}
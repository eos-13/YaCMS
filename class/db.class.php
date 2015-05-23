<?php
/*
  * GLE by Babel-Services
  *
  * Author: Jean-Marc LE FEVRE <jm.lefevre@babel-services.com>
  * Licence : Artistic Licence v2.0
  *
  * Version 1.
  * Create on : 4-1-2009
  *
  * Infos on http://www.babel-services.com
  *
  *//*
 */

/**
        \file       htdocs/lib/databases/mysqli.lib.php
        \brief      Fichier de la classe permettant de gerer une base mysql
        \version    $Id: mysqli.lib.php,v 1.45 2008/08/07 17:11:05 eldy Exp $
*/
// Pour compatibilite lors de l'upgrade
if (! defined('DOL_DOCUMENT_ROOT'))     define('DOL_DOCUMENT_ROOT', '../..');


/**
        \class      DoliDb
        \brief      Classe permettant de gerer la database de dolibarr
*/
class Db
{
    //! Handler de base
  protected $db;
  //! Nom du gestionnaire
  public $type='mysqli';
  //! Charset
  public $forcecharset='utf8';
  //! Collate
  public $forcecollate='utf8_general_ci';
  //! Version min database
  public $versionmin=array(4,1,0);
  //! Resultset de la derniere requete
  public $results;
  //! 1 si connecte, 0 sinon
  public $connected;
  //! 1 si base selectionne, 0 sinon
  public $database_selected;
  //! Nom base selectionnee
  public $database_name;
  //! Nom user base
  public $database_user;
  //! 1 si une transaction est en cours, 0 sinon
  public $transaction_opened;
  //! Derniere requete executee
  public $lastquery;
  //! Derniere requete executee avec echec
  public $lastqueryerror;
  //! Message erreur mysql
  public $lasterror;
  //! Message erreur mysql
  public $lasterrno;

  public $ok;
  public $error;

    // Constantes pour conversion code erreur MySql en code erreur generique
    public $errorcode_map = array(
        1004 => 'DB_ERROR_CANNOT_CREATE',
        1005 => 'DB_ERROR_CANNOT_CREATE',
        1006 => 'DB_ERROR_CANNOT_CREATE',
        1007 => 'DB_ERROR_ALREADY_EXISTS',
        1008 => 'DB_ERROR_CANNOT_DROP',
        1025 => 'DB_ERROR_NO_FOREIGN_KEY_TO_DROP',
        1044 => 'DB_ERROR_ACCESSDENIED',
        1046 => 'DB_ERROR_NODBSELECTED',
        1048 => 'DB_ERROR_CONSTRAINT',
        1050 => 'DB_ERROR_TABLE_ALREADY_EXISTS',
        1051 => 'DB_ERROR_NOSUCHTABLE',
        1054 => 'DB_ERROR_NOSUCHFIELD',
        1060 => 'DB_ERROR_COLUMN_ALREADY_EXISTS',
        1061 => 'DB_ERROR_KEY_NAME_ALREADY_EXISTS',
        1062 => 'DB_ERROR_RECORD_ALREADY_EXISTS',
        1064 => 'DB_ERROR_SYNTAX',
        1068 => 'DB_ERROR_PRIMARY_KEY_ALREADY_EXISTS',
        1075 => 'DB_ERROR_CANT_DROP_PRIMARY_KEY',
        1091 => 'DB_ERROR_NOSUCHFIELD',
        1100 => 'DB_ERROR_NOT_LOCKED',
        1136 => 'DB_ERROR_VALUE_COUNT_ON_ROW',
        1146 => 'DB_ERROR_NOSUCHTABLE',
        1216 => 'DB_ERROR_NO_PARENT',
        1217 => 'DB_ERROR_CHILD_EXISTS',
        1451 => 'DB_ERROR_CHILD_EXISTS'
    );


    /**
            \brief      Ouverture d'une connexion vers le serveur et eventuellement une database.
            \param      type        Type de base de donnees (mysql ou pgsql)
            \param        host        Addresse de la base de donnees
            \param        user        Nom de l'utilisateur autorise
            \param        pass        Mot de passe
            \param        name        Nom de la database
            \param        port        Port of database server
            \return     int            1 en cas de succes, 0 sinon
    */
    function __construct($type='mysqli', $host, $user, $pass, $name='', $port=0)
    {
        global $langs;

        $this->database_user=$user;

        $this->transaction_opened=0;

        //print "Name DB: $host,$user,$pass,$name<br>";

        if (! function_exists("mysqli_connect"))
        {
            $this->connected = 0;
            $this->ok = 0;
            $this->error="Mysqli PHP functions are not available in this version of PHP. Try to use another driver.";
            syslog(LOG_ERR,"Mysql PHP functions are not available in this version of PHP. Try to use another driver.");
            return $this->ok;
        }

        if (! $host)
        {
            $this->connected = 0;
            $this->ok = 0;
            $this->error=$langs->trans("ErrorWrongHostParameter");
            syslog(LOG_ERR,"Erreur Connect, wrong host parameters");
            return $this->ok;
        }

        // Essai connexion serveur
        $this->db = $this->connect($host, $user, $pass, '', $port);

        if ($this->db)
        {
            $this->connected = 1;
            $this->ok = 1;
        }
        else
        {
            // host, login ou password incorrect
            $this->connected = 0;
            $this->ok = 0;
            $this->error=mysqli_connect_error();
            syslog(LOG_ERR,"Erreur Connect mysqli_connect_error=".$this->error);
        }

        // Si connexion serveur ok et si connexion base demandee, on essaie connexion base
        if ($this->connected && $name)
        {
            if ($this->select_db($name))
            {
                $this->database_selected = 1;
                $this->database_name = $name;
                $this->ok = 1;

                // Si client connecte avec charset different de celui de Dolibarr
                /*if (mysqli_client_encoding ( $this->db ) != $this->getDefaultCharacterSetDatabase())
                {
                    $this->query("SET NAMES '".$this->forcecharset."'", $this->db);
                    $this->query("SET CHARACTER SET '".$this->forcecharset."'", $this->db);
                }*/
            }
            else
            {
                $this->database_selected = 0;
                $this->database_name = '';
                $this->ok = 0;
                $this->error=$this->error();
                syslog(LOG_DEBUG,"Erreur Select_db");
            }
        }
        else
        {
            // Pas de selection de base demandee, ok ou ko
            $this->database_selected = 0;
        }

        return $this->ok;
    }


    /**
            \brief      Selectionne une database.
            \param        database        Nom de la database
            \return        boolean         true si ok, false si ko
    */
    function select_db($database)
    {
        return mysqli_select_db($this->db,$database);
    }


    /**
            \brief      Connection vers le serveur
            \param        host        addresse de la base de donnees
            \param        login        nom de l'utilisateur autorise
            \param        passwd        mot de passe
            \param        name        nom de la database (ne sert pas sous mysql, sert sous pgsql)
            \param        port        Port of database server
            \return        resource    handler d'acces e la base
            \seealso    close
    */
    function connect($host, $login, $passwd, $name, $port=0)
    {
        syslog(LOG_DEBUG,"DoliDB::connect host=$host, port=$port, login=$login, passwd=--hidden--, name=$name");

        $newhost=$host;
        $newport=$port;

        // With mysqli, port must be in connect parameters
        if (! $newport) $newport=3306;

        $this->db  = @mysqli_connect($newhost, $login, $passwd, $name, $newport);
        //force les enregistrement en latin1 si la base est en utf8 par defaut
        // Supprime car plante sur mon PHP-Mysql. De plus, la base est forcement en latin1 avec
        // les nouvelles version de Dolibarr car force par l'install Dolibarr.
        //$this->query('SET NAMES '.$this->forcecharset);
        //print "Resultat fonction connect: ".$this->db;
        if ($this->db)
        {
            $this->query("SET NAMES '".$this->forcecharset."'", $this->db);
            $this->query("SET CHARACTER SET '".$this->forcecharset."'", $this->db);
        }
        return $this->db;
    }

    /**
            \brief          Renvoie la version du serveur
            \return            string      Chaine version
    */
    function getVersion()
    {
//        $resql=$this->query('SELECT VERSION()');
//        $row=$this->fetch_row($resql);
//        return $row[0];
        return mysqli_get_server_info($this->db);
    }

    /**
     \brief          Renvoie la version du serveur sous forme de nombre
     \return            string      Chaine version
    */
    function getIntVersion()
    {
            $version=    $this->getVersion();
            $vlist=preg_split('/[.-]/',$version);
            if (strlen($vlist[1])==1){
                $vlist[1]="0".$vlist[1];
            }
            if (strlen($vlist[2])==1){
                $vlist[2]="0".$vlist[2];
            }
            return $vlist[0].$vlist[1].$vlist[2];
    }

    /**
            \brief          Renvoie la version du serveur dans un tableau
            \return            array          Tableau de chaque niveau de version
    */
    function getVersionArray()
    {
        return preg_split('/\./',$this->getVersion());
    }


    /**
        \brief      Fermeture d'une connexion vers une database.
        \return        resource
        \seealso    connect
    */
    function close()
    {
        return mysqli_close($this->db);
    }


    /**
        \brief      Debut d'une transaction.
        \return        int         1 si ouverture transaction ok ou deja ouverte, 0 en cas d'erreur
    */
    function begin()
    {
        if (! $this->transaction_opened)
        {
            $ret=$this->query("BEGIN");
            if ($ret)
            {
                $this->transaction_opened++;
                syslog(LOG_DEBUG,"BEGIN Transaction");
            }
            return $ret;
        }
        else
        {
            $this->transaction_opened++;
            return 1;
        }
    }

    /**
        \brief      Validation d'une transaction
        \return        int         1 si validation ok ou niveau de transaction non ouverte, 0 en cas d'erreur
    */
    function commit()
    {
        if ($this->transaction_opened<=1)
        {
            $ret=$this->query("COMMIT");
            if ($ret)
            {
                $this->transaction_opened=0;
                syslog(LOG_DEBUG,"COMMIT Transaction");
            }
            return $ret;
        }
        else
        {
            $this->transaction_opened--;
            return 1;
        }
    }

    /**
        \brief      Annulation d'une transaction et retour aux anciennes valeurs
        \return        int         1 si annulation ok ou transaction non ouverte, 0 en cas d'erreur
    */
    function rollback()
    {
        if ($this->transaction_opened<=1)
        {
            $ret=$this->query("ROLLBACK");
            $this->transaction_opened=0;
            syslog(LOG_DEBUG,"ROLLBACK Transaction");
            return $ret;
        }
        else
        {
            $this->transaction_opened--;
            return 1;
        }
    }

    /**
        \brief      Effectue une requete et renvoi le resultset de reponse de la base
        \param        query        Contenu de la query
        \return        resource    Resultset de la reponse
    */
    function query($query)
    {
        $this->query = $query;
        $query = trim($query);
        if (! $this->database_name)
        {
            // Ordre SQL ne necessitant pas de connexion a une base (exemple: CREATE DATABASE
            $ret = mysqli_query($this->db,$query);
        } else {
            $ret = mysqli_query($this->db,$query);
        }

        if (! preg_match('/^COMMIT/i',$query) && ! preg_match('/^ROLLBACK/i',$query))
        {
            // Si requete utilisateur, on la sauvegarde ainsi que son resultset
            if (! $ret)
            {
                $this->lastqueryerror = $query;
                $this->lasterror = $this->error();
                $this->lasterrno = $this->errno();
            }
            $this->lastquery=$query;
            $this->results = $ret;
        }

        return $ret;
    }

    /**
        \brief      Renvoie la ligne courante (comme un objet) pour le curseur resultset.
        \param      resultset   Curseur de la requete voulue
        \return        resource
    */
    function fetch_object($resultset=0)
    {
        // Si le resultset n'est pas fourni, on prend le dernier utilise sur cette connexion
        if (! is_object($resultset)) { $resultset=$this->results; }
        if (!$resultset) {
            syslog(LOG_ERR , $this->lastquery);
        }
        return mysqli_fetch_object($resultset);
    }


    /**
        \brief      Renvoie les donnees dans un tableau.
        \param      resultset   Curseur de la requete voulue
        \return        array
    */

    function fetch_array($resultset=0)
    {
        // Si le resultset n'est pas fourni, on prend le dernier utilise sur cette connexion
        if (! is_object($resultset)) { $resultset=$this->results; }
        return mysqli_fetch_array($resultset);
    }

    /**
        \brief      Renvoie les donnees comme un tableau.
        \param      resultset   Curseur de la requete voulue
        \return        array
    */

    function fetch_row($resultset=0)
    {
        // Si le resultset n'est pas fourni, on prend le dernier utilise sur cette connexion
        if (! is_bool($resultset))
        {
            if (! is_object($resultset)) { $resultset=$this->results; }
            return mysqli_fetch_row($resultset);
        }
        else
        {
            // si le curseur est un booleen on retourne la valeur 0
            return 0;
        }
    }

    /**
        \brief      Renvoie le nombre de lignes dans le resultat d'une requete SELECT
        \see        affected_rows
        \param      resultset   Curseur de la requete voulue
        \return     int            Nombre de lignes
    */
    function num_rows($resultset=0)
    {
        // Si le resultset n'est pas fourni, on prend le dernier utilise sur cette connexion
        if (! is_object($resultset)) { $resultset=$this->results; }
        return mysqli_num_rows($resultset);
    }

    /**
        \brief      Renvoie le nombre de lignes dans le resultat d'une requete INSERT, DELETE ou UPDATE
        \see        num_rows
        \param      resultset   Curseur de la requete voulue
        \return     int            Nombre de lignes
    */

    function affected_rows($resultset=0)
    {
        // Si le resultset n'est pas fourni, on prend le dernier utilise sur cette connexion
        if (! is_object($resultset)) { $resultset=$this->results; }
        // mysql necessite un link de base pour cette fonction contrairement
        // a pqsql qui prend un resultset
        return mysqli_affected_rows($this->db);
    }


    /**
        \brief      Libere le dernier resultset utilise sur cette connexion.
        \param      resultset   Curseur de la requete voulue
    */
    function free($resultset=0)
    {
        // Si le resultset n'est pas fourni, on prend le dernier utilise sur cette connexion
        if (! is_object($resultset)) { $resultset=$this->results; }
        // Si resultset en est un, on libere la memoire
        if (is_object($resultset)) mysqli_free_result($resultset);
    }


    /**
        \brief      Defini les limites de la requete.
        \param        limit       nombre maximum de lignes retournees
        \param        offset      numero de la ligne e partir de laquelle recuperer les lignes
        \return        string      chaine exprimant la syntax sql de la limite
    */
    function plimit($limit=0,$offset=0)
    {
        global $conf;
        if (! $limit) $limit=$conf->liste_limit;
        if ($offset > 0) return " LIMIT $offset,$limit ";
        else return " LIMIT $limit ";
    }


    /**
        \brief      Defini le tri de la requete.
        \param        sortfield   liste des champ de tri
        \param        sortorder   ordre du tri
        \return        string      chaine exprimant la syntax sql de l'ordre de tri
        \TODO        A mutualiser dans classe mere
    */
    function order($sortfield=0,$sortorder=0)
    {
        if ($sortfield)
        {
            $return='';
            $fields=preg_split('/,/',$sortfield);
            foreach($fields as $val)
            {
                if (! $return) $return.=' ORDER BY ';
                else $return.=',';

                $return.=$val;
                if ($sortorder) $return.=' '.$sortorder;
            }
            return $return;
        }
        else
        {
            return '';
        }
    }


    /**
        \brief      Escape a string to insert data.
        \param        stringtoencode        String to escape
        \return        string                String escaped
    */
    function escape($stringtoencode)
    {
        return addslashes($stringtoencode);
    }


    /**
    *   \brief      Formatage (par la base de donnees) d'un champ de la base au format tms ou Date (YYYY-MM-DD HH:MM:SS)
    *               afin de retourner une donnee toujours au format universel date tms unix.
    *               Fonction a utiliser pour generer les SELECT.
    *   \param        param       Date au format text a convertir
    *   \return        date        Date au format tms.
    *    \TODO        Remove unix_timestamp functions
    */
    function pdate($param)
    {
        return "unix_timestamp(".$param.")";
    }



    /**
        \brief      Formatage d'un if SQL
        \param        test            chaine test
        \param        resok           resultat si test egal
        \param        resko           resultat si test non egal
        \return        string          chaine formate SQL
    */
    function ifsql($test,$resok,$resko)
    {
        return 'IF('.$test.','.$resok.','.$resko.')';
    }


    /**
        \brief      Renvoie la derniere requete soumise par la methode query()
        \return        lastquery
    */
    function lastquery()
    {
        return $this->lastquery;
    }

    /**
        \brief      Renvoie la derniere requete en erreur
        \return        string    lastqueryerror
    */
    function lastqueryerror()
    {
        return $this->lastqueryerror;
    }

    /**
        \brief      Renvoie le libelle derniere erreur
        \return        string    lasterror
    */
    function lasterror()
    {
        return $this->lasterror;
    }

    /**
        \brief      Renvoie le code derniere erreur
        \return        string    lasterrno
    */
    function lasterrno()
    {
        return $this->lasterrno;
    }

    /**
        \brief     Renvoie le code erreur generique de l'operation precedente.
        \return    error_num       (Exemples: DB_ERROR_TABLE_ALREADY_EXISTS, DB_ERROR_RECORD_ALREADY_EXISTS...)
    */
    function errno()
    {
        if (! $this->connected) {
            // Si il y a eu echec de connexion, $this->db n'est pas valide.
            return 'DB_ERROR_FAILED_TO_CONNECT';
        }
        else {
            if (isset($this->errorcode_map[mysqli_errno($this->db)])) {
                return $this->errorcode_map[mysqli_errno($this->db)];
            }
            $errno=mysqli_errno($this->db);
            return ($errno?'DB_ERROR_'.$errno:'0');
        }
    }

    /**
        \brief     Renvoie le texte de l'erreur mysql de l'operation precedente.
        \return    error_text
    */
    function error()
    {
        if (! $this->connected) {
            // Si il y a eu echec de connexion, $this->db n'est pas valide pour mysqli_error.
            return 'Not connected. Check setup parameters in conf/conf.php file and your mysql client and server versions';
        }
        else {
            return mysqli_error($this->db);
        }
    }

    /**
        \brief     Recupere l'id genere par le dernier INSERT.
        \param     tab     Nom de la table concernee par l'insert. Ne sert pas sous MySql mais requis pour compatibilite avec Postgresql
        \return    int     id
    */
    function last_insert_id($tab)
    {
        return mysqli_insert_id($this->db);
    }

    /**
    *    \brief        Return charset used to store data in database
    *    \return        string        Charset
    */
    function getDefaultCharacterSetDatabase(){
        $resql=$this->query('SHOW VARIABLES LIKE \'character_set_database\'');
        if (!$resql)
        {
            // version Mysql < 4.1.1
            return $this->forcecharset;
        }
        $liste=$this->fetch_array($resql);
        return $liste['Value'];
    }

    function getListOfCharacterSet(){
        $resql=$this->query('SHOW CHARSET');
        $liste = array();
        if ($resql)
            {
            $i = 0;
            while ($obj = $this->fetch_object($resql) )
            {
                $liste[$i]['charset'] = $obj->Charset;
                $liste[$i]['description'] = $obj->Description;
                $i++;
            }
            $this->free($resql);
        } else {
            // version Mysql < 4.1.1
            return null;
        }
        return $liste;
    }

    /**
    *    \brief        Return collation used in database
    *    \return        string        Collation value
    */
    function getDefaultCollationDatabase(){
        $resql=$this->query('SHOW VARIABLES LIKE \'collation_database\'');
        if (!$resql)
        {
            // version Mysql < 4.1.1
            return $this->forcecollate;
        }
        $liste=$this->fetch_array($resql);
        return $liste['Value'];
    }

    function getListOfCollation(){
        $resql=$this->query('SHOW COLLATION');
        $liste = array();
        if ($resql)
            {
            $i = 0;
            while ($obj = $this->fetch_object($resql) )
            {
                $liste[$i]['collation'] = $obj->Collation;
                $i++;
            }
            $this->free($resql);
        } else {
            // version Mysql < 4.1.1
            return null;
        }
        return $liste;
    }
}

?>

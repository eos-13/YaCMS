<?php

$path=__DIR__.'/../../lib/SphinxQL-Query-Builder-master/src/';
set_include_path(get_include_path()  . $path);

require_once(__DIR__."/../../lib/SphinxQL-Query-Builder-master/vendor/autoload.php");


use Foolz\SphinxQL\Connection;
use Foolz\SphinxQL\SphinxQL;
load_alternative_class('class/common_soap_object.class.php');
class api_Sphinx extends common_soap_object
{
    private $index='izend2_idx';
    public $conn;
    protected $db;
    public $transaction;
    public $trans;
    private $meta;
    public $log;
    private $use_sphinx=false;

    public function __construct($db=false)
    {
        global $conf;
        if ($conf->use_sphinx == "on")
        {
            $conn = new Connection();
            global $conf;
            $conn->setParams(
                array(
                    'host' => $conf->sphinx_host,
                    'port' => $conf->sphinx_port)
            );
            $this->index = $conf->sphinx_index;
            $this->db = $db;
            $this->conn = $conn;
            global $log;
            $this->log = $log;
            $this->use_sphinx = true;
        } else {
            $this->use_sphinx = false;
        }
    }
    /**
     *
     * @param string $set
     * @return string $matchtype
     */
    private function set_match_any_char($set=false)
    {
        if (!$this->use_sphinx) return true;
        if ($set) return '|';
        else return ' ';
    }
    //Sample:
    //  load_alternative_class('class/sphinx.class.php');
    //  $sp = new Sphinx();
    //  $result = $sp->query(
    //      array('id'),
    //      array('contenu' =>
    //          array('société','DEXIS')
    //      )
    //    );
    /**
     * @desc Query sphinx
     * @param array $col[]
     * @param array $match[]
     * @param array $where[]
     * @param int $offset
     * @param int $limit
     * @param array $orderby[]
     * @return array $res
     */
    //Howto:
    /*
     * <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:api="http://www.cikord.com/api_sphinx" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">
       <soapenv:Header/>
       <soapenv:Body>
          <api:query>
             <col xsi:type="pur:ArrayOfString" soapenc:arrayType="xsd:string[1]">
             <item>page_id</item>
             </col>
             <match xsi:type="pur:ArrayOfString" soapenc:arrayType="xsd:string[1]">
               <test  xsi:type="pur:ArrayOfString" soapenc:arrayType="xsd:string[1]">
                   <item>légume</item>
               </test>
             </match>

             <offset>0</offset>
             <limit>10</limit>

          </api:query>
       </soapenv:Body>
    </soapenv:Envelope>
     */
    public function query($col=array(),$match=array(),$where=false,$offset=false,$limit=false,$orderby=false)
    {
        if (!$this->use_sphinx) return true;
        $col_str=join(",",$col);
        $query = SphinxQL::create($this->conn)->select($col_str)
        ->from($this->index);
        $query->set_match_any_char(true);

        foreach($match as $key=>$val)
        {
            if (is_array($val))
            {
                $query->match($key,join('|',$val),1);
            }
        }
        //if (is_array($where)) { $query->where(join(',',$where)); }
        if ($where) {
            $query->where($where['col'],$where['ope'],$where['data']);
        }
        if (is_array($orderby)) { $query->orderBy($orderby["key"],$orderby['dir']); }
        //$query->
        if ($limit && $offset)
        {
            $query->limit($offset, $limit);
        } else if ($limit)
        {
            $query->limit($limit);
        }
        $this->log->log(get_class($this)." - Query: ".print_r($query,1),LOG_DEBUG);
        $result = $query->execute();
        $this->log->log(get_class($this)." - Result: ".print_r($result,1),LOG_DEBUG);
        $this->meta = SphinxQL::create($this->conn)->query('SHOW META')->execute();
        $this->log->log(get_class($this)." - Meta: ".print_r($this->meta,1),LOG_DEBUG);
        return ($result);

    }
    //Sample:
    // load_alternative_class('class/sphinx.class.php');
    // $sp = new Sphinx();
    // $a = array(
    //     "id" => 10,
    //     "title" => 'Annonce 5',
    //     "contenu" => addslashes("UTF-8BFL - DEXIS est une enseigne technique du Groupe DESCOURS & CABAUD, leader du négoce aux professionnels. Notre société est spécialisée dans la distribution de composants de transmissions mécaniques, d'automatismes, pneumatiques et instrumentation, d'enlèvement de métal et d'équipements de protection pour l'industrie. Dans le cadre de notre développement, nous recrutons pour le secteur Yvelines (78), un : ATTACHÉ COMMERCIAL ITINÉRANT h/? Rattaché à une agence, vous avez comme objectif le développement du chiffre d'affaires d'un secteur géographique défini (78 sud), avec une orientation généraliste. Vous identifiez et qualifiez les besoins des clients, leurs apportez des solutions et orientez les choix sur nos produits. Vous répondez également aux appels d'offres, assurez le suivi des affaires jusqu'à leur déroulement final, en collaboration avec l'équipe interne. De formation BAC à BAC+2, vous avez des connaissances certaines dans les domaines de la mécanique ou l'automatisme et pneumatique et idéalement une première expérience dans l'industrie (BE, maintenance). En rejoignant notre Groupe, vous élargirez votre champ d'expertise dans la vente et le conseil technique. Merci d'envoyer votre candidature sous la référence 11934/MON via le lien Postuler. Pour plus d'informations, posez-nous vos questions sur notre page Facebook : http://www.facebook.com/DescoursCabaudRH DEXIS DEXIS"),
    //     "date_validation" => time()
    // );
    // $res = $sp->insert($a);

    /**
     * @desc Insert into Sphinx sphinx
     * @param array $data
     * @return string $query
     */
    public function insert($array)
    {
        if (!$this->use_sphinx) return true;
        if ($this->transaction == 1)
        {
            $this->query = SphinxQL::create($this->conn)->insert()->into($this->index);
        } else {
            $this->query = SphinxQL::create($this->conn)->insert()->into($this->index);
        }
        try
        {
            $this->query->set($array)->execute();
            $this->log->log(get_class($this)." - Insert: ".print_r($this->query,1),LOG_DEBUG);
        } catch (Exception $e){
            global $debug;
            $debug->collect($e);
            $this->log->log(get_class($this)." - Insert Error: ".print_r($e,1),LOG_ERR);
            return false;
        }

        return $this->query;
    }
    //Sample:
    // load_alternative_class('class/sphinx.class.php');
    // $sp = new Sphinx();
    // $a = array(
    //     "id" => 10,
    //     "title" => 'Annonce 5',
    //     "contenu" => addslashes("UTF-8BFL - DEXIS est une enseigne technique du Groupe DESCOURS & CABAUD, leader du négoce aux professionnels. Notre société est spécialisée dans la distribution de composants de transmissions mécaniques, d'automatismes, pneumatiques et instrumentation, d'enlèvement de métal et d'équipements de protection pour l'industrie. Dans le cadre de notre développement, nous recrutons pour le secteur Yvelines (78), un : ATTACHÉ COMMERCIAL ITINÉRANT h/? Rattaché à une agence, vous avez comme objectif le développement du chiffre d'affaires d'un secteur géographique défini (78 sud), avec une orientation généraliste. Vous identifiez et qualifiez les besoins des clients, leurs apportez des solutions et orientez les choix sur nos produits. Vous répondez également aux appels d'offres, assurez le suivi des affaires jusqu'à leur déroulement final, en collaboration avec l'équipe interne. De formation BAC à BAC+2, vous avez des connaissances certaines dans les domaines de la mécanique ou l'automatisme et pneumatique et idéalement une première expérience dans l'industrie (BE, maintenance). En rejoignant notre Groupe, vous élargirez votre champ d'expertise dans la vente et le conseil technique. Merci d'envoyer votre candidature sous la référence 11934/MON via le lien Postuler. Pour plus d'informations, posez-nous vos questions sur notre page Facebook : http://www.facebook.com/DescoursCabaudRH DEXIS DEXIS"),
    //     "date_validation" => time()
    // );
    // $res = $sp->update($a);
    /**
     * @desc Update Sphinx data
     * @param array $data
     * @return string $query
     */
    public function update($array)
    {
        if (!$this->use_sphinx) return true;
        if ($this->transaction == 1)
        {
            $this->query = SphinxQL::create($this->conn)->replace()->into($this->index);
        } else {
            $this->query = SphinxQL::create($this->conn)
                 ->replace()
                 ->into($this->index);
        }
        try
        {
            $this->query->set($array)->execute();
            $this->log->log(get_class($this)." - Update: ".print_r($this->query,1),LOG_DEBUG);
        } catch (Exception $e){
            global $debug;
            $debug->collect($e);
            $this->log->log(get_class($this)." - Update Error: ".print_r($e,1),LOG_ERR);
            return false;
        }
        return $this->query;
    }
    /**
     * @desc Delete Sphinx data
     * @param int $id
     * @return bool $res
     */
    public function delete($id)
    {
        if (!$this->use_sphinx) return true;
        try
        {
            $query = SphinxQL::create($this->conn)->delete()->from($this->index)->where("id", (int) $id);
            $this->log->log(get_class($this)." - Delete: ".print_r($query,1),LOG_DEBUG);
            $res = $query->execute();
            return $res;
        } catch (Exception $e){
            global $debug;
            $debug->collect($e);
            $this->log->log(get_class($this)." - Delete Error: ".print_r($e,1),LOG_ERR);
            return false;
        }
        return true;
    }
    /**
     * @desc Delete All Sphinx data
     * @return bool $res
     */
    public function reset_all()
    {
        if (!$this->use_sphinx) return true;
        try
        {
            $query = SphinxQL::create($this->conn)->delete()->from($this->index)->where("id",">",0);
            $res = $query->execute();
            $this->log->log(get_class($this)." - Delete All: ".print_r($query,1),LOG_DEBUG);
            return $res;
        } catch (Exception $e){
            global $debug;
            $debug->collect($e);
            $this->log->log(get_class($this)." - Delete All Error: ".print_r($e,1),LOG_ERR);
            return false;
        }
        return true;
    }
    private function begin()
    {
        if (!$this->use_sphinx) return true;
        $this->trans = SphinxQL::create($this->conn)->transactionBegin();
        $this->transaction = 1 ;
        return ($this->trans);
    }
    private function commit()
    {
        if (!$this->use_sphinx) return true;
        $this->trans->transactionCommit();
    }
    private function rollback()
    {
        if (!$this->use_sphinx) return true;
        $this->trans->transactionRollback();
    }
    /**
     * @desc Count the number of result of a query
     * @return int $count
     */
    public function count_result()
    {
        if (!$this->use_sphinx) return true;
        foreach($this->meta as $key=>$val)
        {
            if ($val["Variable_name"] == "total_found")
                return $val['Value'];
        }
        return 0;
    }
    /**
     * @desc Make a snippet of search result
     * @param int $page_id
     * @param string $search_term
     * @param int $limit
     */
    public function snippet($page_id,$search_term,$limit=200)
    {
        if (!$this->use_sphinx) return true;
        load_alternative_class('class/page.class.php');
        $page = new page($this->db);
        $page->fetch($page_id);

        $search_term = preg_split('/ /', $search_term);
        foreach($search_term as $key => $val)
        {
            $search_term[$key]='*'.$val.'*';
        }
        $search_term=join('|',$search_term);
        $query = "CALL SNIPPETS('".addslashes($page->get_generated_formated_content())."',
                                '".$this->index."',
                                '".addslashes($search_term)."',
                                5 AS around,
                                ".$limit." AS limit)";
        $this->query = SphinxQL::create($this->conn)->query($query);
        $r = $this->query->execute();
        $this->log->log(get_class($this)." - Snippets: ".print_r($this->query,1),LOG_DEBUG);

        //var_dump($r);
        return ($r[0]['snippet']);

    }
    /**
     * @desc Get metadata of a search result
     * @param string $meta
     */
    public function get_meta()
    {
        if (!$this->use_sphinx) return true;
        return $this->meta;
    }
}
?>
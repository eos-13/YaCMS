<?php
class common_object
{
    protected $db;
    private $cache = "file";
    public $log;

    public function __construct($db)
    {
        $this->db = $db;
        if (function_exists('apc_add')) { $this->set_cache('apc'); }
        elseif (function_exists( 'memcache_set' ) ) { $this->set_cache('memcache'); }
        global $log;
        $this->log = $log;
    }
    protected function set_cache($cache)
    {
        $this->cache = $cache;
    }
    protected function get_cache()
    {
        return $this->cache;
    }
    protected function update_field($field,$value)
    {
        $requete = "UPDATE `".$this->table."`
                       SET `".$field. "` = '".addslashes($value)."'
                      WHERE id = ".$this->id;
        if ($value == 'now')
        {
            $requete = "UPDATE `".$this->table."`
                       SET `".$field. "` = now()
                      WHERE id = ".$this->id;
        }
        if ($value == 'null')
        {
            $requete = "UPDATE `".$this->table."`
                       SET `".$field. "` = null
                      WHERE id = ".$this->id;
        }
        $sql = $this->db->query($requete);
        if ($sql)
        {
            global $trigger,$current_user;
            $trigger->run_trigger("UPDATE_".strtoupper(get_class($this))."_".strtoupper($field),$this,$current_user);
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        return $sql;
    }
    public function refresh_sitemap()
    {
        load_alternative_class('class/sitemap.class.php');
        $s = new sitemap($this->db);
        $s->store();
    }
    public function make_list($db,$table,$sort=false,$filter=false,$id='id',$name='name') {
        $this->db = $db;
        $requete = "SELECT *
                      FROM ".$table."
                     WHERE 1=1 ";
        if ($filter){
            foreach ($filter as $key=>$val){
                $requete .= " AND ".$key." IN (".join(',',$val).")";
            }
        }
        if ($sort) $requete .= " ORDER BY ".$sort;
        $sql = $this->db->query($requete);
        while($res = $this->db->fetch_object($sql))
        {
            $this->list[$res->$id]=$res->$name;
        }
        return $this->list;
    }
}
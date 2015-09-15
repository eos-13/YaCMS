<?php

class sitemap
{
    protected $db;
    private $dom;
    private $root;
    private $urllist;
    public $log;

    public function __construct($db)
    {
        global $log;
        $this->log = $log;
        $this->db= $db;
        $this->dom = new DOMDocument('1.0', 'utf-8');
        $this->dom->formatOutput = true;
        $this->root = $this->dom->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9','urlset');
        $this->build();
    }
    public function build()
    {
        $this->log->log(get_class($this)." - Rebuild Sitemap",LOG_INFO);
        $requete = "SELECT DISTINCT page.id,
                           page.url,
                           ifnull(priority,'0.5') as priority,
                           date_format(page.date_derniere_maj,'%Y-%m-%d') as lastmod,
                           ifnull(sitemapFreq.name,'daily') as changeFreq
                      FROM group_publication,
                           group_publication_page,
                           page
                 LEFT JOIN sitemapFreq ON sitemapFreq.id = page.changeFreq_refid
                     WHERE page.active = 1
                       AND group_publication_page.page_refid = page.id
                       AND group_publication.id = group_publication_page.`group_publication_refid`
                       AND exclude_sitemap = 0";
        $sql =$this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        while($res = $this->db->fetch_object($sql))
        {
            if ($this->publi_granted($res))
            {
                global $conf;
                $url = $conf->main_url_root.(preg_match('/\/$/',$conf->main_url_root)?"":"/").$res->url;
                $tmp = $this->dom->createElement('url');
                $tmp->appendChild(new DOMElement('loc',$url));
                $tmp->appendChild(new DOMElement('lastmod',$res->lastmod));
                $tmp->appendChild(new DOMElement('changefreq',$res->changeFreq));
                $tmp->appendChild(new DOMElement('priority',preg_replace('/,/','.',round($res->priority,2))));
                $this->root->appendChild($tmp);
                $this->urllist[]=$url;
            }
        }
        $this->dom->appendChild($this->root);
        $this->log->log(get_class($this)." - Rebuild Sitemap - Generation end",LOG_INFO);
    }
    private function publi_granted($res)
    {
        global $conf;
        $id = $res->id;
        $requete = "SELECT *
                      FROM group_publication,
                           group_publication_page
                     WHERE group_publication.id = group_publication_page.`group_publication_refid`
                       AND group_publication_page.page_refid = ".$id;
        $sql = $this->db->query($requete);
        while ($res = $this->db->fetch_object($sql))
        {
            if ($res->is_public == 1) return true;
        }
        if ($conf->sitemap_add_restricted_page == "on")
        {
            return true;
        } else {
            return false;
        }
    }
    public function store()
    {
        global $conf;
        $file=$conf->main_document_root. "/sitemap.xml";
        file_put_contents($file, $this->dom->saveXML());
        if ( is_array($this->urllist) && count($this->urllist) > 0)
        {
            $urllist = join("\n",$this->urllist);
            file_put_contents($conf->main_document_root. "/urllist.txt",$urllist);
        }
        $this->log->log(get_class($this)." - Rebuild Sitemap - Done !",LOG_INFO);
    }
}
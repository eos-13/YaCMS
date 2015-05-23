<?php
class social
{
    protected $db;
    private $secure;

    private $html;
    private $js = array();
    private $js_code = array();
    private $js_external=array();

    public $name;
    public $desc;

    public function __construct($db)
    {
        $this->db = $db;
        if ($this->isSecure()) $this->secure = true;
    }
    public function get_js()
    {
        $js = array_unique($this->js);
        return $js;
    }
    public function set_js($js)
    {
        $this->js[]=$js;
    }
    public function get_js_code()
    {
        $js_code = array_unique($this->js_code);
        return $js_code;
    }
    public function set_js_code($js)
    {
        $this->js_code[]=$js;
    }
    public function get_js_external()
    {
        $js_external = array_unique($this->js_external);
        return $js_external;
    }
    public function set_js_external($js_external)
    {
        $this->js_external[]=$js_external;
    }
    public function set_html($html)
    {
        $this->html .= $html;
    }
    public function get_html()
    {
        return $this->html;
    }

    private function isSecure()
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) &&  $_SERVER['SERVER_PORT'] == 443);
    }
    public function generate($p)
    {
        $need_social = "";
        if (is_object($p))
            $need_social = $p->get_social_media();

        if ($need_social."x" != "x")
        {
            global $conf;
            $requete = "SELECT *
                          FROM social_media
                         WHERE id IN (". join(',',json_decode($need_social)) .")";
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->html="<div id='social'>";
                while($res = $this->db->fetch_object($sql))
                {
                    if (method_exists($this, $res->name) )
                    {
                        $this->html .= "<div id='social_".$res->name."'>";
                        $func = $res->name;
                        $this->name = $p->get_title();
                        $this->desc = $p->get_title();
                        $this->$func(preg_replace('/\/$/','',$conf->main_url_root)."/". $p->get_url());
                        $this->html .= "</div>";
                    }
                }
                $this->html.="</div>";
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
    public function facebook($url)
    {

        $html = '<div id="fb-root"></div>';
        $js   = <<<EOF
            jQuery(document).ready(function(){
                (function(d, s, id)
                {    var js, fjs = d.getElementsByTagName(s)[0];
                     if (d.getElementById(id)) return;
                     js = d.createElement(s); js.id = id;
                     js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.0";
                     fjs.parentNode.insertBefore(js, fjs);
                }(document, "script", "facebook-jssdk"));
            });
EOF;
        $html .= '<div class="fb-like" data-href="'.$url.'" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>';
        $this->set_html($html);
        $this->set_js_code($js);
    }
    public function pinterrest()
    {
        $html = '<a href="//fr.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark"  data-pin-shape="round" data-pin-height="32"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_round_red_32.png" /></a>';
        $js_external = 'http://assets.pinterest.com/js/pinit.js';
        $this->set_html($html);
        $this->set_js_external($js_external);
    }
    public function linkedin($url)
    {
        $html = '<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: fr_FR</script>';
        $html .= '<script type="IN/Share" data-url="'.$url.'" data-counter="right"></script>';
        $this->set_html($html);
    }
    public function tweeter($url)
    {
        $html = '<a class="twitter-share-button"';
        $html .= '        data-size="medium"';
        $html .= '        data-count="horizontal"';
        $html .= '        data-count="horizontal"';
        $html .= '        data-url="'.$url.'"';
        $html .= '        data-text="Checking out this page"';
        $html .= '        href="https://twitter.com/share">';
        $html .= '        Tweet';
        $html .= '        </a>';
        $js = 'jQuery(document).ready(function(){ ';
        $js .= '        window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));';
        $js .= '});';
        $this->set_html($html);
        $this->set_js_code($js);
    }

    public function flickr($url)
    {
        global $conf;
        $html = "";
        if (isset($conf->flicker_account) && $conf->flicker_account."x" != "x")
        {
            $html.= '<a href="http://www.flickr.com/photos/'.$conf->flicker_account.'/" title="Regardez mes photos sur Flickr !"><img src="https://s.yimg.com/pw/images/goodies/white-flickr.png" width="56" height="26" alt=""></a>';
            $this->set_html($html);
        }
    }
    public function foursquare($url)
    {
        $html = "";
        $html .= '<div style="display:none;" class="vcard">';
        $html .= ' <a class="url fn" href="'.$url.'">'.$url.'</a>';
        $html .= '</div>';
        $html .= '<a href="https://foursquare.com/intent/venue.html" class="fourSq-widget" data-variant="wide">Foursquare</a>';
        $js = 'jQuery(document).ready(function(){ ';
        $js .= '(function() {';
        $js .= '    window.___fourSq = {};';
        $js .= '    var s = document.createElement("script");';
        $js .= '    s.type = "text/javascript";';
        $js .= '    s.src = "http://platform.foursquare.com/js/widgets.js";';
        $js .= '    s.async = true;';
        $js .= '    var ph = document.getElementsByTagName("script")[0];';
        $js .= '    ph.parentNode.insertBefore(s, ph);';
        $js .= '})();';
        $js .= '});';
        $this->set_html($html);
        $this->set_js_code($js);
    }
    public function google_plus($url)
    {
        $html = "";
        $html = '<div class="g-plusone" data-href="'.$url.'"></div>';

        if ($this->secure)
        {
            $html .= '<script src="https://apis.google.com/js/platform.js" async defer>';
        } else {
            $html .= '<script src="http://apis.google.com/js/platform.js" async defer>';
        }
        $html .= '  {lang: "fr"}';
        $html .= '</script>';

        $this->set_html($html);
    }
    public function tumblr($url)
    {
        $html = "";
        $name = $this->name;
        $desc = $this->desc;
        $html = '<a href="http://www.tumblr.com/share/link?url='.urlencode($url).'&name='.urlencode($name).'&description='.urlencode($desc).'"
                    title="Share on Tumblr"
                    style="display:inline-block; text-indent:-9999px; overflow:hidden; width:20px; height:20px; background:url(\'https://platform.tumblr.com/v1/share_4.png\') top left no-repeat transparent;"
                >
                    Share on Tumblr
                </a>';
        $this->set_html($html);
    }

    public function instagram($url)
    {
        $html = "";
        global $conf;
        if (isset($conf->instagram_account) && $conf->instagram_account."x" != "x")
        {
            $html = '<style>.ig-b- { display: inline-block; } .ig-b- img { visibility: hidden; } .ig-b-:hover { background-position: 0 -60px; } .ig-b-:active { background-position: 0 -120px; } .ig-b-24 { width: 24px; height: 24px; background: url(//badges.instagram.com/static/images/ig-badge-sprite-24.png) no-repeat 0 0; } @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) { .ig-b-24 { background-image: url(//badges.instagram.com/static/images/ig-badge-sprite-24@2x.png); background-size: 60px 178px; } }</style>';
            $html .= '<a href="http://instagram.com/'.$conf->instagram_account.'?ref=badge" class="ig-b- ig-b-24"><img src="//badges.instagram.com/static/images/ig-badge-24.png" alt="Instagram" /></a>';
            $this->set_html($html);
        }
    }
}
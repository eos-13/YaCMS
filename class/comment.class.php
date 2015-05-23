<?php
class comment extends common_object
{
    protected $db;
    private $pId;
    private $pTitle;
    private $pContent;
    private $pDate_creation;
    private $pPage_refid;
    private $pAuthor;
    private $pAuthor_refid;
    private $pValid;

    public $id;
    public $title;
    public $content;
    public $date_creation;
    public $page_refid;
    public $author;
    public $author_refid;
    public $valid;

    protected $table = "commentaire";

    /**
     *
     * @param int $id
     * @return boolean $result
     * @desc fetch the comment datas
     */
    public function fetch($id)
    {
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0){
            $res = $this->db->fetch_object($sql);
            $this->pId = $res->id;
            $this->id = $res->id;
            $this->pTitle = $res->title;
            $this->pContent = $res->content;
            $this->pDate_creation = $res->date_creation;
            $this->pPage_refid = $res->page_refid;
            $this->pAuthor = $res->author;
            $this->pAuthor_refid = $res->author_id;
            $this->pValid = $res->valid;
            return $res->id;
        } else {
            return false;
        }
    }
    /**
     * @desc create a new comment
     * @return int $id
     */
    public function create()
    {
        $requete = "INSERT INTO ".$this->table."
                                (date_creation)
                                VALUES
                                (now())";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        return $this->db->last_insert_id($this->table);
    }
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->pId;
            $this->title = $this->pTitle;
            $this->content = $this->pContent;
            $this->date_creation = $this->pDate_creation;
            $this->page_refid = $this->pPage_refid;
            $this->author = $this->pAuthor;
            $this->author_refid = $this->pAuthor_refid;
            $this->valid = $this->pValid;
            return $this;
        } else {
            return false;
        }
    }
    /**
     *
     * @return int $result
     * @desc return the comment id
     */
    public function get_id()
    {
        if ($this->pId > 0)
            return $this->pId;
        else
            return false;
    }
    /**
     *
     * @return datetime $result
     * @desc return the date of creation of a comment
     */
    public function get_date_creation()
    {
        if ($this->get_id() > 0)
            return $this->pDate_creation;
        else
            return false;
    }
    /**
     * @desc return the title of a comment
     * @return string $title
     */
    public function get_title()
    {
        if ($this->get_id() > 0)
            return $this->pTitle;
        else
            return false;
    }
    /**
     * @desc Return the content of a content
     * @return string $content
     */
    public function get_content()
    {
        if ($this->get_id() > 0)
            return $this->pContent;
        else
            return false;
    }
    /**
     * @desc get the page of the comment
     * @return int $page_refid
     */
    public function get_page_refid()
    {
        if ($this->get_id() > 0)
            return $this->pPage_refid;
        else
            return false;
    }
    /**
     * @desc Get the author name
     * @return string $author
     */
    public function get_author()
    {
        if ($this->get_id() > 0)
            return $this->pAuthor;
        else
            return false;
    }
    /**
     * @desc Get author SQL id
     * @return int $author_refid
     */
    public function get_author_refid()
    {
        if ($this->get_id() > 0)
            return $this->pAuthor_refid;
        else
            return false;
    }
    /**
     * @desc get the author complete name
     * @return string $author_html
     */
    public function get_author_html()
    {
        if ($this->get_id() > 0)
        {
            if ($this->pAuthor_refid > 0)
            {
                $u = new user($this->db);
                $u->fetch($this->pAuthor_refid);
                return $u->get_firstname()." ".$u->get_name();
            } else {
                return $this->get_author();
            }
        } else {
            return false;
        }
    }
    /**
     * @desc validation level of the comment
     * @return bool $valid
     */
    public function get_valid()
    {
        if ($this->get_id() > 0)
            return $this->pValid;
        else
            return false;
    }
    /**
     * @desc Set the title of the comment
     * @param string $title
     * @return boolean $result
     */
    public function set_title($title)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('title',$title);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @desc Set the author of a comment
     * @param string $author
     * @return boolean $res
     */
    public function set_author($author)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('author',$author);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @desc set an author id
     * @param int $author_refid
     * @return boolean $res
     */
    public function set_author_refid($author_refid)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('author_id',$author_refid);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @desc set the page of the comment
     * @param int $page_refid
     * @return boolean $res
     */
    public function set_page_refid($page_refid)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('page_refid',$page_refid);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @desc Set the content of a comment
     * @param string $content
     * @return boolean $res
     */
    public function set_content($content)
    {
        if ($this->get_id() > 0){
            $ret = $this->update_field('content',$content);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @desc set the validation level of a comment
     * @param int $valid
     * @return boolean $result
     */
    public function set_valid($valid)
    {
        if ($this->get_id() > 0)
        {
            if (empty($valid) || !$valid || is_null($valid)) {
                $valid = "0";
            }
            $ret = $this->update_field('valid',$valid);
            return $ret;
        } else {
            return false;
        }
    }
    /**
     * @desc Add a new comment
     * @return boolean $res
     */
    public function add()
    {
        $requete="INSERT INTO commentaire
                              (date_creation)
                       VALUES (now())";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $ret = $this->db->last_insert_id($this->table);
        if ($ret > 0){
            $this->fetch($ret);
        }
        return $ret;
    }
    /**
     * @desc Delete a comment
     * @return bool
     */
    public function del()
    {
        $requete = "DELETE FROM ".$this->table."
                          WHERE id = ".$this->id;
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        $ret =  ($sql);
        return $ret;
    }
    /**
     * @desc Get the form to post comment
     * @param int $page_refid
     * @param string $comment_response
     * @return string $res
     */
    public function get_comment_block($page_refid,$comment_response=false)
    {
        global $conf,$current_user;
        $html = "";
        if (!$comment_response)
        {
            if ($conf->anonymous_can_post_comment == "on" || ($conf->anonymous_can_post_comment!= "on" && $current_user->get_id() > 0) )
            $html = "<form id='comment_form' action='?action=comment' method='post'>";
            $rem = "";
            $rowspan=5;
            if ($current_user->get_id() > 0)
            {
                $rowspan--;
            } else {
                $rem .= "<tr><th>Auteur</th><td><input required type='text' name='author' /></tr>";
            }

            $html .= "<table>";
            if ($conf->reCaptcha_key."x" != "x")
                $html .= '<tr><td rowspan="'.$rowspan.'"><div class="g-recaptcha" data-sitekey="'.$conf->reCaptcha_key.'"></div></td></tr>';
            global $current_user;
            $html .= $rem;
            $html .= "<tr><th>Titre</th><td><input required type='text' name='title' /></tr>";
            $html .= "<tr><th colspan=2>Commentaire</th></td>";
            $html .= "<tr><td colspan=2><textarea id='comment_textarea' name='comment'></textarea></td></tr>";
            $html .= "</table>";
            $html .= "<button>Comment</button>";
            $html .= "</form>";
        } else if ($comment_response =="KO"){
            $html = "<h3>Votre commentaire n'a pas été pris en compte</h3>";
        } else if ($comment_response == "OK") {
            if ($conf->moderation_type == "on"){
                $html = "<h3>Votre commentaire a pas été pris en compte. Il sera visible après modération par nos équipes</h3>";
            } else {
                $html = "<h3>Votre commentaire n'a pas été pris en compte</h3>";
            }
        }
        $comments = $this->get_comments_for_page($page_refid);
        $html .= ($comments?$comments:"");
        return $html;
    }
    /**
     * @desc Get all comment for a page
     * @param int $page_refid
     * @return string $res
     */
    protected function get_comments_for_page($page_refid)
    {
        $html = "";
        if ($page_refid > 0)
        {
            $requete = "SELECT *
                          FROM commentaire
                         WHERE page_refid = ".$page_refid."
                           AND valid > 0
                      ORDER BY id desc";
            $sql = $this->db->query($requete);
            if ($sql && $this->db->num_rows($sql) > 0)
            {
                while($res = $this->db->fetch_object($sql))
                {
                    $author = $res->author;
                    if ($res->author_id && $res->author_id > 0)
                    {
                        $user = new user($this->db);
                        $ret = $user->fetch($res->author_id);
                        if ($res && $user->get_name()." ".$user->get_firstname()."x" != " x" )
                        {
                            $author = $user->get_firstname()." ".$user->get_name();
                        }
                    }
                    $html .= "<section>";
                    $html .= "<table width=400 border=1 style='border-collapse: collapse;' CELLPADDING=4>";
                    $html .= "<tr><th colspan=2>".$res->title."</th></tr>";
                    $html .= "<tr><th><small>Par:</small></th><td><small>".$author."</small></td></tr>";
                    $html .= "<tr><th colspan=2>Commentaire:</th>";
                    $html .= "<tr><td colspan=2>".$res->content."</td>";
                    $html .= "</table>";
                    $html .= "</section><br/>";
                }
                return $html;
            } else {
                return "<b>Be the first to comment !</b>";
            }
        } else {
            return false;
        }
    }
    /**
     * @desc Verify captcha answer
     * @param string $g_recaptcha_response
     * @param string $remote_ip
     * @return bool $res
     */
    public function verify_captcha_response($g_recaptcha_response,$remote_ip)
    {
        global $conf;
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = "secret=".$conf->reCaptcha_key_private."&response=".$g_recaptcha_response."&remoteip=".$remote_ip;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST ,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); /* obey redirects */
        curl_setopt($ch, CURLOPT_HEADER, 0);  /* No HTTP headers */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  /* return the data */

        $result = curl_exec($ch);
        $r = json_decode($result);
        curl_close($ch);
        return $r->success;
    }
    /**
     * @desc verify akismet key
     * @return boolean $res
     */
    public function verify_akismet_key()
    {
        global $conf;
        // Call to verify key function
        return $this->akismet_verify_key($conf->akismet_api_key, $conf->main_url_root);
        // Authenticates your Akismet API key

    }
    /**
     * @desc Verify key process
     * @param string $key
     * @param string $blog
     * @return bool $res
     */
    private function akismet_verify_key( $key, $blog )
    {
        global $conf;
        $blog = urlencode ( $blog );
        $request = 'key=' . $key . '&blog=' . $blog;
        $host = $http_host = 'rest.akismet.com';
        $path = '/1.1/verify-key';
        $port = 80;
        $akismet_ua = $conf->user_agent." | Akismet/2.5.9";
        $content_length = strlen ( $request );
        $http_request = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';
        if (false != ($fs = @fsockopen ( $http_host, $port, $errno, $errstr, 10 )))
        {
            fwrite ( $fs, $http_request );
            while ( ! feof ( $fs ) )
                $response .= fgets ( $fs, 1160 ); // One TCP-IP packet
            fclose ( $fs );
            $response = explode ( "\r\n\r\n", $response, 2 );
        }
        if ('valid' == $response [1])
            return true;
        else
            return false;
    }
    /**
     * @desc Check if comment is spam or not
     * @return boolean $res
     */
    public function check_spam_comment()
    {
        global $conf,$current_user;
        $remote_ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:false);
        if (!$remote_ip) { $remote_ip = $_SERVER['REMOTE_ADDR'];}
        // Call to comment check
        $author = $post['author'];
        if ($current_user->get_id()>0)
        {
            $author = $current_user->get_firstname()." ".$current_user->get_name();
        }
        $data = array(
                'blog' => $conf->main_url_root,
                'user_ip' => $remote_ip,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'referrer' => $_SERVER['HTTP_REFERER'],
                'permalink' => curPageURL(),
                'comment_type' => 'comment',
                'comment_author' => $post['author'],
                'comment_author_email' => false,
                'comment_author_url' => false,
                'comment_content' => $post['comment']);
        return $this->akismet_comment_check( $conf->akismet_api_key, $data );

    }
    /**
     * @desc process part of spam check in comment
     * @param string $key
     * @param string $data[]
     * @return boolean $res
     */
    function akismet_comment_check( $key, $data )
    {
        global $conf;
        $request = 'blog='. urlencode($data['blog']) .
        '&user_ip='. urlencode($data['user_ip']) .
        '&user_agent='. urlencode($data['user_agent']) .
        '&referrer='. urlencode($data['referrer']) .
        '&permalink='. urlencode($data['permalink']) .
        '&comment_type='. urlencode($data['comment_type']) .
        '&comment_author='. urlencode($data['comment_author']) .
        '&comment_author_email='. urlencode($data['comment_author_email']) .
        '&comment_author_url='. urlencode($data['comment_author_url']) .
        '&comment_content='. urlencode($data['comment_content']);
        $host = $http_host = $key.'.rest.akismet.com';
        $path = '/1.1/comment-check';
        $port = 80;
        $akismet_ua = $conf->user_agent." | Akismet/2.5.9";
        $content_length = strlen( $request );
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';
        if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {

            fwrite( $fs, $http_request );

            while ( !feof( $fs ) )
                $response .= fgets( $fs, 1160 ); // One TCP-IP packet
            fclose( $fs );

            $response = explode( "\r\n\r\n", $response, 2 );
        }
        if ( 'true' == $response[1] || "invalid" == $response[1] )
            return false;
        else
            return true;
    }
    /**
     * @desc Tell this comment is spam to the community
     * @param string $in
     * @return boolean $res
     */
    public function send_as_spam($in)
    {
        global $conf;
        // Call to comment check
        $data = array(
                'blog' => $conf->main_url_root,
                'user_ip' => $remote_ip,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'referrer' => $_SERVER['HTTP_REFERER'],
                'permalink' => curPageURL(),
                'comment_type' => 'comment',
                'comment_author' => $in['author'],
                'comment_author_email' => false,
                'comment_author_url' => false,
                'comment_content' => $in['comment']);
        return $this->akismet_submit_spam( $conf->akismet_api_key, $data );
    // Passes back true (it's spam) or false (it's ham)
    }
    /**
     * @desc private process of reporting spam
     * @param string $key
     * @param string $data
     * @return boolean $res
     */
    private function akismet_submit_spam( $key, $data )
    {
        global $conf;
        $request = 'blog='. urlencode($data['blog']) .
        '&user_ip='. urlencode($data['user_ip']) .
        '&user_agent='. urlencode($data['user_agent']) .
        '&referrer='. urlencode($data['referrer']) .
        '&permalink='. urlencode($data['permalink']) .
        '&comment_type='. urlencode($data['comment_type']) .
        '&comment_author='. urlencode($data['comment_author']) .
        '&comment_author_email='. urlencode($data['comment_author_email']) .
        '&comment_author_url='. urlencode($data['comment_author_url']) .
        '&comment_content='. urlencode($data['comment_content']);
        $host = $http_host = $key.'.rest.akismet.com';
        $path = '/1.1/submit-spam';
        $port = 80;
        $akismet_ua = $conf->user_agent." | Akismet/2.5.9";
        $content_length = strlen( $request );
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';
        if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {

            fwrite( $fs, $http_request );

            while ( !feof( $fs ) )
                $response .= fgets( $fs, 1160 ); // One TCP-IP packet
            fclose( $fs );

            $response = explode( "\r\n\r\n", $response, 2 );
        }

        if ( 'Thanks for making the web a better place.' == $response[1] )
            return true;
        else
            return false;
    }
}
<?php
require_once(__DIR__.'/../lib/imap/Imap.php');
class imap_client extends Imap
{
    public $db;
    public $imap;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function init($mailbox, $username, $password, $encryption = false) {
        $enc = '';
        if($encryption!=null && isset($encryption) && $encryption=='ssl')
            $enc = '/imap/ssl/novalidate-cert';
        else if($encryption!=null && isset($encryption) && $encryption=='tls')
            $enc = '/imap/tls/novalidate-cert';
        $this->mailbox = "{" . $mailbox . $enc . "}";

        $this->imap = @imap_open($this->mailbox, $username, $password);
    }

    public function connect()
    {
        global $conf;
        if ($conf->use_imapservice != "on") return 0;
        $mailbox = $conf->imap_server;
        $username = $conf->imap_user;
        $password = $conf->imap_pass;
        $encryption = $conf->imap_encryption;
        $this->init($mailbox, $username, $password, $encryption);
        if($this->isConnected()===false)
            die($this->getError());
        $this->selectFolder('INBOX');
        $overallMessages = $this->countMessages();
        $unreadMessages = $this->countUnreadMessages();
        $emails = $this->getMessages();
        if (is_array($emails) && count($emails) > 0)
        {
            foreach($emails as $key=>$email)
            {
                //var_dump($email);
                $from = $email['from'];
                $date = date('Y-m-d H:i:s', strtotime($email['date']));
                $subject = $email['subject'];
                if ($email['html'])
                    $body = htmlentities($email['body']);
                else
                    $body = $email['body'];
                $requete = "INSERT INTO external_msg
                                        (user_mail,external_msg,title,date_create)
                                 VALUES ('".addslashes($from)."','".addslashes($body)."','".addslashes($subject)."','".addslashes($date)."')";
                //print $requete;
                $sql = $this->db->query($requete);
                if ($sql)
                    $this->deleteMessage($emails[$key]['uid']);
            }
        }
        // delete second message
//        $imap->deleteMessage($emails[1]['id']);
    }
    public function isConnected() {
        return $this->imap !== false;
    }
    public function getFolders() {
        $folders = imap_list($this->imap, $this->mailbox, "*");
        return str_replace($this->mailbox, "", $folders);
    }
}
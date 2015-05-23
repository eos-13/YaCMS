<?php
class mail
{
    public $log;
    public function __construct()
    {
        global $log;
        $this->log = $log;
    }
    public function send_email($subject,$message,$to,$from,$reply_to=false,$cc=false)
    {
        if (!$reply_to) $reply_to = $from;
        $headers = "From: " . strip_tags($from) . "\r\n";
        if ($reply_to)
            $headers .= "Reply-To: ". strip_tags($reply_to) . "\r\n";
        if ($cc)
        {
            if (is_array($cc))
            {
                $headers .= "CC: ".strip_tags(join(',',$cc))."\r\n";
            } else {
                $headers .= "CC: ".strip_tags($cc)."\r\n";
            }
        }
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        global $trigger,$current_user;
        $trigger->run_trigger("SEND_MAIL", $this,$current_user);
        return @mail($to, $subject, $message, $headers);
    }
}
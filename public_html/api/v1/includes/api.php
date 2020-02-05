<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';


class API {
    var $db = null;
    
    function __construct($db) {
        $this->db = $db;
    }
    
    // https://github.com/PHPMailer/PHPMailer 
    function sendEmail($to, $subject) {
        if (empty($to)) 
            return false;
        if (empty($subject))
            return false;
            
        $message = '<br>'; // Show WebsMaking logo in the email.
        foreach ($_POST as $var => $val) {
            if ($var == 'token' || $var == 'secret' || $var == 'to')
                continue;
            $message .= $this->protectxss($var).':'.$this->protectxss($val).'<br>';
        }
        
        $mail = new PHPMailer(true);
    
        try {
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER; 
            $mail->Host       = 'websmaking.com';
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'websmak1'; 
            $mail->Password   = 'lxJz0D[EA!346s';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 290; 
            
            $mail->setFrom('noreply@websmaking.com', 'WebsMaking.com');
            $mail->addAddress($to, ''); 
            
            $mail->CharSet = 'UTF-8';
            $mail->Encoding ='base64';
            
            $mail->isHTML(true); 
            $mail->Subject = $subject;//mb_convert_encoding($subject, mb_detect_encoding($subject)); 
            $mail->Body    = $message;//mb_convert_encoding($message, mb_detect_encoding($message));
            $mail->AltBody = $message;
            
            return $mail->send();
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
        return false;
    }
    
    // TODO: Make it works..
    function searchQuery($query, $siteid) {
        $query = (isset($query) ? $query : null);
        if (is_null($query)) return null;
        if (empty($query)) return null;
        
        $result = $this->db->prepare('SELECT * FROM `buildp_pages` WHERE siteID=?');
        $result->execute(array($siteid));
        $results = $result->fetchAll();
        
        $arr = array();
        foreach (explode(' ', $query) as $val) {
            for ($i=0; $i<count($results); $i++) {
                $result = $results[$i];
                
                if (preg_match('/^['.$val.']$/', $result['text']))
                    $arr .= $result;
            }
        }
        
        return $arr;
    }
    
    function protectxss($str) {
        return htmlspecialchars(htmlentities(stripslashes(rtrim($str)), ENT_QUOTES ), ENT_QUOTES);
    }
    
    
}

?>
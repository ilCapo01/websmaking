<?php

class Auth {
    var $db = null;
    
    function __construct($db = null) {
        $this->db = $db;
    }
    
    function authenticate($token, $secret) {
        $login = $this->db->prepare('SELECT `token`, `secret`, `lastlogin`, `id` FROM `api_access` WHERE token=? AND secret=?');
        $login->execute(array($token, $secret));
        $login = $login->fetch();
        
        if ($login != false/* && $login->rowCount() > 0*/) {
            $lastlogin = (!empty($login['lastlogin']) ? $login['lastlogin'] : 0);
            
            $diff = floor( (time()-$lastlogin) / (1) );
            if ($diff <= 15) {
                echo 'Too many requests too frequently.';
                return false;
            }else {
                // Generate hash and insert it into the db, before generating new hash check if there's an hash has been generated already (Delete hashes older than 15 min).
                
                $this->db->prepare('UPDATE `api_access` SET lastlogin=? WHERE id=?')->execute(array(time(), $login['id']));
            
                // Insert to db and save timestamp too.
                //$hash = $this->generateHash($token, $secret);
                return true;
            }
        }
        return false;
    }
    
    private function generateHash($token, $secret) {
        return hash('sha256', time().':'.$token.':'.md5($secret));
    }
    
    private function validateHash($token, $secret, $time, $hash) {
        $nHash = hash('sha256', $time.':'.$token.':'.md5($secret));
        return ($nHash == $hash);
    }
    
}


?>
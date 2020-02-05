<?php

// Collect data about visitors.
class Stalker {
    
    var $ref;
    
    function __construct() {
        $this->ref = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
    }
    
    function getReferer() {
        $ref = &$this->ref;
        
        $sites = array('google.com', 'duckduckgo.com', 'yandex.com', 'yahoo.com', 'reddit.com');
        
        
        if (!is_null($ref)) {
            if (preg_match('#\b('.implode($sites, '|').')\b#', $ref)) {
                return $ref;
            }
        }
        return false;
    }
    
    function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }
    
    // Get ip
    function getClientIP() {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            return (isset($_SERVER[$key]) ? $_SERVER[$key] : null);
        }
        return null;
    }
    
    
}

$stalker = new Stalker;
?>
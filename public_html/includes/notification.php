<?php

class Notification {
    
    var $db; // Connection to database.
    
    var $id; // int 
    var $title; // string
    var $message; // string 
    var $userID; // int
    var $isRead; // bool
    var $time; // long
    
    function __construct($db = null) {
        $this->db = $db;
        
        // Load all data from db to global variables in this class.
    }
    
    function markAsRead() {
        // Only the specific notification.
    }
    
    function getNotification() {
        return '
            <style type="text/style">
            
            </style>
            <span class="fa fa-close">X</span>
            <h2>'.$this->title.'</h2><br>
            <span>'.date('H:i:s d/m/Y', $this->time).'</span>
            <p>'.$this->message.'</p>
        ';
    }
    
    function toString() {
        return "";
    }
}

$noti = new Notification;
?>
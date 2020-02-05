<?php

class User {
  var $db = null;

  function __construct($db) {
    $this->db = $db;
  }
	
  function getGroup($userID) {
	$q1 = $this->db->prepare('SELECT * FROM `buildp_users` WHERE id=?');
    $q1->execute(array($userID));
    $user = $q1->fetch();
	return $user['group'];
  }

  function doesUserExist($username, $password) {
    $q1 = $this->db->prepare('SELECT * FROM `buildp_users` WHERE username=?'); 
    $q1->execute(array($username));
    $login = $q1->fetch();

    $postpass = hash('sha256', $login['salt'].md5($password));
    //strcmp($login['password'], $postpass)
    return ($login['password'] == $postpass);
  }

  function getUser($username) {
    $q1 = $this->db->prepare('SELECT * FROM `buildp_users` WHERE username=?');
    $q1->execute(array($username));
    return $q1->fetch();
  }

  function getUsernameByID($userID) {
    $q1 = $this->db->prepare('SELECT * FROM `buildp_users` WHERE id=?');
    $q1->execute(array($userID));
    return $q1->fetch()['username'];
  }

  // save hashed username + hashed password in COOKIE.
  function authenticateUser($username, $passwordInput) {
    $q1 = $this->db->prepare('SELECT * FROM `buildp_users` WHERE username=?'); 
    $q1->execute(array($username));
    $row = $q1->fetch();

    if ($q1->rowCount() > 0) {
      $password = hash('sha256', $row['salt'].md5($passwordInput));

      if ($row['password'] == $password) {
        $sess = hash('sha256', $row['username'] . $row['id'] . $row['password']).'-'.$row['id']; 
        
        srand(time());
        $this->db->prepare('INSERT INTO `buildp_sessions`(id,hash,userID,time) VALUES(?,?,?,?)')->execute(array(rand(10000, 99999), explode('-', $sess)[0], $row['id'], time()));
        
        setrawcookie('AUTHSESS', $sess, time()+3600*24);
        //$_SESSION['AUTHSESS'] = $sess;
        return true;
      }
    }
    return false;
  }

  function checkAuthentication() {
    $sess = (isset($_COOKIE['AUTHSESS']) ? $_COOKIE['AUTHSESS'] : null);
    if (is_null($sess))
      return false;
      
    $sess = explode('-', $_COOKIE['AUTHSESS']);
    //$sess = explode('-', $_SESSION['AUTHSESS']); /
    
    $q1 = $this->db->prepare('SELECT `username`, `id`, `password` FROM `buildp_users` WHERE id=?');
    $q1->execute(array($sess[1]));
    $row = $q1->fetch();
    
    $hash = hash('sha256', $row['username'] . $row['id'] . $row['password']); 
    
    if ($q1->rowCount() > 0) {
        
      if ($hash == $sess[0]/* && $this->checkHash($sess[0])*/)
        return true;
    }
    return false;
  }
  
  // TODO: Check under which circumstances it returns false, theres possibly a bug. 
  private function checkHash($hash) {
      $stmt = $this->db->prepare('SELECT * FROM `buildp_sessions` WHERE hash=?');
      $stmt->execute(array($hash));
      $data = $stmt->fetch();
      
      if ($data == false) 
          return false;
      
      $isLogged = ($data->rowCount() > 0 ? true : false);
      
      if ($isLogged) {
          $diff = floor((time()-$data[0]['time'])/60);
          if ($diff > 20) {
              $this->db->prepare('DELETE FROM `buildp_sessions` WHERE hash=?')->execute(array($hash));
              $this->logoutUser();
          }
          return $isLogged;
      }
      return $isLogged;
  }
  
  function logoutUser() {
    $this->db->prepare('DELETE FROM `buildp_sessions` WHERE hash=?')->execute(array( explode('-', $_COOKIE['AUTHSESS'])[0] ));
    //unset($_SESSION['AUTHSESS']); 
    unset($_SESSION['siteid']);
    unset($_COOKIE['AUTHSESS']);
    setcookie('AUTHSESS', $_COOKIE['AUTHSESS'], -1);
    session_destroy();
  }

}

?>

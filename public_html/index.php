<?php
ob_start();
//if (!isset($_SESSION)) session_start();

require_once(dirname(__FILE__).'/includes/config.php');
//require_once(dirname(__FILE__).'/includes/paypal/PaypalIPN.php');

//use Listener::PaypalIPN;

global $db, $user, $lang, $payment;


$userloggined = (isset($_COOKIE['AUTHSESS']) ? explode('-', $_COOKIE['AUTHSESS'])[1] : null);

/*$q4 = $db->query("SELECT * FROM `buildp_setting`");
$row = $q4->fetchAll();
for ($i=0; $i<sizeof($row); $i++){
	$c = $row[$i]; $logo = $c['logo']; $url = $c['url'];
}*/

$do = (isset($_GET['do']) ? $_GET['do'] : null);

// Load pages from DB.
$pages = $db->prepare('SELECT * FROM `buildp_mainPages` WHERE url=?');
$pages->execute(array($do));
$pages = $pages->fetchAll();

if ($do == 'login') $page_title = 'התחברות';
elseif ($do == 'register') $page_title = 'הרשמה';
elseif ($do == 'terms') $page_title = 'תקנון האתר';
elseif ($do == 'privacy') $page_title = 'מדיניות פרטיות';
else $page_title = $lang['page_title'];

// ****************
// * Header Layout 
// ****************
include 'includes/layouts/index_header.php';

echo '
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>WebsMaking - '.(count($pages) > 0 ? $pages[0]['title'] : $page_title).'</title>
    <meta name="keywords" content="בניית,אתרים,website,building,הקמת,אתר,פיתוח,מאפס,גמישות,נוחות,מערכת,CMS,ניהול,תוכן,וובסמייקינג,וובס מייקינג">
    <meta name="description" content="וובס מייקינג בניית אתרים בחינם. הקמת אתרים מקצועיים מותאמים לנייד באופן אוטומטי בנוחות וגמישות עם מערכת מתקדמת לניהול תוכן.">
  	<meta name="robots" content="index,follow">
  	<meta name="generator" content="WebsMaking (https://www.websmaking.com)" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=News+Cycle:400,700" rel="stylesheet">
	<link rel="shortcut icon" href="icon.png" type="image/x-icon" />
	<link rel="icon" href="icon.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="'.ABS_PATH.'vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="'.ABS_PATH.'fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="'.ABS_PATH.'fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="'.ABS_PATH.'vendor/animate/animate.css">	
	<link rel="stylesheet" type="text/css" href="'.ABS_PATH.'vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="'.ABS_PATH.'vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="'.ABS_PATH.'vendor/select2/select2.min.css">	
	<link rel="stylesheet" type="text/css" href="'.ABS_PATH.'vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="https://i.websmaking.com/css/util.css">
	<link rel="stylesheet" type="text/css" href="https://i.websmaking.com/css/main.css?v=0.0.8">
	';
	//////////////
	// Dark mode.
	//////////////
	$darkMode = (isset($_GET['dark']) ? $_GET['dark'] : null);
    if ( !is_null( $darkMode ) ) {
        if ($darkMode == 'on') $_SESSION['dark'] = $darkMode;
        else if ($darkMode == 'off') $_SESSION['dark'] = $darkMode;
    }
	
	$sessDarkMode = (isset($_SESSION['dark']) ? $_SESSION['dark'] : null);
	if ( !is_null($sessDarkMode) ) {
	    switch ($sessDarkMode) {
	        case 'on':
	            echo '
    <link rel="stylesheet" href="https://i.websmaking.com/css/index.rtl.css?v=0.8.7">'; // Apply rtl dark mode in index.php.
	            break;
	        case 'off':
	            echo '
    <link rel="stylesheet" href="https://i.websmaking.com/css/'.$lang['css']['index'].'?v=0.8.7">'; // Apply light mode. 
	            break;
	    }
	}else {
	    echo '
    <link rel="stylesheet" href="https://i.websmaking.com/css/'.$lang['css']['index'].'?v=0.8.7">';
	}
	
echo '
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146851516-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag(\'js\', new Date());
    
      gtag(\'config\', \'UA-146851516-1\');
    </script>
            	<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script id="rendered-js">
$(function () {
  $(window).on("scroll", function () {
    if ($(window).scrollTop() > 50) {
      $(".header").addClass("active");
    } else {
      //remove the background property so it comes transparent again (defined in your css)
      $(".header").removeClass("active");
    }
  });
});
//# sourceURL=pen.js
    </script>
</head>
    <body>
 <header class="header">
    <div class="logo-white"></div>
      <a href="'.ABS_PATH.'" class="logo">'.(!is_null($sessDarkMode) ? ($sessDarkMode == 'on' ? '<img src="https://i.websmaking.com/images/white-logo.png" style="width: 200px;">' : '<div class="image-cu"><img src="https://i.websmaking.com/images/custom-logo.svg" style="width: 200px;"></div><div class="logo-cu2"><img src="https://i.websmaking.com/images/white-logo.png" style="width: 200px;"></div>' ) : ( !is_null($darkMode) ? ( $darkMode == 'on' ? '<img src="https://i.websmaking.com/images/white-logo.png" style="width: 200px;">' : '<img src="https://i.websmaking.com/images/custom-logo.svg" style="width: 200px;">') : '<div class="image-cu"><img src="https://i.websmaking.com/images/custom-logo.svg" style="width: 200px;"></div><div class="logo-cu2"><img src="https://i.websmaking.com/images/white-logo.png" style="width: 200px;"></div>' )).'</a>
      <input class="menu-btn" type="checkbox" id="menu-btn" />
      <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
      <ul class="menu">
        '.($user->checkAuthentication() ? '<a href="'.ABS_PATH.'?do=logout"><div class="login">התנתקות</div></a>' : '<a href="'.ABS_PATH.'?do=login"><div class="login">התחברות</div></a>').'
        '.($user->checkAuthentication() ? '' : '<a href="'.ABS_PATH.'?do=register"><div class="login">הרשמה</div></a>').'
        '.($user->checkAuthentication() ? '<a href="'.ABS_PATH.'?do=settings"><div class="login">הגדרות</div></a>' : '').'
        <li><a href="'.ABS_PATH.'">דף הבית</a></li>
        <li><a href="/?do=blog">בלוג</a></li>
        <li><a href="'.ABS_PATH.'?do=premium">פרימיום</a></li>
        <li><a href="'.ABS_PATH.'?do=contact">צור קשר</a></li>
        <li><a href="/help">עזרה</a></li>
    	<div class="login-mobile">
    	    <li>'.($user->checkAuthentication() ? '<a href="'.ABS_PATH.'?do=settings">הגדרות</a>' : '').'</li>
    	    <li>'.($user->checkAuthentication() ? '<a href="'.ABS_PATH.'dashboard.php">לניהול אתרך</a>' : '<a href="'.ABS_PATH.'?do=login">התחברות</a>').'</li>
            <li>'.($user->checkAuthentication() ? '<a href="'.ABS_PATH.'?do=logout">התנתקות</a>' : '<a href="'.ABS_PATH.'?do=register">הרשמה</a>').'</li>
    	</div>
      </ul>
    </header>
    
    <div class="content">';

switch ($do) {
	case 'login':
		if ($user->checkAuthentication()) {
			header('location: '.ABS_PATH);
			die;
		}
		

		if (isset($_POST['dologin'])) {
		    if (!$func->check_securityToken($_POST['csrf_token'])) {
	            $errors = 'CSRF DETECTED !';
	         }else {
	            //https://www.google.com/recaptcha/api/siteverify
	            $cap = (isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : null);
	            if (is_null($cap)) {
	                $errors = '<div class="bad-captcha">Bad captcha.</div>';
	            }else {
	                // https://stackoverflow.com/questions/2138527/php-curl-http-post-sample-code
	                // https://developers.google.com/recaptcha/docs/display
	                $json = json_decode($func->sendRequest('https://www.google.com/recaptcha/api/siteverify', array( 'secret' => '6Le4-MMUAAAAAEVuUiE_AFFuNLL9ZH8zeqk9bnRd', 'response' => $cap, 'remoteip' => $_SERVER['REMOTE_ADDR'] )));
	                if (!$json->{'success'}) {
	                    $errors = '<div class="bad-captcha">Bad captcha.</div>';
	                }else {
	                    if ($user->doesUserExist($_POST['username'], $_POST['password'])){
            				if ($user->authenticateUser($_POST['username'], $_POST['password'])) {
            				    
            				    $db->prepare('UPDATE `buildp_users` SET lastlogin=? WHERE username=?')->execute(array($func->getClientIP() . ' ' . time() . ' UNKNOWN',  $_POST['username']));
            				    
            					$redirect = (isset($_GET['redirect']) ? $_GET['redirect'] : null);
                                header('location: '. (!is_null($redirect) ? ABS_PATH.urldecode($redirect) : ABS_PATH.'dashboard.php'));
            					die;
            				}else {
            					$error = '<div class="errors">'.$lang['index']['login']['errors']['cookies'].'</div>';
            					
            				}
            			}else {
            				$error = ''.$lang['index']['login']['errors']['incorrect'].'';
            			}
    			
	                }
	            }
	            
            }
		}

        echo '
    <style>
    .wrap-login100 {
        background: #fff;
    }
    </style>
        <div class="limiter">
    		<div class="container-login100">
    			<div class="wrap-login100">
    				<form action="" method="POST" class="login100-form validate-form">
    	    			<div align="center"> '.(isset($error) ? '<div class="error">'.$error.'</div>' : '').'
        	    		 <input type="hidden" name="csrf_token" value="'.$func->securityToken().'">
    					<span class="login100-form-title p-b-26">
    						'.$lang['index']['login']['title'].'
    					</span>
    					<span class="login100-form-title p-b-48">
    						<i class="fa fa-sign-in" aria-hidden="true"></i>
    					</span>
    
    					<div class="wrap-input100 validate-input" data-validate = "הקלד שם משתמש">
    						<input class="input100" type="text" name="username">
    						<span class="focus-input100" data-placeholder="שם משתמש"></span>
    					</div>
    
    					<div class="wrap-input100 validate-input" data-validate="הקלד סיסמה">
    						<!-- <span class="btn-show-pass">
    							<i class="zmdi zmdi-eye"></i>
    						</span> -->
    						<input class="input100" type="password" name="password">
    						<span class="focus-input100" data-placeholder="סיסמה"></span>
    					</div>
    					
    					<div class="g-recaptcha" data-sitekey="6Le4-MMUAAAAAOkYGJvFXdUmy81-zY2HEOCzCm3v"></div>
    
    					<div class="container-login100-form-btn">
    						<div class="wrap-login100-form-btn">
    							<div class="login100-form-bgbtn"></div>
    							<button class="login100-form-btn" name="dologin">
    								התחברות
    							</button>
    						</div>
    					</div>
    					<div class="text-center p-t-115">
    						<span class="txt1">
    							שכחת סיסמה? לחץ
    						</span>
    						<a class="txt2" href="'.ABS_PATH.'?do=reset">
    							כאן
    						</a>
    						<br>
    						<span class="txt1">
    							אין לך משתמש?
    						</span>
    						<a class="txt2" href="'.ABS_PATH.'?do=register">
    							הרשמה
    						</a>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
    	
    
    	<div id="dropDownSelect1"></div>
    	
    	<script src="'.ABS_PATH.'vendor/jquery/jquery-3.2.1.min.js"></script>
    	<script src="'.ABS_PATH.'vendor/animsition/js/animsition.min.js"></script>
    	<script src="'.ABS_PATH.'vendor/bootstrap/js/popper.js"></script>
    	<script src="'.ABS_PATH.'vendor/bootstrap/js/bootstrap.min.js"></script>
    	<script src="'.ABS_PATH.'vendor/select2/select2.min.js"></script>
    	<script src="'.ABS_PATH.'vendor/daterangepicker/moment.min.js"></script>
    	<script src="'.ABS_PATH.'vendor/daterangepicker/daterangepicker.js"></script>
    	<script src="'.ABS_PATH.'vendor/countdowntime/countdowntime.js"></script>
    	<script src="'.ABS_PATH.'js/main.js"></script>
    	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

        ';
/*
		echo '
		<div align="center">
			<h1>'.$lang['index']['login']['title'].'</h1>
			<BR><BR><BR>
			<form action="" method="post">
			 <div align="center"> '.(isset($error) ? '<div class="error">'.$error.'</div>' : '').'
			 <input type="hidden" name="csrf_token" value="'.$func->securityToken().'">
			 '.$lang['index']['login']['username_text'].'<BR><input type="text" name="username"><br>
			 '.$lang['index']['login']['password_text'].'&nbsp;<a href="'.ABS_PATH.'index.php?do=reset" style="font-size: 9pt; text-decoration: none; color: #505050;display: unset;">('.$lang['index']['login']['forgot_password'].')</a><BR><input type="password" class="inputlogin" name="password"><br />

					<button type="submit" name="dologin">'.$lang['index']['login']['loginButton'].'</button></div>
			</form>
		</div>
		';
*/
		break;
	case 'register':
	    if ($user->checkAuthentication()) {
			header('location: '.ABS_PATH);
		}
		
	    global $func;
		if (isset($_POST['submit'])) {
			$username = $_POST['username'];
			$salt = bin2hex((function_exists('random_bytes') ? random_bytes(32) : openssl_random_pseudo_bytes(32)));
			$password = hash('sha256', $salt.md5($_POST['password']));
			$pass = $_POST['password'];
			$mail = $_POST['mail'];
			$siteurl = strtolower($_POST['url']);
			$ip = $func->getClientIP(); //$ip = $_SERVER['REMOTE_ADDR'];
			$css = $_POST['css'];
			$logos = $_POST['logo'];


			/* ----------------------
			   --- START REGISTER ---
			   ---------------------- */
			   global $func;
			   
				 if(strlen($username) < 3 || strlen($username) > 12){
				 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['username'].'</div>';
				 }else{
					 if(strlen($pass) < 6 || strlen($pass) > 18){
					 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['password'].'</div>';
					 }else{
						 if(strlen($mail) < 4){
						 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['email'].'</div>';
						 }else{
						 if(strlen($siteurl) < 3 || strlen($siteurl) > 12){
						 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['siteurl'].'</div>';
						 }else{
							 if(strlen($logos) < 2 || strlen($logos) > 30){
							 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['sitename'].'</div>';
							 }else{
								 $query = "SELECT * FROM buildp_users WHERE username=?";
								 $res = $db->prepare($query);
								 $res->execute(array($username));

								 if ($res->rowCount() > 0) {
								 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['username_exists'].'</div>';
								 }else{
									 $query = "SELECT * FROM buildp_users WHERE mail=?";
									 $res = $db->prepare($query);
									 $res->execute(array($mail));
									 if ($res->rowCount() > 0) {
									 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['email_exists'].'</div>';
									 }else{
										 $query = "SELECT * FROM buildp_sites WHERE siteurl=?";
										 $res = $db->prepare($query);
										 $res->execute(array($siteurl));
										 if ($res->rowCount() > 0) {
										 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['siteurl_exists'].'</div>';
										 }else{
											 $e = false;
											 $dir = new DirectoryIterator(dirname(__FILE__));
											 foreach ($dir as $fileinfo) {
												 if ($fileinfo->isDir() && !$fileinfo->isDot()) {
													 if ($siteurl == $fileinfo->getFilename()){ // Block users from getting a url of existing folder's name.
														 $e = true;
													 }
												 }
											 }

											 if ($e) {
											 	$errors = '<div class="errors">'.$lang['index']['register']['errors']['siteurl_exists'].'</div>';

											 }else{

												 if (!preg_match('/^[a-z]+$/i', $siteurl)) {
													 $errors = '<div class="errors">'.$lang['index']['register']['errors']['siteurl_invalid'].'</div>';
												 }else {
												     
												     if(!$func->validateEmailDomain($mail)) {
												         $errors = '<div class="errors">'.$lang['index']['register']['errors']['email_invalid'].'</div>';
											         
    											     }else {
    											         
    											         if (!$func->check_securityToken($_POST['csrf_token'])) {
    											            $errors = 'CSRF DETECTED !';
    											         }else {
    											             
    											             $q1 = $db->prepare('SELECT * FROM buildp_sites WHERE siteurl=?');
    											             $q1->execute(array($siteurl));
    											             if ($q1->rowCount() > 0) {
    											                $errors = '<div class="errors">'.$lang['index']['register']['errors']['siteurl_exists'].'</div>';
    											             }else {
    											                
    											                $cap = (isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : null);

                                                                if (is_null($cap)) {
                                                                    $errors = '<div class="bad-captcha">Bad captcha.</div>';
                                                                } else { 
                                                                    $json = json_decode($func->sendRequest('https://www.google.com/recaptcha/api/siteverify', array( 'secret' => '6Le4-MMUAAAAAEVuUiE_AFFuNLL9ZH8zeqk9bnRd', 'response' => $cap, 'remoteip' => $_SERVER['REMOTE_ADDR'] )));
                                                                    if (!$json->{'success'}) {
                                                                        $errors = '<div class="bad-captcha">Bad captcha.</div>';
                                                                    } else { 
                                                                        
                                                                        //`id`, `userID`, `themeID`, `siteurl` , `logoImage`
            
                                                                        $userID = ($db->getRowsCount('buildp_users')+1);
                                                                        $siteID = ($db->getRowsCount('buildp_sites')+1);
                                                                        srand(time());
                                                                        $pageID = rand(1000, 9999);
                                                                        
                                                                        $theme = $db->prepare('SELECT * FROM `buildp_themes` WHERE id=?');
                                                                        $theme->execute(array($css));
                                                                        $theme = $theme->fetch();
                                                                        
                                                                        include_once(dirname(__FILE__).'/templates/'.$theme['folderName'].'/metadata.php');
                                                                        global $metadata;
                                                                        
                                                                        
                                                                        /*
                                                                        $header = '
                                                                        <table border="0" cellpadding="1" cellspacing="1" style="width:100%;"> 	<tbody> 		<tr> 			<td> 			<h1><span style="font-size:36px;"><strong><span style="font-family:Times New Roman,Times,serif;">YOUR CUSTOM LOGO</span></strong></span></h1> 			</td> 			<td> 			<h2>תיאור האתר כאן<br /> 			<span style="font-size:16px;">על מה העסק שלך עוסק?</span></h2> 			</td> 			<td><img alt="" src="https://image.flaticon.com/icons/png/512/33/33702.png" style="height: 30px; width: 30px;" />&nbsp;<img alt="" src="https://image.flaticon.com/icons/png/512/23/23931.png" style="height: 30px; width: 30px;" />&nbsp;<img alt="" src="https://image.flaticon.com/icons/png/512/25/25178.png" style="height: 30px; width: 30px;" /></td> 		</tr> 	</tbody> </table>
                                                                        ';*/
                                                                        $header  = $metadata['data']['en']['header'];
                                                                        /*
                                                                        $text = '
                                                                        <table align="center" border="0" cellpadding="1" cellspacing="1" style="width:100%;"><tbody><tr><td><p style="text-align: center;"><img alt="" src="https://images.unsplash.com/photo-1513151233558-d860c5398176?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=1350&amp;q=80" style="width: 100%; float: right;" /><span style="font-size:36px;"><span style="color:#2980b9;"><strong>ברכות על אתרך החדש!</strong></span></span><br /><span style="font-size:22px;"><span style="color:#e67e22;"><strong>כעת ניתן לצור תוכן ואפילו לערוך טקסט זה באמצעות פאנל ניהולך, בהצלחה! :)</strong></span></span><br /><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים, תערכו את זה למה שתרצו.&nbsp;</span><span style="font-size:18px;">טקסט זה ניתן להחלפה בעזרת פאנל המנהלים</span></p><p style="text-align: center;"><span style="font-size:18px;"><input name="כפתור" type="button" value="כפתור לעריכה" /></span></p></td></tr></tbody></table>
                                                                        ';*/
                                                                        $text = $metadata['data']['en']['content'];
                                                                        /*
                                                                        $footer = '
                                                                        <p style="text-align: center;"><img alt="" src="https://image.flaticon.com/icons/png/512/33/33702.png" style="height: 30px; width: 30px;" />&nbsp;<img alt="" src="https://image.flaticon.com/icons/png/512/23/23931.png" style="height: 30px; width: 30px;" />&nbsp;<img alt="" src="https://image.flaticon.com/icons/png/512/25/25178.png" style="height: 30px; width: 30px;" /></p>  <p style="text-align: center;">&copy; כל הזכויות שמורות</p>
                                                                        ';*/
                                                                        $footer = $metadata['data']['en']['footer'];
                                                                        
                                                                        $sidebar = $metadata['data']['en']['sidebar'];
                                                                        
                                                                        
                                                                        /*
                                                                        group:
                                                                        1 - an admin.
                                                                        2 - a site's owner.
                                                                        3 - a site's user.
                                                                        */
                                                                        $db->prepare("INSERT INTO `buildp_users` (`id`, `username`, `password`, `salt`, `mail`,`ip`,`group`, `time`) VALUES(?,?,?,?,?,?,?,?)")->execute(array($userID, $username, $password, $salt, $mail, $ip, '2', time()));
                                                                        $db->prepare("INSERT INTO `buildp_sites` (`id`, `userID`, `themeID`, `siteurl` , `logoImage`, `header`, `footer`, `robots`, `favicon`, `accessCode`, `sidebar`, `language_code`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)")->execute(array($siteID, $userID, $css, $siteurl, $logos, $header, $footer, 'index,follow', ABS_PATH.'images/favicon.icon', NULL, $sidebar, 'he'));
                                                                        $db->prepare('INSERT INTO `buildp_pages` ( `id`, `siteid`, `pagename`, `text`, `time`, `inMenu`, `tags`, `previewImageURL`, `customURL`, `seo_description`, `seo_keywords`, `orderNum` ) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,? )')->execute(array($pageID, $siteID, 'Home page', $text, time(), 1, '', '', '', '', '', 1));
                                                                        $errors = '<div class="success"><div style="color: green; padding: 10px; border: solid 1px; border-radius: 5px; margin-bottom: 20px; margin-top: 20px; font-size: 14px;">'.$lang['index']['register']['errors']['success_message'].'</div></div>';
                                                                        
                                                                        
                                                                        /*** Send Mail after registration
                                                                         */
                                                                        $to = $mail;
                                                                        $subject = "נירשמת בהצלחה !";
                                                                        $message = "
                                                                            
                                                                        "; // לרשום כאן הודעה שתישלח במייל לאחר שההרשמה תושלם.
                                                                        $func->sendMail($to, $subject, $message);
                                                                        
                                                                    }
                                                                }
 
    											             }
    											         }
    											     }
											 	}
											 }
										 }
									 }
								 }
							 }
						 }
					 }
					 }
				 }
			/* --------------------
			   --- END REGISTER ---
			   -------------------- */
		}

        
        echo '
<style>
.popover__title {
    font-size: 12px;
    color: rgb(255, 255, 255);
    border-radius: 105px;
    width: 15px;
    height: 15px;
    text-align: center;
    padding-top: 2px;
    background: #707f92;
}

.popover__wrapper {
    position: unset;
    float: left;
    margin-top: -30px;
}
.popover__content {
    opacity: 0;
    visibility: hidden;
    position: absolute;
    transform: translate(0, 10px);
    background-color: #e4e4e4;
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
    width: 100%;
    top: 53px;
    position: absolute;
    right: 0px;
}
/* .popover__content:before {
  position: absolute;
  z-index: -1;
  content: "";
  right: calc(50% - 10px);
  top: -8px;
  border-style: solid;
  border-width: 0 10px 10px 10px;
  border-color: transparent transparent #e4e4e4 transparent;
  transition-duration: 0.3s;
  transition-property: transform;
} */
.popover__wrapper:hover .popover__content {
  z-index: 10;
  opacity: 1;
  visibility: visible;
  transform: translate(0, -20px);
  transition: all 0.5s cubic-bezier(0.75, -0.02, 0.2, 0.97);
}
.popover__message {
  text-align: center;
}

.btn-show-pass {
    left: 23px;
}
.wrap-login100 {
    background: #fff;
}

        </style>
        <div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form action="" method="POST" class="login100-form validate-form">
				<input type="hidden" name="csrf_token" value="'.$func->securityToken().'">
		        <input type="hidden" name="css" value="1">
					<span class="login100-form-title p-b-26">
						'.$lang['index']['register']['title'].'
					</span>
					<span class="login100-form-title p-b-48">
						<i class="fa fa-user" aria-hidden="true"></i>
						'.(isset($errors) ? $errors : '').'
					</span>
					<div style="margin-top: -10px; margin-bottom: 45px; text-align: center;">	משתמש קיים? לחץ <a href="/?do=login">כאן</a></div>
                    
                    <div class="wrap-input100 validate-input" data-validate="3-12 תווים">
						<input class="input100" type="text" name="username">
						<span class="focus-input100" data-placeholder="שם משתמש"></span>
						<div class="popover__wrapper"><h2 class="popover__title">?</h2></a><div class="popover__content"><p class="popover__message">שם המשתמש חייב להיות בין 3 ל-12 תווים</p></div></div>
					</div>
					
					<div class="wrap-input100 validate-input" data-validate="הזן סיסמה">
					<!--	<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span> -->
						<input class="input100" type="password" name="password">
						<span class="focus-input100" data-placeholder="'.$lang['index']['register']['password_label'].'"></span>
						<div class="popover__wrapper"><h2 class="popover__title">?</h2></a><div class="popover__content"><p class="popover__message">הסיסמה חייבת להיות בין 6 ל-18 תווים</p></div></div>
					</div>
                    
					<div class="wrap-input100 validate-input" data-validate="אימייל חוקי הוא: a@b.c">
						<input class="input100" type="text" name="mail">
						<span class="focus-input100" data-placeholder="'.$lang['index']['register']['mail_label'].'"></span>
						<div class="popover__wrapper"><h2 class="popover__title">?</h2></a><div class="popover__content"><p class="popover__message">מיילים מורשים:<br>Gmail, Walla</p></div></div>
					</div>
					
					<div class="wrap-input100 validate-input" data-validate="לא הוזן URL">
						<input class="input100" type="text" name="url">
						<span class="focus-input100" data-placeholder="'.$lang['index']['register']['siteurl_placeHolder'].'"></span>
						<div class="popover__wrapper"><h2 class="popover__title">?</h2></a><div class="popover__content"><p class="popover__message">כתובת האתר צריכה להיות בין 3 ל-12 תווים, באנגלית ובלי סימנים ומספרים </p></div></div>
                	</div>
					<div class="wrap-input100 validate-input" data-validate="לא הוזן שם האתר">
						<input class="input100" type="text" name="logo">
						<span class="focus-input100" data-placeholder="'.$lang['index']['register']['sitename_label'].'"></span>
                        <div class="popover__wrapper"><h2 class="popover__title">?</h2></a><div class="popover__content"><p class="popover__message">שם האתר צריך להיות בין 2 ל-15 תווים (אחרי פתיחת האתר ניתן להוסיף עוד תווים בהגדרות)</p></div></div>
					</div>
					אימות אנושי:
					<div class="g-recaptcha" data-sitekey="6Le4-MMUAAAAAOkYGJvFXdUmy81-zY2HEOCzCm3v"></div><br>
					
<input name="accept" required="required" type="checkbox" /> בהרשמתך לאתר אתה מאשר כי קראת, הבנת והסכמת את <a href="?do=terms" target="_blank"><div style="border-bottom: solid 1px; display: inline;">תנאי השימוש</div></a> ו<a href="?do=privacy" target="_blank"><div style="border-bottom: solid 1px; display: inline;">מדיניות פרטיות</a>.
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" name="submit">
								'.$lang['index']['register']['registerButton'].'
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	<script src="'.ABS_PATH.'vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="'.ABS_PATH.'vendor/animsition/js/animsition.min.js"></script>
	<script src="'.ABS_PATH.'vendor/bootstrap/js/popper.js"></script>
	<script src="'.ABS_PATH.'vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="'.ABS_PATH.'vendor/select2/select2.min.js"></script>
	<script src="'.ABS_PATH.'vendor/daterangepicker/moment.min.js"></script>
	<script src="'.ABS_PATH.'vendor/daterangepicker/daterangepicker.js"></script>
	<script src="'.ABS_PATH.'vendor/countdowntime/countdowntime.js"></script>
	<script src="'.ABS_PATH.'js/main.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
	

/*
		echo '
		<div align="right">
		<div class="container-login100">
		<div class="wrap-login100">
		<span class="login100-form-title p-b-26">
		עוד צעד אחד וגם לך יש אתר באינטרנט!</span>
		<span class="login100-form-title p-b-48">
		<i class="fa fa-user" aria-hidden="true"></i>
		</span>
		
		<form action="" method="post">
		'.(isset($errors) ? $errors : '').'
		
		<input type="hidden" name="csrf_token" value="'.$func->securityToken().'">
		<input type="hidden" name="css" value="1">
		<table>
		<div align="center">
		<tr>
			<td class="bojf13">'.$lang['index']['register']['username_label'].':</td>
			<td class="bojf13"><input type="text" class="inputfi" name="username" size="30"  placeholder="'.$lang['index']['register']['username_placeHolder'].'"></td>
		</tr>
		<tr>
			<td class="bojf13">'.$lang['index']['register']['password_label'].':<BR></td><td class="bojf13"><input type="password" class="inputfi" name="password" size="30" placeholder="'.$lang['index']['register']['password_placeHolder'].'"></td>
		</tr>
		<tr>
			<td class="bojf13">'.$lang['index']['register']['mail_label'].':</td><td class="bojf13"><input type="text" name="mail" class="inputfi" size="30"></td>
		</tr>
		<tr>
			<td class="bojf13">'.$lang['index']['register']['siteurl_label'].': </td><td class="bojf13"><div class="abs2">https://www.websmaking.com/</div><input type="text" class="siteurl" name="url" class="inputfi" size="30" placeholder="'.$lang['index']['register']['siteurl_placeHolder'].'" style="direction: ltr;"></td>
		</tr>
		<tr>
		    <td class="bojf13">'.$lang['index']['register']['sitename_label'].': </td><td class="bojf13"><input type="text" name="logo" class="inputfi" size="30"></td>
		</tr>
		</table>
		<div class="width-send">
		    <button type="submit" name="submit" class="caftor">'.$lang['index']['register']['registerButton'].'</button>
		</form><BR>
		</div></div></div></div>
		';
		*/

		break;
		case 'about':
/*
			echo '
			<style type="text/css">
.about {
    width: 50%;
    background: #fff;
    border-radius: 10px;
    position: relative;
    color: #000;
    margin: auto;
    padding: 35px;
    line-height: 1.2;
    font-size: 15pt;
    font-weight: lighter;
}

.about a {
    display: unset;
}

@media screen and (max-width: 767px) { .about { width: 75%; }}
			</style>
			<h1>'.$lang['index']['about']['title'].'</h1>
			<div class="about">
                '.$lang['index']['about']['text'].'
            </div>
			';
*/
			break;
		case 'reset':
		if ($user->checkAuthentication()) {
			header('location: '.ABS_PATH.'dashboard.php');
		}
		
		global $func;
		
		$hash = (isset($_GET['hash']) ? $_GET['hash'] : null);
		if (!is_null($hash)) {
		    
		    $q2 = $db->prepare('SELECT * FROM `buildp_resets` WHERE hash=?');
		    $q2->execute(array($hash));
		    if ($q2->rowCount() < 1) {
		        header('location: '.ABS_PATH);
		        die;
		    }
			
			if (isset($_POST['changeSubmit'])) {
			    
			    $pass = $_POST['password'];
			    $cpass = $_POST['cpassword'];
			    
			    if (empty($pass)) {
			        $errors = $lang['index']['reset']['errors']['password_empty'];
			    }else {
			        if (empty($cpass)) {
			            $errors = $lang['index']['reset']['errors']['confirmPassword_empty'];
			        }else {
			            
			            if ($pass != $cpass) {
        			        $errors = $lang['index']['reset']['errors']['passwords_different'];
        			    }else {
        			    
            			    $q1 = $db->prepare('SELECT * FROM buildp_resets WHERE hash=?');
                			$q1->execute(array($hash));
                			
                			if ($q1->rowCount() > 0) {
                			    
                			    if (!$func->check_securityToken($_POST['csrf_token'])) {
                			        $errors = 'CSRF ATTACK DETECTED !';
                			    }else {
                				
                				$userID = $q1->fetch()['userID'];
                
                				$q2 = $db->prepare('SELECT * FROM buildp_users WHERE id=?');
                				$q2->execute(array($userID));
                                $user = $q2->fetch();
                
                				$newPassword = hash('sha256', $user['salt'].md5($pass));
                
                				$db->prepare('UPDATE buildp_users SET password=? WHERE id=?')->execute(array($newPassword, $userID));
                				$db->prepare('DELETE FROM buildp_resets WHERE hash=?')->execute(array($hash));
                    

                				$subject = $url. " - ".$lang['index']['reset']['mail2']['subject'];
                				$message = '
                						'.$lang['index']['reset']['mail2']['message'].': '.$pass.'
                					';
                
                				echo '
                				<div style="text-align:center;">'.(!isset($errors) ? '<div class="succeded">'.$lang['index']['reset']['mail2']['success_message'].'</div>' : '<div class="error">'.$errors.'</div>').'</div>
                				';
                				$func->sendMail($user['mail'], $subject, $message);
                				echo '<script typr="text/javascript">alert("'.$lang['index']['reset']['errors']['success_message'].'");</script><meta http-equiv="refresh" content="2;url='.ABS_PATH.'?do=login" />';
                				//header('location: '.ABS_PATH.'index.php?do=login');
                				die;
                			    }
                			}else { 
                			    header('location: '.ABS_PATH.'index.php?do=login'); 
                			}
        			    }
			        }
			    }
			}
			
			echo '
			<div class="limiter">
        		<div class="container-login100">
        			<div class="wrap-login100">
        				<form action="" method="POST" class="login100-form validate-form">
        				    <input type="hidden" name="csrf_token" value="'.$func->securityToken().'">
        					<span class="login100-form-title p-b-26">
        						ברוך שובך
        					</span>
        					<span class="login100-form-title p-b-48">
        						<i class="fa fa-sign-in" aria-hidden="true"></i>
        						<div style="text-align:center;">'.(isset($errors) ? '<div class="error">'.$errors.'</div>' : '').'</div>
        					</span>
        
        					<div class="wrap-input100 validate-input" data-validate="Enter password">
        						<span class="btn-show-pass">
        							<i class="zmdi zmdi-eye"></i>
        						</span>
        						<input class="input100" type="password" name="password">
        						<span class="focus-input100" data-placeholder="'.$lang['index']['reset']['newPassword'].'"></span>
        					</div>
        					
        					<div class="wrap-input100 validate-input" data-validate="Enter confirm password">
        						<span class="btn-show-pass">
        							<i class="zmdi zmdi-eye"></i>
        						</span>
        						<input class="input100" type="password" name="cpassword">
        						<span class="focus-input100" data-placeholder="'.$lang['index']['reset']['confirmNewPassword'].'"></span>
        					</div>
        
        					<div class="container-login100-form-btn">
        						<div class="wrap-login100-form-btn">
        							<div class="login100-form-bgbtn"></div>
        							<button class="login100-form-btn" name="changeSubmit">
        								'.$lang['index']['reset']['changePassword'].'
        							</button>
        						</div>
        					</div>
        				</form>
        			</div>
        		</div>
        	</div>
        	
        
        	<div id="dropDownSelect1"></div>
        	
        	<script src="'.ABS_PATH.'vendor/jquery/jquery-3.2.1.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/animsition/js/animsition.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/bootstrap/js/popper.js"></script>
        	<script src="'.ABS_PATH.'vendor/bootstrap/js/bootstrap.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/select2/select2.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/daterangepicker/moment.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/daterangepicker/daterangepicker.js"></script>
        	<script src="'.ABS_PATH.'vendor/countdowntime/countdowntime.js"></script>
        	<script src="'.ABS_PATH.'js/main.js"></script>
			';
			
			/*
			echo '
			<style type="text/css">
			
			</style>
			<form action="" method="POST">
			    <input type="hidden" name="csrf_token" value="'.$func->securityToken().'">
			    <div style="text-align:center;">'.(!isset($errors) ? '' : '<div class="error">'.$errors.'</div>').'</div>
    			<label for="">'.$lang['index']['reset']['newPassword'].':<input type="text" name="password"></label><br>
    			<label for="">'.$lang['index']['reset']['confirmNewPassword'].':<input type="text" name="cpassword"></label><br>
    			<button type="submit" name="changeSubmit">'.$lang['index']['reset']['changePassword'].'</button>
			</form>
			';*/
			
			
		}else {
			
			if (isset($_POST['submit'])) {
				$to = $_POST['email'];
				
				if (empty($to)) {
					$errors = $lang['index']['reset']['errors']['email_empty'];
				}else {
				    
				    if (!$func->validateEmailDomain($to)) {
		            	$errors = $lang['index']['reset']['errors']['email_invalid'];
				    }else {
				        
				        $q1 = $db->prepare('SELECT * FROM buildp_users WHERE mail=?');
				        $q1->execute(array($to));
				        if ($q1->rowCount() < 1) {
				            $errors = $lang['index']['reset']['errors']['email_doesnt_exist'];
				        }else {
				            
				            if (!$func->check_securityToken($_POST['csrf_token'])) {
				                $erros = 'CSRF ATTACK DETECTED !';
				            }else {
				                
            					$hash = bin2hex((function_exists('random_bytes') ? random_bytes(16) : openssl_random_pseudo_bytes(16) ));
            					
            					$q1 = $db->prepare('SELECT * FROM buildp_users WHERE mail=?');
            					$q1->execute(array($to));
            					$userID = $q1->fetch()['id'];
            					
            					$id = ($db->getRowsCount('buildp_resets')+1);
            					$db->prepare('INSERT INTO `buildp_resets` (`id`, `hash`, `userID`, `time`) VALUES (?,?,?,?)')->execute(array($id, $hash, $userID, time()));
            					
            					$subject = $url. " - ".$lang['index']['reset']['mail']['subject'];
            					
            					$resetURL = ABS_PATH.'index.php?do=reset&hash='.$hash;
            					$message = '
            					'.$lang['index']['reset']['mail']['message'][0].' , '.$user->getUsernameByID($userID).'
                                <br>
                                '.$lang['index']['reset']['mail']['message'][1].'
                                '.$resetURL.'
                                '.$lang['index']['reset']['mail']['message'][2].'
            					';
            
                                $errors = $lang['index']['reset']['errors']['success_message'];
            					$func->sendMail($to, $subject, $message);
            					echo '<script typr="text/javascript">alert("'.$lang['index']['reset']['errors']['success_message'].'");</script><meta http-equiv="refresh" content="2;url='.ABS_PATH.'?do=login" />';
                				//header('location: '.ABS_PATH.'?do=login');
                				die;
				            }
				        }
				    }
				}
			}
            
            echo '
            <style>
            .wrap-login100 {
                background: #fff;
            }
            </style>
			<div class="limiter">
        		<div class="container-login100">
        			<div class="wrap-login100">
        				<form action="" method="POST" class="login100-form validate-form">
        				    <input type="hidden" name="csrf_token" value="'.$func->securityToken().'">
        					<span class="login100-form-title p-b-26">
        						'.$lang['index']['reset']['title'].'
        					</span>
        					<span class="login100-form-title p-b-48">
        						<i class="fa fa-key" aria-hidden="true"></i>
        						<div style="text-align:center;">'.(isset($errors) ? '<div class="error">'.$errors.'</div>' : '').'</div>
        					</span>
        					
        					<div class="wrap-input100 validate-input" data-validate="Enter Email">
        						<input class="input100" type="text" name="email">
        						<span class="focus-input100" data-placeholder="'.$lang['index']['reset']['mailPlaceholder'].'"></span>
        					</div>
        
        					<div class="container-login100-form-btn">
        						<div class="wrap-login100-form-btn">
        							<div class="login100-form-bgbtn"></div>
        							<button class="login100-form-btn" name="submit">
        								'.$lang['index']['reset']['resetButton'].'
        							</button>
        						</div>
        					</div>
        				</form>
        			</div>
        		</div>
        	</div>
        	
        
        	<div id="dropDownSelect1"></div>
        	
        	<script src="'.ABS_PATH.'vendor/jquery/jquery-3.2.1.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/animsition/js/animsition.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/bootstrap/js/popper.js"></script>
        	<script src="'.ABS_PATH.'vendor/bootstrap/js/bootstrap.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/select2/select2.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/daterangepicker/moment.min.js"></script>
        	<script src="'.ABS_PATH.'vendor/daterangepicker/daterangepicker.js"></script>
        	<script src="'.ABS_PATH.'vendor/countdowntime/countdowntime.js"></script>
        	<script src="'.ABS_PATH.'js/main.js"></script>
			';



/*
			echo '
			<style type="text/css">
			div#content { text-align: center; margin-top: 40px; } form button2 { background: #d0d0d0; color: #6b6b6b; border: solid 0px; padding: 5px; padding-right: 35px; padding-left: 35px; font-size: 13pt; outline: none; cursor: pointer; border-radius: 10px; margin-top: 4px; user-select: none;}
			</style>
			
			
			<div class="container-login100">
			<div class="wrap-login100">
			<span class="login100-form-title p-b-26">שחזור סיסמה</span>
			<span class="login100-form-title p-b-48"> <i class="fa fa-key" aria-hidden="true"></i> </span>
			<form action="" method="POST">
			    <input type="hidden" name="csrf_token" value="'.$func->securityToken().'">
			    <div style="text-align:center;">'.(isset($errors) ? '<div class="error">'.$errors.'</div>' : '').'</div>
    			<label for=""><input type="text" name="email" placeholder="'.$lang['index']['reset']['mailPlaceholder'].'"></label>
    			<button type="submit" name="submit">'.$lang['index']['reset']['resetButton'].'</button>
			</form></div></div>
			<!--
			<span>
			    ההודעה נשלחה למייל שצוין, אם לא ראיתם את ההודעה אנא תבדקו גם בתיבת הספאם.
			</span>
			-->
			';
			*/
			
		}
		break;
	    case 'mysites':
	        
	        // TODO: 
	        // 1) Can buy premium to each site individually.
	        
	        if ($user->checkAuthentication()) {
	            
	            $sites = $db->prepare('SELECT * FROM `buildp_sites` WHERE userID=?');
	            $sites->execute(array($userloggined));
	            $sites = $sites->fetchAll();
	            
	            
	            echo '<div class="head-block2">
<div class="head-block">&nbsp;</div>
</div>';
	            for ($i=0; $i<count($sites); $i++) {
	                $site = $sites[$i];
	                
	                if ($site['isPremium'] == 1) {
	                    $diff = floor(($site['premiumExpiry']-time())/1000*60*60*24);
	                    if ($diff <= 0) {
	                        $db->prepare('UPDATE `buildp_sites` SET isPremium=?, premiumExpiry=? WHERE id=?')->execute(array(0, '0', $site['id']));
	                    }
	                    else if ($diff <= 3) {
	                        $msg = 'הפרמיום של האתר '.$site['siteurl'].' יפוג בעוד '.$diff.' ימים.';
	                        srand(time());
	                        $db->prepare('INSERT INTO `buildp_notifications`(id,message,userID) VALUES(?,?,?)')->execute(array(rand(10000, 99999), $msg, $site['userID']));
	                    }
	                }
	                
	                // Check if the site is linked to a domain, if so then redirect to the domain instead of the url.
	                
	                $url = (!empty($site['domain']) ? 'https://'.$site['domain'] : $site['siteurl']);
	                
	                echo '
	                <div style="position: relative; border-radius: 4px; margin-bottom: 20px; margin: auto;">
	                   <div style="border-bottom: solid 1px #d8d8d8; padding-bottom: 5px; width: fit-content; margin: auto;"><i class="fas fa-external-link-alt"></i> <a href="'.$url.'" target="_blank">'.$site['logoImage'].'</a>&nbsp;
                        <a href="'.ABS_PATH.'?do=manage&siteid='.$site['id'].'"><i class="far fa-edit"></i> ניהול</a>&nbsp;
                        <!-- <a href="'.ABS_PATH.'storecp.php?siteid='.$site['id'].'"><i class="far fa-edit"></i> ניהול חנות</a>&nbsp; -->
	                    '.($site['isPremium'] == 1 ? '' : '<a href="'.ABS_PATH.'?do=premium&siteid='.$site['id'].'"><i class="fa fa-diamond"></i> לשדרג לפרימיום</a></div>').'
	                </div>
	                ';
	                
	            }
	            echo '<div style="width: 55%; margin: auto; text-align: center; margin-top: 15px; font-size: 13pt;"><a href="'.ABS_PATH.'?do=manage"><i class="fas fa-plus"></i> צור אתר חדש </a></div>
	            
	            <style>
	                .head-block {
                        background-image: url(https://images.unsplash.com/photo-1464746133101-a2c3f88e0dd9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1327&q=80);
                        height: 185px;
                        margin-bottom: 30px;
                        background-position: center;
                        background-repeat: no-repeat;
                        background-size: cover;
                        opacity: 0.2;
                        margin-top: -46px;
                    }
                    .head-block2 {
                        background-image: -webkit-linear-gradient(45deg,#780206,#061161);
                        background-image: linear-gradient(45deg,#780206,#061161);
                        background-repeat: repeat-x;
                    }
	           </style>
	            ';
	            
	        }else {
	            header('location: '.ABS_PATH.'');
	            die;
	        }
	        
	        break;
	    case 'manage':
	        if (!$user->checkAuthentication()) {
	            header('location: '.ABS_PATH);
                die;
	        }
	        
	        global $func;
	        
	        $group = $db->prepare('SELECT `group` FROM `buildp_users` WHERE id=?');
	        $group->execute(array($userloggined));
	        $group = $group->fetch()['group'];
            if ($group == 1) {
                header('location: '.ABS_PATH.'dashboard.php');
                die;
            }
            
            $siteID = (isset($_GET['siteid']) ? $_GET['siteid'] : null);
            
            if (!is_null($siteID)) {
                $userID = $db->prepare('SELECT `userID` FROM `buildp_sites` WHERE id=?');
    	        $userID->execute(array($siteID));
    	        $userID = $userID->fetch();
    	        
                if ($userID == false || $userID['userID'] != $userloggined) {
                    $redirect = (isset($_GET['redirect']) ? $_GET['redirect'] : null);
                    
                    header('location: '.ABS_PATH.'?do=mysites'.( isset($_GET['redirect']) ? '&redirect='.$_GET['redirect'] : '') );
                    die;
                }
                
                $_SESSION['siteid'] = $siteID;
                
                $redirect = (isset($_GET['redirect']) ? $_GET['redirect'] : null);
                if (is_null($redirect))
                    header('location: '.ABS_PATH.'dashboard.php');
                else
                    header('location: '.ABS_PATH.$redirect);
                die;
                
            }else {
                
                if (isset($_POST['createSite'])) {
                    $errors = '';
                    
                    $siteurl = $func->protectxss($_POST['siteurl']);
                    $css = $func->protectxss($_POST['css']);
                    $logos = $func->protectxss($_POST['logo']);
                    
                    $limit = $db->prepare('SELECT * FROM buildp_sites WHERE userID=?');
                    $limit->execute(array($userloggined));
                    $l12 = $limit->fetchAll();
                    if (count($l12) >= 5) {
                        $errors = 'אין באפשרותך ליצור יותר מ- 5 אתרים';
                    }
                    
                    if (empty($siteurl)) {
                        $errors = 'חייב לספר כתובת אתר';
                    }
                    if (empty($css)) {
                        $errors = 'No CSS picked';
                    }
                    if (empty($logos)) {
                        $errors = 'חייב למלא שם אתר';
                    }
                    
                    if (strlen($siteurl) < 3) {
                        $errors = 'כתובת האתר חייבת להכיל 3 תווים לפחות';
                    }
                    
                    $query = "SELECT * FROM buildp_sites WHERE siteurl=?";
					$res = $db->prepare($query);
                    $res->execute(array($siteurl));
                    if ($res->rowCount() > 0) {
                        $errors = 'הכתובת תפוסה';
                    }
                    
                    $e = false;
                    $dir = new DirectoryIterator(dirname(__FILE__));
                    foreach ($dir as $fileinfo) {
                        if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                            if ($siteurl == $fileinfo->getFilename()){ // Blocks users from getting a url of existing folder's name for thier site.
                                $e = true;
                            }
                        }
                    }
                    if ($e) {
                        $errors = 'כתובת תפוסה';
                    }
                    
                    if (!preg_match('/^[a-z]+$/i', $siteurl)) {
                        $errors = 'כתובת האתר לא יכול להכיל סימנים';
                    }
                    
                    $theme = $db->prepare('SELECT * FROM `buildp_themes` WHERE id=?');
                    $theme->execute(array($css));
                    $theme = $theme->fetch();
                    
                    if ($theme['isPremium'] == 1 && $row['isPremium'] == 0) {
                        $errors = 'האתר לא פרמיום.';
                        
                    } // Disallow people from choosing premium themes.
                    
                    if (empty($errors)) {
                        
                        $userID = $userloggined;
                        $siteID = ($db->getRowsCount('buildp_sites')+1);
                        srand(time());
                        $pageID = rand(1000, 9999);
                        
                        include_once(dirname(__FILE__).'/templates/'.$theme['folderName'].'/metadata.php');
                        global $metadata;
                        
                        $header  = $metadata['data']['en']['header'];
                        $text = $metadata['data']['en']['content'];
                        $footer = $metadata['data']['en']['footer'];
                        $sidebar = $metadata['data']['en']['sidebar'];
                        
                        /*
                        1 - an admin.
                        2 - a site's owner.
                        3 - a site's user.
                        */
                        $db->prepare("INSERT INTO `buildp_sites` ( `id`, `userID`, `themeID`, `siteurl` , `logoImage`, `header`, `footer`, `robots`, `favicon`, `accessCode`, `sidebar`, `language_code`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)")->execute(array($siteID, $userID, $css, $siteurl, $logos, $header, $footer, 'index,follow', ABS_PATH.'images/favicon.icon', NULL, $sidebar, 'he'));
                        //$db->prepare('INSERT INTO `buildp_pages` ( `id`, `siteid`, `pagename`, `text`, `time`, `inMenu` ) VALUES ( ?,?,?,?,?,? )')->execute(array($pageID, $siteID, 'Home page', $text, time(), 1));
                        $db->prepare('INSERT INTO `buildp_pages` ( `id`, `siteid`, `pagename`, `text`, `time`, `inMenu`, `tags`, `previewImageURL`, `customURL`, `seo_description`, `seo_keywords`, `orderNum` ) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,? )')->execute(array($pageID, $siteID, 'Home page', $text, time(), 1, '', '', '', '', '', 1));
                        
                        header('location: '.ABS_PATH.'?do=mysites');
                        die;
                    }
                    header('location: '.ABS_PATH.'?do=manage');
                    die;
                }
                
                $limit = $db->prepare('SELECT * FROM `buildp_sites` WHERE userID=?');
                $limit->execute(array($userloggined));
                $l12 = $limit->fetchAll();
                if (count($l12) < 5) {
                    
                echo '
                    	    <style type="text/css">
    	        .box div {
        background: #ffcece;
        border: solid 1px red;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        margin-bottom: 25px;
    }
    
input:focus {
    outline: auto;
}

    	                .head-block {
    background-image: url(https://images.unsplash.com/photo-1464746133101-a2c3f88e0dd9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1327&q=80);
    height: 185px;
    margin-bottom: 30px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    opacity: 0.2;
    margin-top: -46px;
}
.head-block2 {
    background-image: -webkit-linear-gradient(45deg,#780206,#061161);
    background-image: linear-gradient(45deg,#780206,#061161);
    background-repeat: repeat-x;
}

.col-md-8 input[type="password"] {
    width: 250px;
    margin-right: 0px;
    border-radius: 5px;
    font-size: 13pt;
    padding: 4px;
    border: 1px solid #b0b4b8;
    color: #000000;
    margin-top: 10px;
    margin-bottom: 20px;
    display: block;
}

[type=submit] {
    -webkit-appearance: button;
    background: none;
    text-align: center;
    border-radius: 30px;
    padding: 12px;
    border: solid 1px #05bc24;
    color: #05bc24;
}

[type=submit]:hover {
    -webkit-appearance: button;
    background: #05bc24;
    text-align: center;
    border-radius: 30px;
    padding: 12px;
    border: solid 1px #05bc24;
    color: #fff;
     transition: 0.5s ease;
}

.col-md-8 {
    margin: auto;
}

.box div {
    background: #ffcece;
    border: solid 1px red;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    margin-bottom: 25px;
    color: #000;
}
 .error {
    border: solid 1px red;
    border-radius: 5px;
    padding: 10px;
    color: red;
    margin-bottom: 20px;
    margin-top: 20px;
    font-size: 14px;
}
 </style>
                <div class="col-md-8">
                    <h3>פתח אתר חדש</h3>
                    '.(!empty($errors) ? '<div class="error">'.$errors.'</div>' : '').'
                    <p>באפשרותך לפתוח עד 5 אתרים נוספים על המשתמש שלך.</p>
                    <form action="" method="POST">
                        <input type="hidden" name="css" value="1">
                        <label for="">כתובת אתר (URL):<input type="text" name="siteurl"></label><br>
                        <label for="">שם אתר:<input type="text" name="logo"></label><br>
                        <button type="submit" name="createSite">פתח אתר</button>
                    </form>
                </div>
                ';
                }
                    
            }
            
            break;
	    case 'pay':
	        if (!$user->checkAuthentication()) {
	            header('location: '.ABS_PATH);
                die;
	        }
	        
	        $itemName = $_POST['item_name'];
	        $itemNumber = $_POST['item_number'];
	        $amount = $_POST['amount'];
	        $siteid = $_POST['siteid'];
	        
	        $siteExists = false;
	        $site = $db->prepare('SELECT * FROM buildp_sites WHERE userID=?');
	        $site->execute(array($userloggined));
	        $sites = $site->fetchAll();
	        foreach ($sites as $site) {
	            if ($site['id'] == $siteid) {
	                $siteExists = true;
	            }
	        }
	        
	        if (!$siteExists) {
	            header('location: '.ABS_PATH.'?do=mysites');
	            die;
	        }
	        
	        $data = array(
	            'item_name' => $itemName,
	            'item_number' => $itemNumber,
	            'amount' => $amount,
	            'currency' => 'ILS'
	            );
	        $payment->createPayment($data);
	        
	        break;
	   case 'success': // A user bought premium for his site successfully.
	   echo '
<style type="text/css">.done {
    max-width: 50%;
    margin: auto;
    padding: 15px;
    border-radius: 10px;
    background: white;
}
</style>
<div class="done">
<p style="text-align: center;"><span style="font-size:28px;">התשלום בוצע בהצלחה!</span><br />
<br />
<img alt="Check free icon" src="https://image.flaticon.com/icons/svg/1828/1828640.svg" style="max-width: 50%; width: 20%;" /><br />
<br />
<span style="font-size:14px;">בקשת התשלום שלך עבור <strong>חבילת פרימיום</strong>&nbsp;לאתרך בוצעה בהצלחה!<br />
ברגע שנאשר את התשלום&nbsp;הפרימיום יופעל אוטומטית באתר.</span><br />
<br />
<span style="font-size:16px;"><strong>תודה שבחרת בנו,<br />
צוות WebsMaking</strong></span></p>

<div style="color: #fff; background: #2196f3; max-width: fit-content; padding: 15px; margin: auto; text-align: center; margin-top: 20px; border-radius: 5px; border-bottom: solid 3px #1a79c5; outline: none; cursor: pointer;"><a href="https://www.websmaking.com/?do=mysites" style="color: white;">חזרה לאתרים שלי</a></div>
</div>
	   ';
	        break;
	   case 'cancel': // User cancelled his order of premium.
            break;
	   case 'verify-paypal': // PP IPN
	        
	        // Handles requestes from PayPal's server.
	        //include dirname(__FILE__).'/includes/paypal/payment.php';
            
            require_once(dirname(__FILE__).'/includes/paypal/PaypalIPN.php');
            $ipn = new PaypalIPN();
            
            $verified = $ipn->verifyIPN();
            
            $data_text = "";
            foreach ($_POST as $key => $value) {
                $data_text .= $key . " = " . $value . "\r\n";
            }
            
            $test_text = "";
            if ($_POST["test_ipn"] == 1) {
                $test_text = "Test ";
            }
            
            // Check the receiver email to see if it matches your list of paypal email addresses
            $receiver_email_found = false;
            foreach ($my_email_addresses as $a) {
                if (strtolower($_POST["receiver_email"]) == strtolower($a)) {
                    $receiver_email_found = true;
                    break;
                }
            }
            
            date_default_timezone_set("Asia/Jerusalem");
            list($year, $month, $day, $hour, $minute, $second, $timezone) = explode(":", date("Y:m:d:H:i:s:T"));
            $date = $year . "-" . $month . "-" . $day;
            $timestamp = $date . " " . $hour . ":" . $minute . ":" . $second . " " . $timezone;
            $dated_log_file_dir = $log_file_dir . "/" . $year . "/" . $month;
            
            $paypal_ipn_status = "VERIFICATION FAILED";
            if ($verified) {
                $paypal_ipn_status = "RECEIVER EMAIL MISMATCH";
                if ($receiver_email_found) {
                    $paypal_ipn_status = "Completed Successfully";
            
            
                    // Process IPN
                    // A list of variables are available here:
                    // https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
                    
                    if ($_POST["payment_status"] == "Completed") {
                        $siteid = $_POST['custom'];
                        $itemName = $_POST["item_name"];
                        $price = $_POST["mc_gross"];
                        $currency = $_POST["mc_currency"];
                        
                        $firstName = $_POST["first_name"];
                        $surname = $_POST["last_name"];
                        $date = $_POST['payment_date'];
                        $db->prepare('INSERT INTO `payments`(`first_name`,`last_name`,`creation_date`,`price`,`siteid`) VALUES (?,?,?,?,?)')->execute($firstName, $surname, $date, $price, $siteid);
                        
                        //$email_to = $_POST["first_name"] . " " . $_POST["last_name"] . " <" . $_POST["payer_email"] . ">";
                        //$email_subject = $test_text . "Completed order for: " . $_POST["item_name"];
                        //$email_body = "Thank you for purchasing " . $_POST["item_name"] . "." . "\r\n" . "\r\n" . "This is an example email only." . "\r\n" . "\r\n" . "Thank you.";
                        //mail($email_to, $email_subject, $email_body, "From: " . $from_email_address);
                    }
            
            
                }
            }
            
            if ($send_confirmation_email) {
                // Send confirmation email
                mail($confirmation_email_address, $test_text . "PayPal IPN : " . $paypal_ipn_status, "paypal_ipn_status = " . $paypal_ipn_status . "\r\n" . "paypal_ipn_date = " . $timestamp . "\r\n" . $data_text, "From: " . $from_email_address);
            }
            
            header("HTTP/1.1 200 OK");
            break;
	   case 'premium':
	       $siteid = (isset($_GET['siteid']) ? $_GET['siteid'] : null);
	       
	       if (is_null($siteid)) {
	           header('location: '.ABS_PATH.'?do=mysites');
	           die;
	       }
	       
	       // https://pastebin.com/s9GDGM6F
	       echo '
	       <style type="text/css">.header {
    z-index: 99999;
}
body {font-family: font-weight: normal; font-style: normal; font-size: 15px; line-height: 1.5; letter-spacing: 1px;}

h2,h4 {
    font-family: \'Dosis\', sans-serif;
	font-style: normal;
    margin-bottom: 10px;
    font-weight: 500;
    color: #1c1d3e;
}
#canvas {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0.5;
}
.pos-r {
    position: relative;
}
.section-title{margin-bottom:50px; position: relative;}
.section-title p{font-size: 16px;}
.title-effect {width: 50px; height: 50px; top: 0; position: absolute; left: 0; opacity: 0.5; -webkit-animation: rotation 12.8s steps(1) 0s infinite; animation: rotation 12.8s steps(1) 0s infinite;}
.text-center .title-effect{left: 50%; margin-left: -25px; display:none;}
.text-center .section-title h6{padding: 15px 0;}

.title-effect .bar {background: #2575fc;}
.title-effect .bar-top {width: 100%; height: 7px; position: absolute; top: 0; left: 0; -webkit-transform-origin: left top; transform-origin: left top; -webkit-transform: scale(0, 1); transform: scale(0, 1); -webkit-animation: bar-top 3.2s linear 0s infinite; animation: bar-top 3.2s linear 0s infinite;}
.title-effect .bar-right {width: 7px; height: 100%; position: absolute; top: 0; right: 0; -webkit-transform-origin: left top; transform-origin: left top; -webkit-transform: scale(1, 0); transform: scale(1, 0); -webkit-animation: bar-right 3.2s linear 0s infinite; animation: bar-right 3.2s linear 0s infinite;}
.title-effect .bar-bottom {width: 100%; height: 7px; position: absolute; right: 0; bottom: 0; -webkit-transform-origin: right top; transform-origin: right top; -webkit-transform: scale(0, 1); transform: scale(0, 1); -webkit-animation: bar-bottom 3.2s linear 0s infinite; animation: bar-bottom 3.2s linear 0s infinite;}
.title-effect .bar-left {width: 7px; height: 100%; position: absolute; left: 0; bottom: 0; -webkit-transform-origin: left bottom; transform-origin: left bottom; -webkit-transform: scale(1, 0); transform: scale(1, 0); -webkit-animation: bar-left 3.2s linear 0s infinite; animation: bar-left 3.2s linear 0s infinite;}

.title {position: relative; color: #1c1d3e; margin-bottom: 0;}
.section-title h2{margin-bottom: 15px;  color: #dc0049;}


/*--rotation--*/

@-webkit-keyframes rotation {
  0% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
  25% {
    -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
  }
  50% {
    -webkit-transform: rotate(180deg);
            transform: rotate(180deg);
  }
  75% {
    -webkit-transform: rotate(270deg);
            transform: rotate(270deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}

@keyframes rotation {
  0% {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
  25% {
    -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
  }
  50% {
    -webkit-transform: rotate(180deg);
            transform: rotate(180deg);
  }
  75% {
    -webkit-transform: rotate(270deg);
            transform: rotate(270deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}
@-webkit-keyframes bar-top {
  0% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
  12.5% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  87.5% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  100% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
}
@keyframes bar-top {
  0% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
  12.5% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  87.5% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  100% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
}
@-webkit-keyframes bar-right {
  0% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  12.5% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  25% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  75% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  87.5% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  100% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
}
@keyframes bar-right {
  0% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  12.5% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  25% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  75% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  87.5% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  100% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
}
@-webkit-keyframes bar-bottom {
  0% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
  25% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
  37.5% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  62.5% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  75% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
  100% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
}
@keyframes bar-bottom {
  0% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
  25% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
  37.5% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  62.5% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  75% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
  100% {
    -webkit-transform: scale(0, 1);
            transform: scale(0, 1);
  }
}
@-webkit-keyframes bar-left {
  0% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  37.5% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  50% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  62.5% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  100% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
}
@keyframes bar-left {
  0% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  37.5% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  50% {
    -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
  }
  62.5% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
  100% {
    -webkit-transform: scale(1, 0);
            transform: scale(1, 0);
  }
}


/* ------------------------
    Price Table
------------------------*/
.price-table{padding: 50px 30px; border-radius: 7px; overflow: hidden; position: relative; background: #ffffff; text-align: center;
    box-shadow: 0px 0px 15px 0px rgba(72,73,121,0.15);
    transition: all 0.5s ease-in-out 0s;}
.price-title{text-transform: uppercase; font-weight: 700; color: #51915a;}
.price-header{position: relative; z-index: 9;}
.price-value {display: inline-block; width: 100%;}
.price-value h2 {font-size: 60px; line-height: 60px; font-weight: 800; color: #1c1d3e; margin-bottom: 0; position: relative; display: inline-block;}
.price-value h2 span {font-size: 26px; left: -15px; line-height: 24px; margin: 0; position: absolute; top: 10px; color: #5f5f5f; font-weight: normal;}
.price-value span {margin: 15px 0; display: block; color: #5f5f5f;}
.price-list ul li {position: relative; display: block; margin-bottom: 20px; color: #5f5f5f;}
.price-list ul li:last-child{margin-bottom: 0;}
.dark-bg .price-list ul li{color: rgba(255,255,255,0.8);}
.price-list ul li:last-child{margin-right: 0;}
.price-list li i {color: #2575fc; line-height: 20px; font-size: 20px;}

.price-inside {font-size: 80px; line-height: 80px; position: absolute; left: 85%; top: 50%; -webkit-transform: translateX(-50%) translateY(-50%) rotate(-90deg); transform: translateX(-50%) translateY(-50%) rotate(-90deg); font-weight: 900; color: rgba(0,0,0,0.040);}
.price-table::before {background: #f7f7f7; content: ""; height: 300px; left: -25%; position: absolute; top: -10%; -webkit-transform: rotate(-10deg); transform: rotate(-10deg); width: 150%;}
.price-table.active::before{-webkit-transform: rotate(10deg);transform: rotate(10deg);}

.price-table.style-2{background: rgba(255,255,255,0.020); box-shadow: none;}
.price-table.style-2::before {background: rgba(255,255,255,0.030); top: 50%; -webkit-transform: translateY(-50%) rotate(-10deg); transform: translateY(-50%) rotate(-10deg);}
.price-table.style-2 .price-title{color:#005bea;}
.price-table.style-3.active .price-title{color:#ffffff;}
.price-table.style-2 .price-value h2, .price-table.style-3.active .price-value h2{color: #ffffff;}
.price-table.style-2 .price-list{margin-top: 20px;}
.price-table.style-2.active::before{-webkit-transform: rotate(0);transform: rotate(0);}
.price-table.style-2 .price-inside{color: rgba(255,255,255,0.040);}
.price-table.style-2 .btn, .price-table.style-3.active .btn{-webkit-box-shadow: 0 10px 20px rgba(255,255,255,0.1);}

.price-table.active{padding: 70px 30px;}
.price-table.style-3.active{background: #2575fc; color: #ffffff;}
.price-table.active .price-value h2 span{color: #ffffff;}
.price-table.style-3:before, .price-table.style-4:before{display: none;}
.price-table.style-3 .price-list{margin-top: 25px;}

.price-table.style-4 .price-list{margin: 25px 0;}
.price-table.style-4 h3{text-transform: capitalize;}

.btn{padding: 12px 25px; font-weight: 500; background: none; color: #1c1d3e; overflow: hidden; border-radius: 7px; border: none; position: relative; z-index: 9; transition: all 0.5s ease-in-out 0s; box-shadow: 0px 0px 15px 0px rgba(72,73,121,0.15);}
.btn.btn-theme { background: rgb(0, 210, 195); background: linear-gradient(90deg, rgb(5, 204, 186) 0%, rgb(81, 145, 90) 80%); color: #ffffff; }
.btn.focus, .btn:focus{box-shadow: none;}
.btn:hover {-webkit-transform: translateY(-3px);transform: translateY(-3px);}
.btn.btn-circle{border-radius: 30px;}

.btn span {display: inline-block; opacity: 0; -webkit-transform: translate3d(10, 10px, 0); transform: translate3d(10, 10px, 0);transition-timing-function: cubic-bezier(0.75, 0, 0.125, 1);}
 .price-table:hover{ box-shadow: 0px 20px 50px 0px rgba(153, 153, 153, 0.5);}

.btn::before {content: attr(data-text); height: 100%; left: 0; position: absolute; top: 0; transition: all 0.3s cubic-bezier(0.75, 0, 0.125, 1) 0s; width: 100%; padding: 12px 0;}
.btn:hover:before {opacity: 0; -webkit-transform: translate3d(0, 100%, 0); transform: translate3d(0, 100%, 0);}
.btn:hover span{opacity:1; -webkit-transform:translate3d(0, 0, 0); transform:translate3d(0, 0, 0)}
.btn:hover span:nth-child(1){ transition-delay:0.01s}
.btn:hover span:nth-child(2){ transition-delay:0.05s}
.btn:hover span:nth-child(3){ transition-delay:0.1s}
.btn:hover span:nth-child(4){ transition-delay:0.15s}
.btn:hover span:nth-child(5){ transition-delay:0.2s}
.btn:hover span:nth-child(6){ transition-delay:0.25s}
.btn:hover span:nth-child(7){ transition-delay:0.3s}
.btn:hover span:nth-child(8){ transition-delay:0.35s}
.btn:hover span:nth-child(9){ transition-delay:0.4s}
.btn:hover span:nth-child(10){ transition-delay:0.45s}
.btn:hover span:nth-child(11){ transition-delay:0.5s}
.btn:hover span:nth-child(12){ transition-delay:0.55s}
.btn:hover span:nth-child(13){ transition-delay:0.6s}
.btn:hover span:nth-child(14){ transition-delay:0.65s}
.btn:hover span:nth-child(15){ transition-delay:0.7s}
.btn:hover span:nth-child(16){ transition-delay:0.75s}
.btn:hover span:nth-child(17){ transition-delay:0.8s}
.btn:hover span:nth-child(18){ transition-delay:0.85s}
.btn:hover span:nth-child(19){ transition-delay:0.95s}
.btn:hover span:nth-child(20){ transition-delay:1s}
</style>
<script>
  window.console = window.console || function(t) {};
</script><script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>
<section class="pos-r" id="pricing"><canvas height="1018" id="canvas" width="1519"></canvas>
<div class="container">
<div class="row text-center">
<div class="col-lg-8 col-md-12 ml-auto mr-auto">
<div class="section-title">
<div class="title-effect">
<div class="bar bar-top">&nbsp;</div>

<div class="bar bar-right">&nbsp;</div>

<div class="bar bar-bottom">&nbsp;</div>

<div class="bar bar-left">&nbsp;</div>
</div>

<h6>תוכניות פרימיום</h6>

<h2 class="title">בחר את החבילה שלך</h2>

<p>חבילות במחירים נוחים לכל כיס במיוחד בשבילך!</p>
</div>
</div>
</div>

<div class="row align-items-center">
<div class="col-lg-4 col-md-12">
<div class="price-table">
<div class="price-header">
<div class="price-value">
<h2><span>₪</span>30.00</h2>
<span>לתקופת המנוי</span></div>

<h3 class="price-title">חבילה חודשית</h3>
</div>

<form action="https://www.websmaking.com/?do=pay" method="POST">
<input type="hidden" name="item_name" value="WebsMaking Premium - 1 Month">
<input type="hidden" name="item_number" value="1mon">
<input type="hidden" name="amount" value="1">
<input type="hidden" name="siteid" value="'.$siteid.'">
<button type="submit">
<a class="btn btn-theme btn-circle my-4" data-text="לא זמין כרגע" href="#"><span>ל</span><span>ד</span><span>ף</span> <span>ה</span><span>ר</span><span>כ</span><span>י</span><span>ש</span><span>ה</span> <span>ל</span><span>ח</span><span>צ</span><span>ו</span> </a>
</button>
</form>

<div class="price-list">
<ul class="list-unstyled">
	<li>חיבור דומיין</li>
	<li>יצירת דפים ללא הגבלה</li>
	<li>תבניות בלעדיות לפרימיום</li>
	<li>אפשרויות מתקדמות יותר</li>
	<li>כלי SEO מתקדם</li>
	<li>ללא פרסומות באתר</li>
	<li>ניהול URL ידידותי</li>
	<li>תעודת SSL חינם</li>
	<li>תמיכה באימייל ובצ&#39;אט Live</li>
</ul>
</div>
</div>
</div>

<div class="col-lg-4 col-md-12 md-mt-5">
<div class="price-table active">
<div class="price-header">
<div class="price-value">
<h2><span style="color:#5f5f5f;">₪</span>לא זמין</h2>
<span>לתקופת המנוי</span></div>

<h3 class="price-title">תלת חודשי</h3>
</div>
<form action="https://www.websmaking.com/?do=pay" method="POST">
<input type="hidden" name="item_name" value="WebsMaking Premium - 3 Months">
<input type="hidden" name="item_number" value="3mon">
<input type="hidden" name="amount" value="96">
<input type="hidden" name="siteid" value="'.$siteid.'">
<button type="submit">
<a class="btn btn-theme btn-circle my-4" data-text="לא זמין כרגע" href="#" onclick="this.form.submit();"><span>ל</span><span>ד</span><span>ף</span> <span>ה</span><span>ר</span><span>כ</span><span>י</span><span>ש</span><span>ה</span> <span>ל</span><span>ח</span><span>צ</span><span>ו</span> </a>
</button>
</form>

<div class="price-list">
<ul class="list-unstyled">
	<li>חיבור דומיין</li>
	<li>יצירת דפים ללא הגבלה</li>
	<li>תבניות בלעדיות לפרימיום</li>
	<li>אפשרויות מתקדמות יותר</li>
	<li>כלי SEO מתקדם</li>
	<li>ללא פרסומות באתר</li>
	<li>ניהול URL ידידותי</li>
	<li>תעודת SSL חינם</li>
	<li>תמיכה באימייל ובצ&#39;אט Live</li>
</ul>
</div>
</div>
</div>

<div class="col-lg-4 col-md-12 md-mt-5">
<div class="price-table">
<div class="price-header">
<div class="price-value">
<h2><span style="color:#5f5f5f;">₪</span>לא זמין</h2>
<span>לתקופת המנוי</span></div>

<h3 class="price-title">שנתי</h3>
</div>

<form action="https://websmaking.com/?do=pay" method="POST">
<input type="hidden" name="item_name" value="WebsMaking Premium - A year">
<input type="hidden" name="item_number" value="12mon">
<input type="hidden" name="amount" value="384">
<input type="hidden" name="siteid" value="'.$siteid.'">
<button type="submit">
<a class="btn btn-theme btn-circle my-4" data-text="לא זמין כרגע" href="#" onclick="this.form.submit();"><span>ל</span><span>ד</span><span>ף</span> <span>ה</span><span>ר</span><span>כ</span><span>י</span><span>ש</span><span>ה</span> <span>ל</span><span>ח</span><span>צ</span><span>ו</span> </a>
</button>
</form>

<div class="price-list">
<ul class="list-unstyled">
	<li>חיבור דומיין</li>
	<li>יצירת דפים ללא הגבלה</li>
	<li>תבניות בלעדיות לפרימיום</li>
	<li>אפשרויות מתקדמות יותר</li>
	<li>כלי SEO מתקדם</li>
	<li>ללא פרסומות באתר</li>
	<li>ניהול URL ידידותי</li>
	<li>תעודת SSL חינם</li>
	<li>תמיכה באימייל ובצ&#39;אט Live</li>
</ul>
</div>
</div>
</div>
</div>
</div>
</section>

<p style="text-align: center; background: #d38f8f; width: 50%; margin: auto; padding: 5px; color: #fff;   margin-top: 40px;">תשלום חד פעמי בלבד. לא ניתן לפרוס לתשלומים.</p>
<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script><script src=\'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js\'></script><script id="rendered-js">
      var Canvas = document.getElementById(\'canvas\');
var ctx = Canvas.getContext(\'2d\');

var resize = function () {
  Canvas.width = Canvas.clientWidth;
  Canvas.height = Canvas.clientHeight;
};
window.addEventListener(\'resize\', resize);
resize();

var elements = [];
var presets = {};

presets.o = function (x, y, s, dx, dy) {
  return {
    x: x,
    y: y,
    r: 12 * s,
    w: 5 * s,
    dx: dx,
    dy: dy,
    draw: function (ctx, t) {
      this.x += this.dx;
      this.y += this.dy;

      ctx.beginPath();
      ctx.arc(this.x + +Math.sin((50 + x + t / 10) / 100) * 3, this.y + +Math.sin((45 + x + t / 10) / 100) * 4, this.r, 0, 2 * Math.PI, false);
      ctx.lineWidth = this.w;
      ctx.strokeStyle = \'#f44d85\';
      ctx.stroke();
    } };

};

presets.x = function (x, y, s, dx, dy, dr, r) {
  r = r || 0;
  return {
    x: x,
    y: y,
    s: 20 * s,
    w: 5 * s,
    r: r,
    dx: dx,
    dy: dy,
    dr: dr,
    draw: function (ctx, t) {
      this.x += this.dx;
      this.y += this.dy;
      this.r += this.dr;

      var _this = this;
      var line = function (x, y, tx, ty, c, o) {
        o = o || 0;
        ctx.beginPath();
        ctx.moveTo(-o + _this.s / 2 * x, o + _this.s / 2 * y);
        ctx.lineTo(-o + _this.s / 2 * tx, o + _this.s / 2 * ty);
        ctx.lineWidth = _this.w;
        ctx.strokeStyle = c;
        ctx.stroke();
      };

      ctx.save();

      ctx.translate(this.x + Math.sin((x + t / 10) / 100) * 5, this.y + Math.sin((10 + x + t / 10) / 100) * 2);
      ctx.rotate(this.r * Math.PI / 1500);

      line(-1, -1, 1, 1, \'#f44d85\');
      line(1, -1, -1, 1, \'#481ea7\');

      ctx.restore();
    } };

};

for (var x = 0; x < Canvas.width; x++) {
  for (var y = 0; y < Canvas.height; y++) {
    if (Math.round(Math.random() * 25000) == 1) {
      var s = (Math.random() * 5 + 1) / 10;
      if (Math.round(Math.random()) == 1)
      elements.push(presets.o(x, y, s, 0, 0));else

      elements.push(presets.x(x, y, s, 0, 0, (Math.random() * 3 - 1) / 10, Math.random() * 360));
    }
  }
}

setInterval(function () {
  ctx.clearRect(0, 0, Canvas.width, Canvas.height);

  var time = new Date().getTime();
  for (var e in elements)
  elements[e].draw(ctx, time);
}, 10);
      //# sourceURL=pen.js
    </script>
	       ';
	       
	       if (isset($_POST['payButton'])) {
	           
	           
	       }
	       
	       
	       break;
	    case 'logout':
	        if (!$user->checkAuthentication()) {
	            header('location: '.ABS_PATH);
                die;
	        }
	        
	        
	        $user->logoutUser();
            
            header('location: '.ABS_PATH.'index.php?do=login');
            die;
            break;
        case 'security':
            if (!$user->checkAuthentication()) {
                header('location: '.ABS_PATH);
                die;
            }
	    
    	    global $func;
    	    if (isset($_POST['changePassword'])) {
    	        $oPass = $func->protectxss($_POST['oldPassword']);
    	        $nPass = $func->protectxss($_POST['newPassword']);
    	        $cNPass = $func->protectxss($_POST['confirmNewPassword']);
    	        
    	        $salt = $user->getUser($user->getUsernameByID($userloggined))['salt'];
    	        $hash = hash('sha256', $salt.md5($oPass));
    	        
    	        $q1 = $db->prepare('SELECT * FROM `buildp_users` WHERE password=?');
    	        $q1->execute(array($hash));
    	        
    	        if ($q1->rowCount() < 1) {
    	            $errors = $lang['panel']['security']['errors']['oldPasswordDoesntMatch'];
    	        }else { 
    	            if ($nPass != $cNPass) {
    	                $errors = $lang['panel']['security']['errors']['PasswordsDontMatch'];
    	            }else {
    	                
    	                $nHash = hash('sha256', $salt.md5($nPass));
    	                
    	                $db->prepare('UPDATE `buildp_users` SET password=? WHERE id=?')->execute(array($nHash, $userloggined));
    	                unset($_SESSION['AUTHSESS']);
                        unset($_SESSION['siteid']);
                        // session_destroy();
                        
                        header('location: '.ABS_PATH.'index.php?do=login');
                        die;
    	            }
    	        }
    	        
    	    }
    	    
    	    echo '
    	    <style type="text/css">
    	        .box div {
        background: #ffcece;
        border: solid 1px red;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        margin-bottom: 25px;
    }
    
input:focus {
    outline: auto;
}

    	                .head-block {
    background-image: url(https://images.unsplash.com/photo-1464746133101-a2c3f88e0dd9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1327&q=80);
    height: 185px;
    margin-bottom: 30px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    opacity: 0.2;
    margin-top: -46px;
}
.head-block2 {
    background-image: -webkit-linear-gradient(45deg,#780206,#061161);
    background-image: linear-gradient(45deg,#780206,#061161);
    background-repeat: repeat-x;
}

.col-md-8 input[type="password"] {
    width: 250px;
    margin-right: 0px;
    border-radius: 5px;
    font-size: 13pt;
    padding: 4px;
    border: 1px solid #b0b4b8;
    color: #000000;
    margin-top: 10px;
    margin-bottom: 20px;
    display: block;
}

[type=submit] {
    -webkit-appearance: button;
    background: none;
    text-align: center;
    border-radius: 30px;
    padding: 12px;
    border: solid 1px #05bc24;
    color: #05bc24;
}

[type=submit]:hover {
    -webkit-appearance: button;
    background: #05bc24;
    text-align: center;
    border-radius: 30px;
    padding: 12px;
    border: solid 1px #05bc24;
    color: #fff;
     transition: 0.5s ease;
}

.col-md-8 {
    margin: auto;
}

.box div {
    background: #ffcece;
    border: solid 1px red;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    margin-bottom: 25px;
    color: #000;
}
 </style>
	                <div class="head-block2">
<div class="head-block">&nbsp;</div>
</div>
    	    <div class="col-md-8">
            <h3>שינוי סיסמה</h3><br>
                <div class="box">
    	    
        	    <form action="" method="POST">
            	    '.(isset($errors) ? '<div class="">'.$errors.'</div>' : '').'
            	    <label for="">'.$lang['panel']['security']['oldPassword'].':<input type="password" name="oldPassword"></label><br>
            	    <label for="">'.$lang['panel']['security']['newPassword'].':<input type="password" name="newPassword"></label><br>
            	    <label for="">'.$lang['panel']['security']['confirmNewPassword'].':<input type="password" name="confirmNewPassword"></label><br>
            	    <button type="submit" name="changePassword">'.$lang['panel']['security']['changePasswordButton'].'</button>
        	    </form>
    	    </div>
    	    </div>
    	    
    	    ';
    	    break;
        case 'contact':
            
            if (isset($_POST['sendContact'])) {
                $errors = '';
                
                $to = 'support@websmaking.com';
                $subject = $func->protectxss($_POST['subject']);
                $message = $func->protectxss($_POST['message']);
                //https://www.google.com/recaptcha/api/siteverify
	            $cap = (isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : null);
                
                if (empty($subject)) {
                    $errors = 'חייב למלא את נושא הפנייה.';
                }
                if (empty($message)) {
                    $errors = 'ההודעה חייבת להכיל תוכן.';
                }
                
                
                $json = json_decode($func->sendRequest('https://www.google.com/recaptcha/api/siteverify', array( 'secret' => '6Le4-MMUAAAAAEVuUiE_AFFuNLL9ZH8zeqk9bnRd', 'response' => $cap, 'remoteip' => $_SERVER['REMOTE_ADDR'] )));
                if (!$json->{'success'}) {
                    $errors = '<div class="alert alert-warning">חייב למלא reCAPTCHA</div>';
                }
                
                if (empty($errors)) {
                    $message = 'שם מלא:'.$func->protectxss($_POST['first_name']).'<br>'.
                                'דואר אלקטרוני: '.$func->protectxss($_POST['email']).'<br>'.
                                'נושא הפנייה: '.$func->protectxss($_POST['subject']).'<br>'.
                                'תוכן הפנייה: '.$message;
                    
                    $func->sendMail($to, $subject, $message);
                }
            }
            
            echo '
                
                <style type="text/css">.head-block {
                    background-image: url(https://images.unsplash.com/photo-1487132906645-8e0fbba067e0?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80);
                }
                .head-block2 {
                    background-image: -webkit-linear-gradient(45deg,#6a7133,#26465c);
                    background-image: linear-gradient(45deg,#6a7133,#26465c);
                    background-repeat: repeat-x;
                }
                </style>
                <div class="head-block2">
                    <div class="head-block">&nbsp;</div>
                    </div>
                    <style type="text/css">input[type="text"] {
                        margin: 8px 0;
                        display: inline-block;
                        border: 1px solid #ccc;
                        box-shadow: inset 0 1px 3px #ddd;
                        border-radius: 4px;
                        -webkit-box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        box-sizing: border-box;
                        font-size: 12pt;
                        padding: 10px;
                        font-family: rubik, sans-serif;
                        outline: none;
                        -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
                        transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
                        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
                    }
                    input[type="text"]:focus {
                        color: #000;
                        border: 1px solid #6c63ff;
                    }
                    
                    [type="submit"] {
                        -webkit-appearance: button;
                        display: inline-block;
                        margin-bottom: 0;
                        font-weight: normal;
                        text-align: center;
                        white-space: nowrap;
                        vertical-align: middle;
                        -ms-touch-action: manipulation;
                        touch-action: manipulation;
                        cursor: pointer;
                        background-image: none;
                        border: 1px solid transparent;
                        padding: 6px 12px;
                        font-size: 14px;
                        line-height: 1.42857143;
                        border-radius: 4px;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        user-select: none;
                        background-color: #50905a;
                        border-color: #3b8146;
                        color: #fff;
                        outline: none;
                        margin-top: 25px;
                        margin-bottom: 5px;
                    }
                    
                    [type="submit"]:hover{
                      background: #3b8146;
                      border-color: #50905a;
                    }
                    
                    .alert-warning, .alert-success {
                        max-width: 50%;
                        margin: auto;
                    }
                    
                    
                    </style>
                    
                    '.(isset($errors) ? (empty($errors) ? '<div class="alert alert-success">ההודעה נשלחה בהצלחה</div>' : $errors) : '').'
                    
                    <p style="text-align: center;"><span style="font-size:20px;"><strong>צור קשר</strong></span></p>
                    
                    <p style="text-align: center;"><span style="font-size:16px;">באפשרותכם לפנות אלינו בצור קשר ופנייתכם תענה ע&quot;י נציג שירות&nbsp;בהקדם האפשרי.</span></p>
                    
                    <form action="" method="POST">
                        <p style="text-align: center;"><input name="first_name" placeholder="שם מלא" required="required" type="text" /></p>
                        
                        <p style="text-align: center;"><input name="email" placeholder="דואר אלקטרוני" required="required" type="text" /></p>
                        
                        <p style="text-align: center;"><input name="subject" placeholder="נושא הפנייה" required="required" type="text" /></p>
                        
                        <p style="text-align: center;"><input name="message" placeholder="תוכן ההודעה" required="required" type="text" /></p>
                        
                         <center><div class="g-recaptcha" data-sitekey="6Le4-MMUAAAAAOkYGJvFXdUmy81-zY2HEOCzCm3v"></div></center>
                        
                        <p style="text-align: center;"><button type="submit" name="sendContact">שליחה</button></p>
                    </form>
                    
                    <p style="text-align: center;"><br />
                    <strong><a href="https://www.websmaking.com/?do=abuse">דיווח על שימוש לרעה? לחצו כאן</a></strong></p>
                
                </div>
	        
	           <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	            ';
            
            break;
		default:
		    
		    if (sizeof($pages) > 0) {
		        $page = $pages[0];
		        if ($page['isUser'] == 1 && $user->checkAuthentication()) {
		            echo $page['content'];
    		    }else if ($page['isUser'] == 0) {
    		        echo $page['content']; 
    		    }/*else if ($page['isUser'] == 1 && !$user->checkAuthentication()) {
    		        header('location: '.ABS_PATH.'?do=login&redirect='.urlencode($do));
		            die;
    		    }*/else {
    		        header('location: '.ABS_PATH);
		            die;
    		    }
		    
    		    }else{
		    echo '<style>
		    @media screen and (max-width: 992px) {
    .header {
        background: #00d2c7;
    }
}
		    </style>
		<div id="welcome">
                <h1>צור את אתרך בחינם עוד היום!</h1>
                <h2>מבחר תבניות מקצועיות ורספונסיביות (מותאמות לנייד) זמינות עבורך!</br>
יצירת אתר למגוון רחב של מטרות, החל מאתר תדמיתי ועד אתר עסקי</h2>
            <div class="start">'.($user->checkAuthentication() ? '<a href="'.ABS_PATH.'dashboard.php"><div class="get-start">לניהול אתרך</div></a>' : '<a href="/?do=register"><div class="get-start">התחל עכשיו</div></a>').'</div></div>
        	</div>	<div class="image-wel"><center><img src="https://i.websmaking.com/images/welcome.png""></center></div>
        	<div id="manage-website">
        		<div class="text-manage-website">
        			<h1>נהל את האתר שלך בכל מקום, אפילו בנייד</h1>
        			<h2>האתר שלכם איתכם בכל מקום שתלכו, לעדכן בכל מקום ללא מגבלות!</h2>
        			<div class="start">'.($user->checkAuthentication() ? '<a href="'.ABS_PATH.'dashboard.php"><div class="get-start2">לניהול אתרך</div></a>' : '<a href="/?do=register"><div class="get-start2">התחל עכשיו</div></a>').'</div>
        			<img src="https://i.websmaking.com/images/phones.png" style="max-width: 80%;width: auto;">
        		</div>
        	</div></div>
        	</div>
		    ';
		    }
		    
		    
		    /* --- DELETE [For Matan: DONT TOUCH THESE LINES !!!]
			echo '
            <h1>'.$lang['index']['main']['titleOne'].'</h1>
            <h3>'.$lang['index']['main']['subTitleOne'].'</h3>
            <div class="size"><a href="/index.php?do=register"><div class="create-free">'.$lang['index']['main']['registerButton'].'</i></div></a></div>
            <img src="'.ABS_PATH.'images/banner_img.png" style=" width: 40%; float: left; margin-top: -15px; ">
            <div class="why-us">
                <h1>'.$lang['index']['main']['titleTwo'].'</h1>
                <h3>'.$lang['index']['main']['subTitleTwo'].'</h3>
                <div class="size"><a href="/index.php?do=register"><div class="create-free">'.$lang['index']['main']['registerButton'].'</i></div></a></div>
                <img src="'.ABS_PATH.'images/review_bg.png" style=" width: 28%;     margin-top: 50px;">
            </div>
            <div class="mobile">
            <div class="mobiletxt">
                <h1>'.$lang['index']['main']['titleThree'].'</h1>
                <h3>'.$lang['index']['main']['subTitleThree'].'</h3>
            <div class="size"><a href="/index.php?do=register"><div class="create-free">'.$lang['index']['main']['registerButton'].'</i></div></a></div>
            </div>
            </div>
            <div class="support">
                <h1>'.$lang['index']['main']['titleFour'].'</h1>
                <h3>'.$lang['index']['main']['subTitleFour'].'</h3>
                <div class="size"><a href="/index.php?do=register"><div class="create-free">'.$lang['index']['main']['registerButton'].'</i></div></a></div>
                <img src="'.ABS_PATH.'images/about_img_1.png" style="width: 28%;float: right;margin-top: -15%;">
            </div>
            <div class="footer">
            <br>
            <a href="'.ABS_PATH.'?lang='.$langName.'">
            <div style=" letter-spacing: 0px; font-weight: bold; ">'.$lang['index']['main']['changeLanguage'].' <img src="'.ABS_PATH.$flag.'"></div>
            </a>
                '.$lang['index']['main']['copyright'].'<br>
                <!--
                <a href="'.ABS_PATH.'changelog.html" style="font-weight: bold;background:none;font-size: 13px;">BreatheCMS Change log</a>
                <a href="https://trello.com/b/TZrjKmAD/wesmaking" style="font-weight: bold;background:none;font-size: 13px;">BreatheCMS TODO.</a>
                <span>ETH:&nbsp;<span style="border-bottom: 1px dotted #000;">0xC52f1fCEC85De514e2D4E8d475Ec162037874f90</span></span>
                -->
            </div>
			';
			*/
			break;
}

$normalModeLogo = '<img src="https://i.websmaking.com/images/custom-logo.svg" style="width: 110px;">';
$darkModeLogo = '<img src="https://i.websmaking.com/images/white-logo.png" style="width: 110px;">';
echo '
	</div>
	
    <div class="footer">
	  <div style="margin: auto; width: 71%; text-align: center;">
	    <!-- Start dark mode. -->
	    <style type="text/css"> .darkMode_link a { border-bottom: 1px #f0f0 dotted; } .darkMode_link { text-aling: center; } </style>
        <!-- <span class="darkMode_link">Dark Mode:&nbsp;'.( isset($_SESSION['dark']) ? ( $_SESSION['dark'] == 'on' ? '<a href="'.ABS_PATH.'?dark=off">On</a>' : '<a href="'.ABS_PATH.'?dark=on">Off</a>') : '<a href="'.ABS_PATH.'?dark=on">Off</a>' ).'</span><br> -->
        <br><!-- End dark mode. -->
	    
		'.(!is_null($sessDarkMode) ? ($sessDarkMode == 'on' ? '<img src="https://i.websmaking.com/images/white-logo.png" style="width: 110px;">' : '<img src="https://i.websmaking.com/images/white-logo.png" style="width: 110px;">' ) : ( !is_null($darkMode) ? ( $darkMode == 'on' ? '<img src="https://i.websmaking.com/images/white-logo.png" style="width: 110px;">' : '<img src="https://i.websmaking.com/images/white-logo.png" style="width: 110px;">') : '<img src="https://i.websmaking.com/images/white-logo.png" style="width: 110px;">' )).'
		| העיצוב תוכנן וקודד ע"י WebsMaking Solutions
	  </div>
	  <hr>
	  <div class="footer-width">
		<div class="about">
			'.(!is_null($sessDarkMode) ? ($sessDarkMode == 'on' ? '<img src="https://i.websmaking.com/images/white-logo.png" style="max-width: 80%;width: auto;">' : '<img src="https://i.websmaking.com/images/custom-logo.svg" style="max-width: 80%;width: auto;">' ) : ( !is_null($darkMode) ? ( $darkMode == 'on' ? '<img src="https://i.websmaking.com/images/white-logo.png" style="max-width: 80%;width: auto;">' : '<img src="https://i.websmaking.com/images/white-logo.png" style="max-width: 80%;width: auto;">') : '<img src="https://i.websmaking.com/images/white-logo.png" style="max-width: 80%;width: auto;">' )).'
			<br>
                WebsMaking הינו שירות לבניית אתרי אינטרנט בחינם עם מערכת ניהול תוכן ידידותית וקלה לשימוש. אנו ב- WebsMaking ראינו צורך בלפתח מערכת כזו בשביל לתת מענה למשתמש שאין לו כל ידע קודם בתכנות ובכך לאפשר לכל אדם לפתוח  אתר עסקי/אישי משלו בכמה דקות ובלי בעיה.
                עדיין אין לך אתר באינטרנט? לחץ <a href="'.ABS_PATH.'?do=register">כאן</a> לפתיחת אתר בחינם!
            
		</div>
		<div class="links">
		  <div style="float: right;">
			<h3>גלה</h3>
			    <li><a href="https://www.websmaking.com/?do=contact">צור קשר</a></li>
			    <li><a href="https://www.websmaking.com/?do=blog">בלוג</a></li>
				<li><a href="https://www.websmaking.com/?do=premium">פרימיום</a></li>
				<li><a href="https://www.websmaking.com/?do=abuse">שימוש לרעה</a></li>
			</div>
			<div style="float: right;">
			<h3>עיון</h3>
			    <li><a href="/?do=terms">תקנון האתר</a></li>
				<li><a href="/?do=privacy">מדיניות פרטיות</a></li>
			    <li><a href="/help">מרכז עזרה</a></li>
			   <li><a href="https://www.websmaking.com/?do=blog/%D7%A7%D7%99%D7%93%D7%95%D7%9D-%D7%90%D7%95%D7%A8%D7%92%D7%A0%D7%99-%D7%90%D7%95-%D7%9E%D7%9E%D7%95%D7%9E%D7%9F-seo">SEO</a></li>
			</div>
			<div style="float: right;">
			<h3>קהילה</h3>
			    <li><a href="#">פייסבוק</a></li>
				<li><a href="https://twitter.com/websmaking">טוויטר</a></li>
				<li><a href="#">אינסטגרם</a></li>
			</div>
		</div>
		</div>
	</div>

</body>
</html>
';

// ****************
// * Footer Layout 
// ****************
include 'includes/layouts/index_footer.php';

$db->close();

/* --- DELETE [For Matan: DONT TOUCH THESE LINES !!!]
<a href="/index.php?do=login"><div class="login">'.$lang['index']['main']['loginButton'].'</div></a>
    <div class="menuburger"><input type="checkbox" id="menu-toggle"/>
    <label id="trigger" for="menu-toggle"></label>
    <label id="burger" for="menu-toggle"></label>
    <ul id="menu">
        <li><a href="'.ABS_PATH.'">'.$lang['index']['menu'][0].'</a></li>
        <li><a href="'.ABS_PATH.'?do=about">'.$lang['index']['menu'][1].'</a></li>
        <li><a href="'.ABS_PATH.'?do=premium">'.$lang['index']['menu'][2].'</a></li>
        <li><a href="'.ABS_PATH.$lang['index']['menu']['help_center'][0].'">'.$lang['index']['menu']['help_center'][1].'</a></li>
        <li><a href="mailto:support@websmaking.com?subject=WebsMaking Support">'.$lang['index']['menu'][3].'</a></li>
    </ul></div>
*/
?>

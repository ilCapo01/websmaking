<?php
ob_start();
require_once(dirname(__FILE__).'/includes/config.php');
global $db, $user, $lang;

if(!$user->checkAuthentication()){
  header('location: '.ABS_PATH.'index.php?do=login&redirect='.urlencode($_SERVER['REQUEST_URI']));
  die;
}

/*****************************
 * Drag & Drop Editor:
 * https://github.com/givanz/VvvebJs
 * 
 ******************************/

$do = (isset($_GET['do']) ? $_GET['do'] : null);

// Cookie. 
//$userloggined = (isset(explode('-', $_SESSION['AUTHSESS'])[1]) ? explode('-', $_SESSION['AUTHSESS'])[1] : null);
$userloggined = (isset(explode('-', $_COOKIE['AUTHSESS'])[1]) ? explode('-', $_COOKIE['AUTHSESS'])[1] : null);
if (is_null($userloggined) || !isset($_COOKIE['AUTHSESS'])) {
    header('location: '.ABS_PATH);
    die;
}
$usersiteid = (isset($_SESSION['siteid']) ? $_SESSION['siteid'] : null);

// User's data.
//=============
$q2 = $db->prepare("SELECT * FROM `buildp_users` WHERE id=?");
$q2->execute(array($userloggined));
$b = $q2->fetch(); 
$group = $b['group'];
$uname = $b['username'];

if ($group == 1) {
    // Main site's data.
    $q2 = $db->prepare('SELECT * FROM buildp_setting');
    $q2->execute();
    $s = $q2->fetch();
}

if (!is_null($usersiteid)) {
    // Site's data.
    //=============
    //$q2 = $db->prepare("SELECT * FROM `buildp_sites` where userID=?");
    //$q2->execute(array($userloggined));
    $q2 = $db->prepare("SELECT * FROM `buildp_sites` where id=?");
    $q2->execute(array($usersiteid)); 
    $row = $q2->fetch();
    $url = $row['siteurl'];
    $cssID = $row['themeID'];
    $premium = $row['isPremium'];
}else {
    if ($group == 2) {
        header('location: '.ABS_PATH.'?do=mysites');
        die;
    }
}

// 2FA - Google Authenticator.
// https://github.com/PHPGangsta/GoogleAuthenticator
// WYSIWYG EDITOR
// https://froala.com/wysiwyg-editor


//  FontAwesome for ckeditor
// https://ckeditor.com/cke4/addon/ckeditorfa

// Load the CKEDITOR 4.
$load_ckeditor = '
        <script>
            /*CKEDITOR.plugins.addExternal( \'emoji\', \'https://websmaking.com/vendor/ckeditor/plugins/emoji/\', \'plugin.js\' );
            CKEDITOR.plugins.addExternal( \'autocomplete\', \'https://websmaking.com/vendor/ckeditor/plugins/autocomplete/\', \'plugin.js\' );
            CKEDITOR.plugins.addExternal( \'textmatch\', \'https://websmaking.com/vendor/ckeditor/plugins/textmatch/\', \'plugin.js\' );
            CKEDITOR.plugins.addExternal( \'textwatcher\', \'https://websmaking.com/vendor/ckeditor/plugins/textwatcher/\', \'plugin.js\' );
            CKEDITOR.plugins.addExternal( \'ajax\', \'https://websmaking.com/vendor/ckeditor/plugins/ajax/\', \'plugin.js\' );
            CKEDITOR.plugins.addExternal( \'xml\', \'https://websmaking.com/vendor/ckeditor/plugins/xml/\', \'plugin.js\' );*/
            
            CKEDITOR.dtd.$removeEmpty[\'i\'] = false;
            
            CKEDITOR.replace( \'editor1\', {
                allowedContent: true,
                //emoji_emojiListUrl: \''.ABS_PATH.'vendor/ckeditor/plugins/emoji/emoji.json\', 
                //extraPlugins: \'emoji,autocomplete,textmatch,textwatcher,ajax,xml\',
                autoParagraph: false
            });
            
        
        </script>';

// --- Start HTML --- 
echo '
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
  	<meta name="viewport" content="width=device-width, initial-scale=1">
      <title>'.$lang['panel']['title'].'</title>
  	<link rel="stylesheet" href="https://i.websmaking.com/css/'.$lang['css']['panel'].'">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
	<script src="//cdn.ckeditor.com/4.12.1/full/ckeditor.js"></script> 
	<!-- <script src="//websmaking.com/vendor/ckeditor/ckeditor.js"></script> -->
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<!--
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146851516-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag(\'js\', new Date());
    
      gtag(\'config\', \'UA-146851516-1\');
    </script>
    --> 
</head>
<body>
<div class="header">
	<div class="inside_header">
		<a href="/"><img src="https://i.websmaking.com/images/custom-logo.svg?v=0.1" style=" width: 195px; user-select: none;"></a>
	</div>
</div>

<div class="w3-sidebar w3-bar-block w3-animate-left" style="display:none;z-index:5" id="mySidebar">
    <button class="w3-bar-item w3-button w3-large" onclick="w3_close()">'.$lang['panel']['menu']['close'].' &times;</button>
';
if ($group == 2):
echo '
		<li><a href="'.ABS_PATH.'dashboard.php"><i class="fa fa-th"></i> '.$lang['panel']['menu']['dashboard'].'</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=pages"><i class="fas fa-copy"></i> '.$lang['panel']['menu']['all_pages'].'</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=newpage"><i class="fas fa-file"></i> '.$lang['panel']['menu']['new_page'].'</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=header"><i class="fas fa-heading"></i> '.$lang['panel']['menu']['edit_top_section'].'</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=sidebar"><i class="fa fa-arrow-circle-right"></i> '.$lang['panel']['menu']['edit_side_bar'].'</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=footer"><i class="fas fa-shoe-prints"></i> '.$lang['panel']['menu']['edit_bottom_section'].'</a></li>
		<!--<li><a href="'.ABS_PATH.'dashboard.php?do=users"><i class="fas fa-users-cog"></i> ניהול משתמשים</a></li>-->
		<li><a href="'.ABS_PATH.'dashboard.php?do=style"><i class="fas fa-paint-brush"></i> '.$lang['panel']['menu']['edit_theme'].'</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=settings"><i class="fas fa-cog"></i> '.$lang['panel']['menu']['site_settings'].'</a></li>
		'.($row['isPremium'] == 1 ? '<li><a href="'.ABS_PATH.'dashboard.php?do=domain"><i class="fas fa-globe"></i> חיבור דומיין</a></li>' : '').'
		<li><a href="'.ABS_PATH.'?do=adding"><i class="fa fa-rocket"></i> תוספים</a></li>
		<li><a href="'.ABS_PATH.$url.'" target="_blank"><i class="fas fa-eye"></i> '.$lang['panel']['menu']['watch_site'].'</a></li>
		<li><a href="'.ABS_PATH.'?do=security"><i class="fa fa-lock"></i></i> '.$lang['panel']['menu']['security'].'</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=api"><i class="fa fa-server"></i> API</a></li>
	    <li><a href="https://www.websmaking.com/help/p/147371"><i class="fas fa-support"></i> '.$lang['panel']['menu']['support'].'</a></li>
	    <!-- <li><a href="'.ABS_PATH.'dashboard.php?do='.$do.'&lang='.$langName.'"><i class="fa fa-language"></i> '.$lang['index']['main']['changeLanguage'].'</a> -->
';
elseif($group == 1):
echo '
		<li><a href="'.ABS_PATH.'dashboard.php?do=users"><i class="fas fa-support"></i> ניהול משתמשים</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=mainsettings"><i class="fas fa-support"></i> הגדרות אתר</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=ads"><i class="fas fa-support"></i> ניהול פרסומות</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=themes"><i class="fas fa-support"></i> ניהול תבניות</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=sitepages"><i class="fas fa-paint-brush"></i> ניהול דפים באתר הראשי</a></li> <!-- doesnt showen up for some reason, check this out !!! -->
		<li><a href="'.ABS_PATH.'dashboard.php?do=sites"><i class="fas fa-support"></i> ניהול אתרים</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=updates"><i class="fas fa-support"></i> ניהול עדכונים</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=api"><i class="fa fa-server"></i> ניהול API</a></li>
';
endif;
echo '
		<li><a href="'.ABS_PATH.'?do=logout"><i class="fas fa-sign-out-alt"></i> '.$lang['panel']['menu']['logout'].'</a></li>
		
	</div>
    <div class="header_menu">
        <div class="w3-overlay w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>
        
        <div>
           <a href="'.ABS_PATH.'dashboard.php"> <button class="w3-button w3-white w3-xxlarge">&#9776;</button> </a>
        </div>
    </div>

  <div class="content">
';

switch ($do) {
  case 'editor':
      
      $editorPath = dirname(__FILE__).'/includes/VvvebJs/';
      $editorURL = ABS_PATH.'includes/VvvebJs/';
      
      echo '
    <!-- jquery-->
    <script src="'.$editorURL.'js/jquery.min.js"></script>
    <script src="'.$editorURL.'js/jquery.hotkeys.js"></script>
    
    <!-- bootstrap-->
    <script src="'.$editorURL.'js/popper.min.js"></script>
    <script src="'.$editorURL.'js/bootstrap.min.js"></script>
    
    <!-- builder code-->
    <script src="'.$editorURL.'libs/builder/builder.js"></script>	
    <!-- undo manager-->
    <script src="'.$editorURL.'libs/builder/undo.js"></script>	
    <!-- inputs-->
    <script src="'.$editorURL.'libs/builder/inputs.js"></script>	
    <!-- components-->
    <script src="'.$editorURL.'libs/builder/components-bootstrap4.js"></script>	
    <script src="'.$editorURL.'libs/builder/components-widgets.js"></script>	
    
    
    <script>
$(document).ready(function() 
{
	Vvveb.Builder.init(\''.$editorURL.'demo/blog/index.html\', function() {
		//load code after page is loaded here
		Vvveb.Gui.init();
	});
});
</script>
      ';
      
      //include $editorPath.'editor.html';
      
      break;
  case 'templatepages':
    global $func;
      
    if ($premium) { //$row['isPremium']
        $imgURL = '';
        $description = '';
        $keywords = '';
        $customURL = 'contact';
        
        if (preg_match('/\\s/', $customURL)) { // Filter out spaces, and replace with a dash.
            $customURL = preg_replace('/\\s/', '-', $customURL);
        }
        
        $stmt = $db->prepare('SELECT * FROM buildp_pages WHERE customURL=?');
        $stmt->execute(array($url));
        if (count($stmt->fetchAll()) > 0) {
            $errors = 'הכתובת קיימת כבר באתר.';
            header('location: '.ABS_PATH.'dashboard.php');
            die;
        }
    
    }else {
        $description = $keywords = $customURL = $imgURL = '';
        
        $stmt = $db->prepare('SELECT * FROM buildp_pages WHERE type=? AND siteid=?');
        $stmt->execute(array('contact', $row['id']));
        if (count($stmt->fetchAll()) > 0) {
            header('location: '.ABS_PATH.'dashboard.php');
            die;
        }
    }
    $stmt = $db->prepare('SELECT * FROM buildp_pages WHERE siteid=? ORDER BY orderNum ASC');
    $stmt->execute(array($row['id']));
    $pages = $stmt->fetchAll();
    $order = $pages[count($pages)-1]['orderNum']+1;
    
    srand(time());
    $db->prepare("INSERT INTO buildp_pages (`id`, `siteid`, `pagename`, `text`, `time`, `previewImageURL`, `seo_description`, `seo_keywords`, `customURL`, `orderNum`, `type`) VALUES(?,?,?,?,?,?,?,?,?,?,?)")->execute(array(rand(111111, 999999), $row['id'], $name, $text, time(), $imgURL, $description, $keywords, $customURL, $order, $pageType));
    echo '<script>alert("הדף נוצר בהצלחה!");</script>
    <meta http-equiv="refresh" content="0; url='.ABS_PATH.'dashboard.php?do=newpage" />';
    //header('location: '.ABS_PATH.'dashboard.php?do=newpage');
    die;
      
    break;
    
  case 'newthemepage':
      $pageType = (isset($_GET['type']) ? $func->protectxss($_GET['type']) : null);
      global $func;
      $name = 'Contact';
      $text = '';
      $errors = '';
      
      if (is_null($pageType)) {
          $errors = 'לא ניבחר סוג דף.';
      }
      
      if (in_array($pageType, array('contact'))) {
          $errors = 'סוג הדף שאתה מנסה ליצור לא קיים.';
      }
      
      switch ($pageType) {
                    case 'contact':
                        $name = 'צור קשר';
                        $text = '<style type="text/css">.head-block {
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
	</div>';
                        break;
                }
                
                if (empty($errors)) {
                    $stmt = $db->prepare('SELECT * FROM buildp_pages WHERE siteid=? ORDER BY orderNum ASC');
                    $stmt->execute(array($row['id']));
                    $pages = $stmt->fetchAll();
                    $order = $pages[count($pages)-1]['orderNum']+1;
            
                    srand(time());
                    $db->prepare("INSERT INTO buildp_pages (`id`, `siteid`, `pagename`, `text`, `time`, `previewImageURL`, `seo_description`, `seo_keywords`, `customURL`, `orderNum`, `type`) VALUES(?,?,?,?,?,?,?,?,?,?,?)")->execute(array(rand(111111, 999999), $row['id'], $name, $text, time(), '', '', '', '', $order, $pageType));
                    echo '<script>alert("הדף נוצר בהצלחה!");</script>
                    <meta http-equiv="refresh" content="0; url='.ABS_PATH.'dashboard.php" />';
                    //header('location: '.ABS_PATH.'dashboard.php');
                    die;
                }
      break;
      
  case 'newpage':
    
    if (isset($_POST['addSubmit'])) {
        
        global $func;
        $name = $func->protectxss($_POST['pageName']);
        $text = $_POST['text'];
        $pageType = $func->protectxss($_POST['type']);
        
        if (empty($name)) {
            $errors = 'שם הדף לא יכול להישאר ריק.';
        }else {
            if (empty($text)) {
                $errors = 'תוכן הדף לא יכול להישאר ריק.';
            }else {
                if ($premium) { //$row['isPremium']
                    $imgURL = $func->protectxss($_POST['imageURL']);
                    $description = $func->protectxss($_POST['description']);
                    $keywords = $func->protectxss($_POST['keywords']);
                    $customURL = $func->protectxss($_POST['url']);
                    
                    if (preg_match('/\\s/', $customURL)) { // Filter out spaces, and replace with a dash.
                        $customURL = preg_replace('/\\s/', '-', $customURL);
                    }
                    
                    $stmt = $db->prepare('SELECT * FROM buildp_pages WHERE customURL=? AND siteID=?');
                    $stmt->execute(array($url, $usersiteid));
                    if (sizeof($stmt->fetchAll()) > 0) {
                        $errors = 'הכתובת קיימת כבר באתר.';
                        //header('location: '.ABS_PATH.'dashboard.php?do=pages&id='.$page['id']);
                        //die;
                    }
                
                }else {
                    $description = $keywords = $customURL = $imgURL = '';
                }
                
                if (empty($errors)) {
                    $stmt = $db->prepare('SELECT * FROM buildp_pages WHERE siteid=? ORDER BY orderNum ASC');
                    $stmt->execute(array($row['id']));
                    $pages = $stmt->fetchAll();
                    $order = $pages[count($pages)-1]['orderNum']+1;
            
                    srand(time());
                    $db->prepare("INSERT INTO buildp_pages (`id`, `siteid`, `pagename`, `text`, `time`, `previewImageURL`, `seo_description`, `seo_keywords`, `customURL`, `orderNum`, `type`) VALUES(?,?,?,?,?,?,?,?,?,?,?)")->execute(array(rand(111111, 999999), $row['id'], $name, $text, time(), $imgURL, $description, $keywords, $customURL, $order, $pageType));
                    echo '<script>alert("הדף נוצר בהצלחה!");</script>
                    <meta http-equiv="refresh" content="0; url='.ABS_PATH.'dashboard.php?do=newpage" />';
                    //header('location: '.ABS_PATH.'dashboard.php?do=newpage');
                    die;
                }
            }
        }
    }

    echo '
    <style>
      /*
     CSS for the main interaction
    */
    .tabset > input[type="radio"] {
        position: inherit;
        display: none;
    }
    .tabset .tab-panel {
      display: none;
    }
    
    .tabset > input:first-child:checked ~ .tab-panels > .tab-panel:first-child,
    .tabset > input:nth-child(3):checked ~ .tab-panels > .tab-panel:nth-child(2),
    .tabset > input:nth-child(5):checked ~ .tab-panels > .tab-panel:nth-child(3),
    .tabset > input:nth-child(7):checked ~ .tab-panels > .tab-panel:nth-child(4),
    .tabset > input:nth-child(9):checked ~ .tab-panels > .tab-panel:nth-child(5),
    .tabset > input:nth-child(11):checked ~ .tab-panels > .tab-panel:nth-child(6) {
      display: block;
    }
    
    /*
     Styling
    */
    
    .tabset > label {
      position: relative;
      display: inline-block;
      padding: 15px 15px 25px;
      border: 1px solid transparent;
      border-bottom: 0;
      cursor: pointer;
      font-weight: 600;
    }
    
    .tabset > label::after {
      content: "";
      position: absolute;
      left: 15px;
      bottom: 10px;
      width: 22px;
      height: 4px;
      background: #8d8d8d;
    }
    
    .tabset > label:hover,
    .tabset > input:focus + label {
      color: #06c;
    }
    
    .tabset > label:hover::after,
    .tabset > input:focus + label::after,
    .tabset > input:checked + label::after {
      background: #06c;
    }
    
    .tabset > input:checked + label {
      border-color: #ccc;
      border-bottom: 1px solid #fff;
      margin-bottom: -1px;
    }
    
    .tab-panel {
      padding: 30px 0;
      border-top: 1px solid #ccc;
    }
    
    /*
     Demo purposes only
    */
    *,
    *:before,
    *:after {
      box-sizing: border-box;
    }
    
    .tabset {
      max-width: 65em;
    }
    </style>
    
    <div class="col-md-8">
    <h3>'.$lang['panel']['new_page']['title'].'</h3>
        <div class="box">
            <form action="" method="POST">
                <input type="hidden" name="type" value="page">
                <label for="">'.$lang['panel']['new_page']['page_name'].':&nbsp;<input type="text" name="pageName"></label><br>
                <label for="pageText">'.$lang['panel']['new_page']['page_content'].':&nbsp;<br><textarea id="editor1" name="text" rows="10" cols="80"></textarea></label><br><br>

                '.($premium == 1 ? '
                <div class="tabset">
                
                    <input type="radio" name="tabset" id="tab1" aria-controls="marzen" checked>
                    <label for="tab1">SEO</label>
                    
                    <input type="radio" name="tabset" id="tab2" aria-controls="rauchbier">
                    <label for="tab2">תמונה ראשית</label>
                    
                    <input type="radio" name="tabset" id="tab3" aria-controls="dunkles">
                    <label for="tab3">URL מותאם אישית</label>
                
                    <div class="tab-panels">
                        <section id="marzen" class="tab-panel">
                            <h2>SEO</h2>
                            <p><label for="">תיאור הדף (description):<input type="text" name="description" value="" placeholder="רשום בקצרה על הדף"></label></p>
                            <label for="">מילות מפתח (keywords):<input type="text" name="keywords" value="" placeholder="הפרד מילים בפסיק"></label>
                        </section>
                        <section id="rauchbier" class="tab-panel">
                            <h2>תמונה ראשית</h2>
                            <label for="pagePreviewImage"><div style="font-size: 8pt; color: #949494;">תמונה ראשית תציג את הדף בתצוגה מוקדמת ברשתות החברתיות.</div><input type="text" name="imageURL" value=""></label>
                        </section>
                        <section id="dunkles" class="tab-panel">
                            <h2>URL מותאם אישית</h2>
                            <label for=""><div style="font-size: 8pt; color: #949494;">האפשרות זמינה רק באתרים עם דומיין מותאם אישית.</div><input type="text" name="url" value="" placeholder="URL"></label>
                        </section>
                    </div>
                </div>' : '').'
                
                
                <button type="submit" name="addSubmit">'.$lang['panel']['new_page']['createPageButton'].'</button>
            </form>
        </div>
    </div>
    '.$load_ckeditor.'
    ';
    break;
  case 'pages':
      // CKEDITOR DOCUMENTATION:
      // https://ckeditor.com/docs/ckeditor4/latest/index.html
      
      // TODO: Before deleting ask if the user is aware of what he's about to do.
      //https://www.websmaking.com/blog/page/9264https://stackoverflow.com/questions/9334636/how-to-create-a-dialog-with-yes-and-no-options#9334684
    
    $pageID = (isset($_GET['id']) ? $_GET['id'] : null);
    
    
    if (is_null($pageID)) {
        // All pages.
        
        echo '
        <style type="text/css">
        .title {
            font-weight: bold;
            font-size: 30px;
        }
        
        .box input[type="text"] {
    overflow: visible;
    display: block;
    padding: 5px;
    font-size: 13px;
    line-height: 1.42857143;
    color: #555555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    outline: none;
    width: 35px;
    float: right;
    margin-left: 10px;
    height: 27px;
}
.box button, [type=button], [type=reset], [type=submit] {
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
    padding: 3px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: #337ab7;
    border-color: #2e6da4;
    color: #fff;
    outline: none;
    margin-top: 0px;
    margin-bottom: 0px;
    margin-left: 5px;
    float: right;
}
        </style>
        <div class="col-md-8">
        <h3>'.$lang['panel']['all_pages']['page_management'].'</h3>
        <div class="box">';
        
        $q1 = $db->prepare('SELECT * FROM buildp_pages WHERE siteid=? ORDER BY `orderNum` ASC'); //time ASC
        $q1->execute(array($row['id']));
        $pages = $q1->fetchAll();

        for ($i=0; $i<sizeof($pages); $i++) {
            $page = $pages[$i];
            
            $inMenu = ($page['inMenu'] == 1 ? 'false' : 'true');
            
            $mainPage = ($page['pagename'] == 'דף ראשי' ? true : false);
            //if ($page['pagename'] == 'דף ראשי') continue;
            
            echo '
<style>
.box {
    padding-top: 15px;
}
.box b, strong {
    background: #ffffff;
    border-radius: 30px;
    padding: 5px;
    padding-right: 15px;
    padding-left: 15px;
    color: #397ed1;
    border: solid 1px #397ed1;
    font-weight: normal;
    margin-right: 5px;
}

.box b, strong:hover {
  background: #397ed1;
  border: solid 1px #397ed1; 
  color: #fff;
  
}

@media screen and (max-width: 992px) {
.box b, strong {
    background: #ffffff;
    border-radius: 30px;
    padding: 5px;
    padding-right: 9px;
    padding-left: 0px;
    color: #397ed1;
    border: solid 0px #397ed1;
    font-weight: normal;
    margin-right: 0px;
}

.box b, strong:hover {
  background: #fff;
  border: solid 0px #397ed1; 
  color: #1f62b3;
  
}

}

.box button, [type=button], [type=reset], [type=submit] {
    margin-left: 10px;
}
</style>
            <div class="">
            <form action="" method="POST"><input type="hidden" name="pageID" value="'.$page['id'].'"><input type="text" name="order" value="'.$page['orderNum'].'"><button type="submit" name="changeOrder">שמור סדר</button></form>
            '.$page['pagename'].'
            <a href="'.ABS_PATH.'dashboard.php?do=pages&id='.$page['id'].'"><strong><i class="far fa-edit"></i> '.$lang['panel']['all_pages']['edit_page'].'</strong></a>
            '.($mainPage ? '' : '<a href="'.ABS_PATH.'/dashboard.php?do=pages&id='.$page['id'].'&r=true" onclick="confirmAction(event)"><strong><i class="fa fa-trash"></i> '.$lang['panel']['all_pages']['delete_page'].'</strong></a>').'
            '.($mainPage ? '' : '<a href="'.ABS_PATH.'/dashboard.php?do=pages&id='.$page['id'].'&display='.$inMenu.'">'.($page['inMenu'] == 1 ? '<strong><i class="fa fa-eye-slash"></i> '.$lang['panel']['all_pages']['hide_page_from_menu'].'</strong>' : '<strong><i class="fa fa-eye"></i> '.$lang['panel']['all_pages']['show_page_in_menu'].'</strong>').'</a>').'
            '.($mainPage ? '<a href="'.(empty($row['domain']) ? ABS_PATH.$url : 'http://'.$row['domain']).'/" target="_blank"><strong><i class="fas fa-external-link-alt"></i> '.$lang['panel']['all_pages']['watch_page'].'</strong></a>' : '<a href="'.(empty($row['domain']) ? ABS_PATH.$url : 'http://'.$row['domain']).'/'.(empty($page['customURL']) ? $page['id'] : $page['customURL'] ).'" target="_blank"><strong><i class="fas fa-external-link-alt"></i> '.$lang['panel']['all_pages']['watch_page'].'</strong></a>').'
            <hr>
            </div>
            ';
        }
        
        if (isset($_POST['changeOrder'])) {
            global $func;
            
            $pageID = $func->protectxss($_POST['pageID']);
            $orderNum = $func->protectxss($_POST['order']);
            
            $stmt = $db->prepare('UPDATE `buildp_pages` SET orderNum=? WHERE id=?');
            $stmt->execute(array($orderNum, $pageID));
            
            header('location: '.$_SERVER['HTTP_REFERER']);
            die;
        }
        
        echo '
        <script type="text/javascript">
        function confirmAction(event) {
            
            if (!confirm("'.$lang['panel']['all_pages']['delete_warning'].'")) {
                event.preventDefault();
            }
            
        }
        </script>
        ';
        
    }else {
        
        $pageOwnership = $db->prepare('SELECT * FROM `buildp_pages` WHERE id=?');
        $pageOwnership->execute(array($pageID));
        $pageOwnership = $pageOwnership->fetch();
        
        if ($pageOwnership['siteid'] != $row['id']){
            header('location: '.ABS_PATH.'dashboard.php?do=pages');
            die;
        }
        
        $r = (isset($_GET['r']) ? $_GET['r'] : null);
        if (!is_null($r) && $r == 'true') {  // Delete a specific page.
            
            $q2 = $db->prepare("SELECT * FROM `buildp_pages` WHERE siteid=? order by time Asc ");
            $q2->execute(array($row['id']));
            $main = $q2->fetch();
            
            if ($main['id'] == $pageID) {
                header('location: '.ABS_PATH.'dashboard.php?do=pages');
                die;
            }
            
            
            $db->prepare('DELETE FROM buildp_pages WHERE id=?')->execute(array($pageID));
            
            header('location: '.ABS_PATH.'dashboard.php?do=pages');
            die;
        }else { // Edit a specific page.
            
            $inMenu = (isset($_GET['display']) ? $_GET['display'] : null);
            if (!is_null($inMenu) && ($inMenu == 'true' || $inMenu == 'false')) {
                
                $q2 = $db->prepare("SELECT * FROM `buildp_pages` WHERE siteid=? order by time Asc ");
                $q2->execute(array($row['id']));
                $main = $q2->fetch();
                
                if ($main['id'] == $pageID) {
                    header('location: '.ABS_PATH.'dashboard.php?do=pages');
                    die;
                }
                
                $inMenu = ($inMenu == 'true' ? 1 : 0);
                $db->prepare('UPDATE `buildp_pages` SET inMenu=? WHERE id=?')->execute(array($inMenu, $pageID));
                
                header('location: '.ABS_PATH.'dashboard.php?do=pages');
                die;
            }else {
             
                $q1 = $db->prepare('SELECT * FROM buildp_pages WHERE id=?');
                $q1->execute(array($pageID));
                $page = $q1->fetch();
            
                if (isset($_POST['changeSubmit'])) {
                    global $func;
                    
                    $pageName = (isset($_POST['pagename']) ? $func->protectxss($_POST['pagename']) : null);
                    $text = $_POST['text'];
                    
                    
                    // Black list certain JS commands, in order to prevent hackers from harm doing.
                    $blacklist = array('document.cookie');
                    $text = str_replace($blacklist, '', $text);
                    
                    if(empty($pageName)) {
                        $errors = 'שם הדף לא יכול להישאר ריק.';
                    }else {
                        if (empty($text)) {
                            $errors = 'תוכן הדף לא יכול להישאר ריק.';
                        }else {
                            
                            if ($row['isPremium']) {
                                $imgURL = $func->protectxss($_POST['imageURL']);
                                
                                $description = $func->protectxss($_POST['description']);
                                $keywords = $func->protectxss($_POST['keywords']);
                                
                                $customURL = $func->protectxss($_POST['url']);
                                
                                if (preg_match('/^[/ \\]$/', $customURL)) {
                                    $customURL = preg_replace('/^[/ \\]$/', '-', $customURL);
                                }
                                
                                $stmt = $db->prepare('SELECT * FROM buildp_pages WHERE customURL=? AND siteID=?');
                                $stmt->execute(array($url, $usersiteid));
                                if (count($stmt->fetchAll()) > 0) {
                                    $errors = 'הכתובת קיימת כבר באתר.';
                                    header('location: '.ABS_PATH.'dashboard.php?do=pages&id='.$page['id']);
                                    die;
                                }
                                
                                $q2 = $db->prepare("SELECT * FROM `buildp_pages` WHERE siteid=? order by time Asc ");
                                $q2->execute(array($usersiteid));
                                $mainPage = $q2->fetch();
                                if (mainPage['id'] == $pageID)
                                    return;
                            
                            }else {
                                $description = $keywords = $customURL = $imgURL = '';
                            }
                            
                            $mainPage = ($page['pagename'] == 'דף ראשי' ? true : false);
                            if ($mainPage) { $pageName = $page['pagename']; }
                            
                            $db->prepare('UPDATE buildp_pages SET pagename=?, text=?, previewImageURL=?, seo_description=?, seo_keywords=?, customURL=? WHERE id=?')->execute(array($pageName, $text, $imgURL, $description, $keywords, $customURL, $pageID));
                            header('location: '.ABS_PATH.'dashboard.php?do=pages&id='.$pageID);
                            die;
                        }
                    }
                }
            
            }
        }
        
        $mainPage = ($page['pagename'] == 'דף ראשי' ? true : false);
        
        echo '
        <style type="text/css">
        </style>
        
        '.(isset($errors) ? '<div class="error">'.$errors.'</div>' : '').'
        <div class="col-md-8">
        <h3>'.$lang['panel']['edit_page']['title'].': '.$page['pagename'].'</h3>
            <div class="box">
            <form action="" method="POST">
                <label for="pageName">'.$lang['panel']['edit_page']['page_name'].':&nbsp;<input type="text" name="pagename" value="'.$page['pagename'].'"></label><br>
                <label for="pageText">'.$lang['panel']['edit_page']['page_content'].':&nbsp;<br><textarea id="editor1" name="text" rows="10" cols="80">'.$page['text'].'</textarea></label><br>
                
                '.($row['isPremium'] == true ? '
<style>
  /*
 CSS for the main interaction
*/
.tabset > input[type="radio"] {
    position: inherit;
    display: none;
}
.tabset .tab-panel {
  display: none;
}

.tabset > input:first-child:checked ~ .tab-panels > .tab-panel:first-child,
.tabset > input:nth-child(3):checked ~ .tab-panels > .tab-panel:nth-child(2),
.tabset > input:nth-child(5):checked ~ .tab-panels > .tab-panel:nth-child(3),
.tabset > input:nth-child(7):checked ~ .tab-panels > .tab-panel:nth-child(4),
.tabset > input:nth-child(9):checked ~ .tab-panels > .tab-panel:nth-child(5),
.tabset > input:nth-child(11):checked ~ .tab-panels > .tab-panel:nth-child(6) {
  display: block;
}

/*
 Styling
*/

.tabset > label {
  position: relative;
  display: inline-block;
  padding: 15px 15px 25px;
  border: 1px solid transparent;
  border-bottom: 0;
  cursor: pointer;
  font-weight: 600;
}

.tabset > label::after {
  content: "";
  position: absolute;
  left: 15px;
  bottom: 10px;
  width: 22px;
  height: 4px;
  background: #8d8d8d;
}

.tabset > label:hover,
.tabset > input:focus + label {
  color: #06c;
}

.tabset > label:hover::after,
.tabset > input:focus + label::after,
.tabset > input:checked + label::after {
  background: #06c;
}

.tabset > input:checked + label {
  border-color: #ccc;
  border-bottom: 1px solid #fff;
  margin-bottom: -1px;
}

.tab-panel {
  padding: 30px 0;
  border-top: 1px solid #ccc;
}

/*
 Demo purposes only
*/
*,
*:before,
*:after {
  box-sizing: border-box;
}

.tabset {
  max-width: 65em;
}
</style>

<div class="tabset">

<input type="radio" name="tabset" id="tab1" aria-controls="marzen" checked>
<label for="tab1">SEO</label>

<input type="radio" name="tabset" id="tab2" aria-controls="rauchbier">
<label for="tab2">תמונה ראשית</label>

'.($mainPage['id'] != $pageID ? '<input type="radio" name="tabset" id="tab3" aria-controls="dunkles">
<label for="tab3">URL מותאם אישית</label>' : '').'

<div class="tab-panels">
<section id="marzen" class="tab-panel">
<h2>SEO</h2>
                <p><label for="">תיאור הדף (description):<input type="text" name="description" value="'.$page['seo_description'].'" placeholder="רשום בקצרה על הדף"></label></p>
                <label for="">מילות מפתח (keywords):<input type="text" name="keywords" value="'.$page['seo_keywords'].'" placeholder="הפרד מילים בפסיק"></label>
</section>
<section id="rauchbier" class="tab-panel">
<h2>תמונה ראשית</h2>
                <label for="pagePreviewImage"><div style="font-size: 8pt; color: #949494;">תמונה ראשית תציג את הדף בתצוגה מוקדמת ברשתות החברתיות.</div><input type="text" name="imageURL" value="'.$page['previewImageURL'].'"></label>

</section>

'.($mainPage['id'] != $pageID ? '<section id="dunkles" class="tab-panel">
<h2>URL מותאם אישית</h2>
   <label for=""><div style="font-size: 8pt; color: #949494;">האפשרות זמינה רק באתרים עם דומיין מותאם אישית.</div><input type="text" name="url" value="'.$page['customURL'].'" placeholder="URL"></label>
</section>' : '').'
</div>
</div>
                ' : '').'
                
                <button type="submit" name="changeSubmit">'.$lang['panel']['edit_page']['updatePageButton'].'</button>
            </form>
            </div>
        </div>
        '.$load_ckeditor.'
        ';
    }
    
    break;
  case 'edit':
      // EDIT A SPECIFIC PAGE.
      
    break;
  case 'settings':
      // site's name.
      // site's keywords.
      // site's description.
      // site's access password (also when activated change robots to noindex, unfollow, otherwise change to index, follow).
      // site's favicon.ico
      
      if (isset($_POST['changeSubmit'])) { 
          // $row[''] - Ur site's row from sites table.
          
          global $func;
          
          $nme = $func->protectxss($_POST['logoName']);
          $password = ''; //$func->protectxss($_POST['password']);
          $ficon = $func->protectxss($_POST['favicon']);
          $language = $func->protectxss($_POST['language']);
          $sidebarVisibility = $func->protectxss($_POST['sidebarVisibility']);
          
          $recaptcha_secret = $func->protectxss($_POST['RECAPTCHA_SECRET']);
          
          if (empty($nme)) {
              $errors = '<div class="error">'.$lang['panel']['site_settings']['errors']['site_name_empty'].'</div>.';
          }else {
                  
              if ($language === 'en' || $language === 'he') {
                  
                  $db->prepare('UPDATE `buildp_sites` SET logoImage=?, accessCode=?, favicon=?, language_code=?, sidebarVisibility=?, recaptcha_secret=? WHERE id=?')->execute(array($nme, $password, $ficon, $language, $sidebarVisibility, $recaptcha_secret, $row['id']));
                  
                  header('location: '.ABS_PATH.'dashboard.php?do=settings');
                  die;
                  
              }else {
                  $errors = '<div class="error">'.$lang['panel']['site_settings']['unfamiliar_languages'].'</div>';
              }
          }
          
      }
      
      echo '
      <style type="text/css">
      .error { 
        border: 1px solid #fc0505;
        text-align: center;
        padding: 10px;
        color: #fc0505;
      }
      
      form {
    position: relative;
    border-radius: 4px;
    background: #ffffff;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px 0 rgba(29,29,29,.1), -26px 0 42px -32px rgba(0,0,0,.13);
    padding: 10px;
    text-align:center;
    }
    
    input[type="text"] {
    overflow: visible;
    width: 255px;
    display: block;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    outline: none;
    text-align: center;
    margin:auto;
    }
    
    input[type="text"]:focus {
    border: solid 1px #337ab7;
    color: #337ab7;
    }
    
    .comsi-comsa {
    background-image: linear-gradient(#f4f4f5, #dcdcdc);
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #5f6875;
}
      </style>
       <div class="col-md-8">
      <h3>'.$lang['panel']['site_settings']['title'].'</h3>
      <form action="" method="POST">
          '.(isset($errors) ? $errors : '').'
           <div class="comsi-comsa">כללי</div>
          <label for="">'.$lang['panel']['site_settings']['site_name'].':&nbsp;<input type="text" name="logoName" value="'.$row['logoImage'].'"></label>
          <hr>
          <!-- <label for="">סיסמת גישה(אופציונאלי):&nbsp;<input type="text" name="password" value="'.(!is_null($row['accessCode']) ? $row['accessCode'] : '').'"></label><br>-->
          <label for="">'.$lang['panel']['site_settings']['favicon'].':&nbsp;<input type="text" name="favicon" value="'.$row['favicon'].'"></label>
          <hr>
          '.$lang['panel']['site_settings']['site_language'].': <label for=""><select name="language">
            '.($row['language_code'] == 'he' ? '<option value="he">'.$lang['panel']['main']['hebrew'].'</option>' : '<option value="en">'.$lang['panel']['main']['english'].'</option>').'
            '.($row['language_code'] == 'en' ? '<option value="he">'.$lang['panel']['main']['hebrew'].'</option>' : '<option value="en">'.$lang['panel']['main']['english'].'</option>').'
          </select></label>
          <hr>
          '.$lang['panel']['site_settings']['sidebar_visibility'].': <label for=""><select name="sidebarVisibility">
            '.($row['sidebarVisibility'] == 1 ? '<option value="1">'.$lang['panel']['site_settings']['sidebar_visible'].'</option>' : '<option value="0">'.$lang['panel']['site_settings']['sidebar_invisible'].'</option>').'
            '.($row['sidebarVisibility'] == 1 ? '<option value="0">'.$lang['panel']['site_settings']['sidebar_invisible'].'</option>' : '<option value="1">'.$lang['panel']['site_settings']['sidebar_visible'].'</option>').'
          </select></label>
          <hr>
          <div class="comsi-comsa">reCAPTCHA v2 Checkbox</div>
          <label for="">Secret key:<input type="text" name="RECAPTCHA_SECRET" value="'.$row['recaptcha_secret'].'"></label>
          <button type="submit" name="changeSubmit">'.$lang['panel']['site_settings']['updateSettingsButton'].'</button>
      </form>
      
      <!-- <a href="'.ABS_PATH.'dashboard.php?do=backup&status=inprogress"><button>Backup site</button></a> -->
      
      </div>
      ';

    break;
  case 'domain':
      if ($row['isPremium'] == 0) {
          header('location: '.ABS_PATH.'dashboard.php');
          die;
      }
      
      if (isset($_POST['connectBtn'])) {
          global $func;
          
          $domain = $func->protectxss($_POST['domain']);
          $domain = (isset($domain) ? $domain : '');
                      
          $dmn = $db->prepare('SELECT * FROM `buildp_sites` WHERE domain=?');
          $dmn->execute(array($domain));
          
          if (empty($domain)) {
              $db->prepare('UPDATE `buildp_sites` SET domain=? WHERE id=?')->execute(array('', $row['id']));
      
              header('location: '.ABS_PATH.'dashboard.php?do=domain');
              die;
          }else {
              if (count($dmn->fetchAll()) > 0 && $dmn->fetch()['id'] != $row['id']) {
                  //$domain = ''; 
                  $errors = '<div class="error">הדומיין מקושר לאתר אחר.</div>'; 
              }else {
                  $db->prepare('UPDATE `buildp_sites` SET domain=? WHERE id=?')->execute(array($domain, $row['id']));
      
                  header('location: '.ABS_PATH.'dashboard.php?do=domain');
                  die;
              }
          }
      }
      
      echo '
      <style type="text/css">
      .error { 
        border: 1px solid #fc0505;
        text-align: center;
        padding: 10px;
        color: #fc0505;
      }
      
      form {
    position: relative;
    border-radius: 4px;
    background: #ffffff;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px 0 rgba(29,29,29,.1), -26px 0 42px -32px rgba(0,0,0,.13);
    padding: 10px;
    text-align:center;
    }
    
    input[type="text"] {
    overflow: visible;
    width: 255px;
    display: block;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    outline: none;
    text-align: center;
    margin:auto;
    }
    
    input[type="text"]:focus {
    border: solid 1px #337ab7;
    color: #337ab7;
    }
      </style>
       <div class="col-md-8">
            <h3>חיבור דומיין</h3>
      
      <form action="" method="POST">
       '.(isset($errors) ? $errors : '').'
       <br>
על מנת לחבר את הדומיין שלך לאתרך, עליך לפנות אלינו בצור קשר/צאט Live בבקשה לחיבור הדומיין לאתרך בשביל שנאמת את שם המתחם שלך.
<br><br>
ה-DNS שלנו:
<br>1. NS1.DIGITALOCEAN.COM
<br> 2. NS2.DIGITALOCEAN.COM
<br>
         
    <br><label for="domain">שם מתחם (דומיין):&nbsp;<input type="text" name="domain" placeholder="example.com" value="'.(!empty($row['domain']) ? $row['domain'] : '').'"><span style="font-size:12px;">(ללא HTTP://WWW.)</span></label>
          <br><button type="submit" name="connectBtn">'.$lang['panel']['site_settings']['updateSettingsButton'].'</button>
      </form>
      
      <!-- <a href="'.ABS_PATH.'dashboard.php?do=backup&status=inprogress"><button>Backup site</button></a> -->
      
      </div>
      ';
      break;
      
  case 'backup':
      // Fetch all site's data, write to html files, and compress to ZIP file.
      // https://www.php.net/manual/en/class.ziparchive.php
	
echo '
Backup feature is not available yet.
<meta http-equiv="refresh" content="0; url='.ABS_PATH.'dashboard.php" />
';
die;
		
	  $status = (isset($_GET['status']) ? $_GET['status'] : null);
	  if (!is_null($status)) {
		  if ($status == 'inprogress') {
				global $func;

				$files = array();
				$zipname = $func->downloadBackup(dirname(__FILE__).'/includes/backups', $row['id'], $db);
			  
				// Send the site's owner a direct link to his site's backup.
			  	//header('location: '.ABS_PATH.'dashboard.php?do=backup&status=complete');
			  	//die;
		  }elseif ($status == 'complete') {
			  echo 'Backed up successfully !<br><meta http-equiv="refresh" content="0; url='.ABS_PATH.'dashboard.php" />';
		  }
	  }
      
      break;
    case 'style':
        // TODO: Show premium themes. 
      $themeID = (isset($_GET['id']) ? $_GET['id'] : '');
      if (empty($themeID)) {
      echo '
<style>

.info-theme {
    padding-bottom: 20px;
}

.name-theme {
    font-size: 18px;
    margin-bottom: 10px;
    margin-top: 5px;
}

.block-theme img {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    width: 100%;
}
</style>
      '; // TODO: If the site is premium then show themes fro premium.
      $q2 = $db->prepare("SELECT * FROM `buildp_themes`");
      $q2->execute();
      $rows = $q2->fetchAll();

      for($i=0;$i<sizeof($rows);$i++) {
        $style = $rows[$i];
        
        if ($style['isLocked'] == 1 && $b['group'] != 1)
            continue;
            
        if ($style['isPremium'] == 1 && $row['isPremium'] == 0) 
            continue;
        
        if ($style['id'] == $cssID) 
            $btn = '<button disabled>'.$lang['panel']['edit_theme']['theme_already_been_activated'].'</button>';
        else
            $btn = '<button>'.$lang['panel']['edit_theme']['activate_theme'].'</button>';
            
            // TODO: intend for deprecation.
            include_once(dirname(__FILE__).'/templates/'.$style['folderName'].'/metadata.php');
            global $metadata;
            
            $metadataJSON = json_decode( file_get_contents(dirname(__FILE__).'/templates/'.$style['folderName'].'/metadata.json') );
            
            $thumbnail_IMG = (isset($metadata['thumbnail_image']) ? $metadata['thumbnail_image'] : $metadataJSON->name);
            $theme_name = (isset($metadata['theme_name']) ? $metadata['theme_name'] : $metadataJSON->thumbnail_img);
            $theme_author = (isset($metadata['made_by']) ? $metadata['made_by'] : $metadataJSON->author);
            $preview_IMG = (isset($metadata['preview_image']) ? $metadata['preview_image'] : $metadataJSON->preview_img);
            
        echo '
        <div class="block-theme">
          <img src="'.$thumbnail_IMG.'"><br>
          <div class="name-theme">'.$theme_name.'    '.($style['isPremium'] == 1 && $row['isPremium'] == 1 ? '<div style="background-image: linear-gradient(to bottom right, #b57605, yellow); text-shadow: 0px 1px 3px #000; display: inline-block; min-width: 10px; padding: 3px 7px; font-size: 12px; font-weight: bold; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: middle; border-radius: 10px;">פרימיום</div>' : '').'</div>
          <div class="info-theme">'.$lang['panel']['edit_theme']['created_by'].' '.$theme_author.'</div>
          <a href="'.$preview_IMG.'" target="blank"><button>'.$lang['panel']['edit_theme']['watch_preview'].'</button></a> <a href="'.ABS_PATH.'dashboard.php?do=style&id='.$style['id'].'" onclick="confirmAction(event);">'.$btn.'</a>
        </div>
        ';
      }
      echo '
        <script type="text/javascript">
        function confirmAction(event) {
            
            if (!confirm("'.$lang['panel']['edit_theme']['switch_warning'].'")) {
                event.preventDefault();
            }
            
        }
        </script>
        ';
    }else {


      $q2 = $db->prepare("SELECT * FROM `buildp_themes` WHERE id=?");
      $q2->execute(array($themeID));
      $style = $q2->fetch();
      if ($q2->rowCount() > 0) {
          if($style['isPremium'] == 1 && $premium == 0) {
              header('location: '.ABS_PATH.'dashboard.php?do=style');
              die;
          }else {
              $db->prepare("UPDATE `buildp_sites` SET themeID=? WHERE id=?")->execute(array($style['id'], $usersiteid ));
          
              $q3 = $db->prepare("SELECT * FROM `buildp_pages` WHERE siteid=? order by time Asc ");
              $q3->execute(array($row['id'])); 
              $mainPage = $q3->fetch();
              
              include_once(dirname(__FILE__).'/templates/'.$style['folderName'].'/metadata.php');
              global $metadata;
              
              $db->prepare('UPDATE `buildp_pages` SET pagename=?, text=? WHERE id=?')->execute(array($metadata['data'][$row['language_code']]['main_page_caption'], $metadata['data'][$row['language_code']]['content'], $mainPage['id']));
              $db->prepare('UPDATE `buildp_sites` SET header=?, footer=?, sidebar=? WHERE id=?')->execute(array($metadata['data'][$row['language_code']]['header'], $metadata['data'][$row['language_code']]['footer'], $metadata['data'][$row['language_code']]['sidebar'], $row['id']));
        
              header('location: '.ABS_PATH.'dashboard.php?do=style');
              die;
          }
      }else {
        // Error.
      }
      // Apply theme to site.

    }
      break;
	case 'users':
		if ($group == 1) {
			$userID = (isset($_GET['id']) ? $_GET['id'] : null );
			
			if (is_null($userID)) {
				// List of all users.
				$q1 = $db->query('SELECT * FROM buildp_users');
				$q1->execute();
				$users = $q1->fetchAll();
				
				for ($i=0; $i<sizeof($users); $i++) {
					$user = $users[$i];
					if ($user['username'] == 'admin' && $user['id'] == 1) // Disallow admin user to edit himself.
					    continue;
						
					echo '
						<div>
							'.$user['username'].' 
							<a href="'.ABS_PATH.'dashboard.php?do=users&id='.$user['id'].'"><b><i class="fas fa-edit"></i> עריכה</b></a></a>
							<!-- <a href="'.ABS_PATH.'dashboard.php?do=users&id='.$user['id'].'&r=true">Remove</a> -->
							<!-- <a href="'.ABS_PATH.'dashboard.php?do=api&id='.$user['id'].'">New access to API</a> -->
						<div>
					';// TODO: When clicking on "remove" pop up a OKCancel box and confirm the user really wants to delete the specific user.
				}
				
			}else {
				// Edit specific user.
				$q1 = $db->prepare('SELECT * FROM buildp_users WHERE id=?');
				$q1->execute(array($userID));
				$user = $q1->fetch();
				
				
				$isRemoving = (isset($_GET['r']) ? $_GET['r'] : null);
				
				if (!is_null($isRemoving) && $isRemoving == 'true') {
				    // Temporarily disabled.
				    /*
				    $db->prepare('DELETE FROM buildp_sites WHERE userID=?')->execute(array($userID));
				    $db->prepare('DELETE FROM buildp_users WHERE id=?')->execute(array($userID));
				    header('location: '.ABS_PATH.'dashboard.php?do=users');
				    die;
				    */
				}else {
    				if (isset($_POST['changeSubmit'])) {
    					$username = $_POST['username'];
    					$mail = $_POST['email'];
    					
    					if (empty($username)) {
    						$errors = 'השם משתמש לא יכול להישאר ריק.';
    					}else {
    						if (empty($mail)) {
    							$errors = 'המייל לא יכול להישאר ריק.';
    						}else {
    							$db->prepare('UPDATE buildp_users SET username=?, mail=? WHERE id=?')->execute(array($username, $mail, $userID));
    							header('location: '.ABS_PATH.'dashboard.php?do=users');
    							die;
    						}
    					}
    					
    				}
    				if (isset($_POST['changePassSubmit'])) {
    					$pword = $_POST['password'];
    					$cpword = $_POST['cpassword'];
    					
    					if ($pword != $cpword) {
    						$errors = 'הסיסמאות שונות אחת מהשניה.';
    					}else {
    						$newPassword = hash('sha256', $user['salt'].md5($pword));
    						$db->prepare('UPDATE buildp_users SET password=? WHERE id=?')->execute(array($newPassword, $userID));
    						header('location: '.ABS_PATH.'dashboard.php?do=users');
    						die;
    					}
    				}
				}
				
				echo '
					'.(isset($errors) ? '<div class="errors">'.$errors.'</div>' : '').'
					<div>שינוי פרטים</div>
					<form action="" method="post">
						שם משתמש:<input type="text" name="username" value="'.$user['username'].'"><br>
						כתובת מייל:<input type="text" name="email" value="'.$user['mail'].'"><br>
						<button type="submit" name="changeSubmit">שנה פרטים</button>
					</form>
					<div>שינוי סיסמה</div>
					<form action="" method="post">
						רשום סיסמה חדשה:<input type="password" name="password"><br>
						רשום סיסמה חדשה שוב:<input type="password" name="cpassword"><br>
						<button type="submit" name="changePassSubmit">שנה פרטים</button>
					</form>
				
				';
			}
			
		}
		else { header('location: '.ABS_PATH.'dashboard.php'); die; }
		break;
	case 'mainsettings':
		if ($group == 1) {
			// Main site's settings.
			if (isset($_POST['changeSubmit'])) {
			    $lo = $_POST['logo'];
			    $cre = $_POST['credit'];
			    $mainurl = $_POST['url'];
			    $kw = $_POST['keywords'];
			    $desc = $_POST['description'];
			    
			    if (empty($lo)) {
			        $errors = 'שם האתר לא יכול להישאר ריק.';
			    }else {
			        if (empty($cre)) {
			            $errors = 'קרדיט האתר לא יכול להישאר ריק.';
			        }else {
			            if (empty($mainurl)) {
			                $errors = 'כתובת האתר לא יכולה להישאר ריקה.';
			            }else {
			                if (empty($kw)) {
			                    $errors = 'מילות המפתח לא יכולות להישאר ריקות.';
			                }else {
			                    if (empty($desc)) {
			                        $errors = 'תיאור האתר לא יכול להישאר ריק.';
			                    }else {
			                        
			                        $db->prepare('UPDATE buildp_setting SET logo=?, credit=?, url=?, keywords=?, description=?')->execute(array($lo, $cre, urlencode($mainurl), $kw, $desc));
			                        header('location: '.ABS_PATH.'dashboard.php?do=mainsettings');
			                        die;
			                        
			                    }
			                }
			            }
			        }
			    }
			    
			}
			
			echo '
				<style type="text/css">
				/* כאן הCSS */
				</style>
				
				'.(isset($errors) ? '<div class="error">'.$errors.'</div>>' : '').'
				<form action="" method="post">
				<label for="">שם האתר:&nbsp;<input type="text" name="logo" value="'.$s['logo'].'"></label><br>
				<label for="">קרדיט:&nbsp;<textarea name="credit">'.$s['credit'].'</textarea></label><br>
				<label for="">כתובת האתר:&nbsp;<input type="text" name="url" value="'.$s['url'].'"></label><br>
				<label for="">מילות מפתח:&nbsp;<textarea name="keywords" value="'.$s['keywords'].'"></textarea></label><br>
				<label for="">תיאור האתר:&nbsp;<textarea name="description" value="'.$s['description'].'"></textarea></label><br>
				<button type="submit" name="changeSubmit">שינוי פרטי האתר</button>
				</form>
			';
			
		}
		else { header('location: '.ABS_PATH.'dashboard.php'); die; }
		break;
	case 'ads':
		if ($group == 1) {
			// Manage advertisments.
			
			$adID = (isset($_GET['id']) ? $_GET['id'] : null);
			
			if (is_null($adID)) {
			    $isCreating = (isset($_GET['c']) ? $_GET['c'] : null);
			    
			    if (!is_null($isCreating) && $isCreating == true) {
			        // Create new ad.
			        
			        echo '
			        <style="text/css">
			        </style>
			        
			        HTML CODE COMES HERE.
			        ';
			        
			    }else {
			        echo '
			        <style="text/css">
			        </style>
			        
			        <a href="'.ABS_PATH.'dashboard.php?do=ads&c=true">צור פרסומת חדשה.</a>
			        ';
			        
			        $ads = $db->prepare('SELECT * FROM buildp_advertisements');
			        $ads->execute();
			        $ads = $ads->fetchAll();
			        
			        for ($i=0; $i<sizeof($ads); $i++) {
			            $ad = $ads[$i];
			            
			            echo '
			            '.$ad['name'].'&nbsp;
			            <a href="'.ABS_PATH.'dashboard.php?do=ads&id='.$ad['id'].'">Edit</a>&nbsp;
			            <a href="'.ABS_PATH.'dashboard.php?do=ads&id='.$ad['id'].'&r=true">Remove</a>
			            ';
			            
			        }
    			    
    			    // Show all ads in links.
			    }
			 
			}else {
			    // Show specific ad in form.
			    
			    $isRemoving = (isset($_GET['r']) ? $_GET['r'] : null);
				
				if (!is_null($isRemoving) && $isRemoving == 'true') {
				    $db->prepare('DELETE FROM buildp_sites WHERE userID=?')->execute(array($userID));
				    $db->prepare('DELETE FROM buildp_users WHERE id=?')->execute(array($userID));
				    
				}else {
				    
				    if (isset($_POST['changeSubmit'])){
				        
				    }
				    
				}
			    
			    echo '
				<style type="text/css">
				/* כאן הCSS */
				</style>
				
				<form action="" method="POST">
				<label for=""><input type="text" value=""></label>
				<button type="submit" name="changeSubmit">שינוי פרסומת</button>
				</form>
			    ';
			}
			
		}
		else { header('location: '.ABS_PATH.'dashboard.php'); die; }
		break;
	case 'themes':
		if ($group == 1) { // TODO: Check out why it is not possible to upload new themes.
			// Manage themes.
			
			$themeID = (isset($_GET['id']) ? $_GET['id'] : null);
			
			if (is_null($themeID)) {
			    global $func;
			    
			    // Add new theme.
			    //
			    $addTheme = (isset($_GET['a']) ? $_GET['a'] : null);
    			    if (!is_null($addTheme) && $addTheme == true) {
    			        
    			        if (isset($_POST['createTheme'])) {
    			            // https://www.php.net/manual/en/class.ziparchive.php
    			            //
    			            // https://stackoverflow.com/a/8889126
    			            //
    			            
    			            $isLocked = ($_POST['lock'] == TRUE ? TRUE : (!isset($_POST['lock']) ? null : FALSE));
    			            $isPremium = ($_POST['premium'] == TRUE ? TRUE : (!isset($_POST['premium']) ? null : FALSE));
    			            
    			            $file = $_FILES['themeFile']; // Upload ZIP file.
    			            
    			            $theme = $db->prepare('SELECT * FROM `buildp_themes` FROM folderName=?');
    			            $theme->execute(array( explode('.', $file['name'])[0] ));
    			            if ($theme->rowCount() > 0) {
    			                header('location: '.ABS_PATH.'dashboard.php?do=themes');
    			                die;
    			            }
    			            
    			            if (is_null($isLocked)) {
    			                header('location: '.ABS_PATH.'dashboard.php?do=themes');
    			                die;
    			            }
    			            
    			            if (is_null($isPremium)) {
    			                header('location: '.ABS_PATH.'dashboard.php?do=themes');
    			                die;
    			            }
    			            
    			            if (!isset($file)) {
    			                header('location: '.ABS_PATH.'dashboard.php?do=themes');
    			                die;
    			            }
    			            
    			            if (explode('/', $file['type'])[1] != 'zip') { // Error
    			                header('location: '.ABS_PATH.'dashboard.php?do=themes');
    			                die;
    			            }
    			            
    			            $folderName = explode('.', $file['name'])[0];
    			            $dest = dirname(__FILE__).'/templates/'.$folderName;
    			            
    			            if (file_exists($dest.'.'.explode('/', $file['type'])[1])) {
    			                header('location: '.ABS_PATH.'dashboard.php?do=themes');
    			                die;
    			            }
    			            
    			            mkdir($dest); // Make theme directory.
    			            
    			            if (move_uploaded_file($file['tmp_name'], $dest.'.'.explode('/', $file['type'])[1])) {
    			                
    			                $zip = new ZipArchive;
    			                
                                $res = $zip->open($dest.'.'.explode('/', $file['type'])[1]);
                                if ($res === TRUE) {
                                  $zip->extractTo($dest);
                                  $zip->close();
                                }
    			                
    			                //rmdir($dest);
    			                unlink($dest.'.'.explode('/', $file['type'])[1]);

    			                $themeID = intval($db->getRowsCount('buildp_themes'))+1;
    			                
    			                $db->prepare('INSERT INTO `buildp_themes`(`id`,`folderName`,`isLocked`,`isPremium`) VALUES(?,?,?,?)')->execute(array($themeID, $folderName, $isLocked, $isPremium));
    			                
    			                header('location: '.ABS_PATH.'dashboard.php?do=themes');
    			                die;
    			            }
    			            
    			            // Coudlnt upload the theme.
    			            header('location: '.ABS_PATH.'dashboard.php?do=themes');
    			            die;
    			        }
    			        
    			        echo '
    			            <!-- Add new theme form. -->
    			            
    			            <form enctype="multipart/form-data" action="" method="POST">
    			            
        			            <label for=""><input type="file" name="themeFile" accept=".zip"></label><br>
        			            
        			            <label for=""><select name="lock">
        			                <option value="false">Unlocked</option>
        			                <option value="true">Locked</option>
        			            </select></label><br>
        			            
        			            <label for=""><select name="premium">
        			                <option value="false">Free</option>
        			                <option value="true">Premium</option>
        			            </select></label><br>
        			            
        			            <button type="submit" name="createTheme">
        			                צור תבנית
        			            </button>
        			            
    			            </form>
    			        ';
    			    }else {
    			        
    			        // Manage all themes.
        			    //
        			    
        			    $theme = $db->prepare('SELECT * FROM `buildp_themes`');
    			        $theme->execute();
    			        $themes = $theme->fetchAll();
    			        
    			            
        			    echo '
        			        <a href="dashboard.php?do=themes&a=true">
        			            הוספת תבנית
        			        </a><br>';
        			    for ($i=0; $i<count($themes); $i++) {
        			        $t = $themes[$i];
        			        
        			        echo '
        			        =>&nbsp;'.$t['folderName'].'<br>
        			        ';
        			    }
    			        
    			    }
			}else {
			    // Remove selected theme.
			    //
			    $removeTheme = (isset($_GET['del']) ? $_GET['del'] : null);
			    if (!is_null($removeTheme) && $removeTheme == true) {
			        // Remove the theme.
			        //
			        
			        header('location: '.ABS_PATH.'dashboard.php?do=themes');
			        die;
			    }
			    
			    // Edit selected theme.   
			    //
			    if (isset($_POST['editTheme'])) {}
			    
			    echo '
			        <!-- Theme edit form. -->
			    ';
			}
		}
		else { header('location: '.ABS_PATH.'dashboard.php'); die; }
		break;
    case 'sitepages':
        if ($group == 1) {
            
            $do = (isset($_GET['action']) ? $_GET['action'] : null);
            
            if (is_null($do) || !isset($do) || empty($do)) {
                echo '
                <style>
.box2 {
    width: 60%;
    margin: auto;
    margin-bottom: 20px;
    text-align: center;
    text-decoration: none;
    font-size: 12pt;
    padding: 8px;
    background: #3989cf;
    border: 1px solid #2970b0;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
    transition: 0.5s ease;
    border-radius: 5px;
}
.box2:hover {
    background: #2970b0;
    border: 1px solid #1b568c;
}
.box2 a {
    color: #ffffff;
}
</style>
                <div class="box2"><a href="'.ABS_PATH.'dashboard.php?do=sitepages&action=new"><i class="fas fa-plus"></i> דף חדש</a></div>
                
                ';
            }
            
            switch ($do) {
                case 'new':
                    if (isset($_POST['createPage'])) {
                
                       $title = (isset($_POST['title']) ? $_POST['title'] : null);
                       $content = (isset($_POST['content']) ? $_POST['content'] : null);
                       $restrictedPage = (isset($_POST['restricted']) ? TRUE : FALSE);
                       $url = (isset($_POST['url']) ? $_POST['url'] : null);
                       
                       if (is_null($title) || empty($title)) {
                           $error = 'Title cannot be empty.';
                       }
                       if (is_null($content) || empty($content)) {
                           $error = 'Content cannot be empty.';
                       }
                       if (is_null($url)) {
                           $error = 'Page URL cannot be empty.';
                       }
                       
                       if (count(explode(' ', $url)) > 0 ) {
                           str_replace(' ', '', $url);
                           strtoupper($url);
                       }
                       
                       
                       if (empty($error)) {
                           $id = ($db->getRowsCount('buildp_mainPages')+1);
                           $time = time();
                           
                           $stmt = $db->prepare('INSERT INTO `buildp_mainPages` (id,time,content,url,isUser,title) VALUES (?,?,?,?,?,?)');
                           $stmt->execute(array($id, $time, $content, $url, $restrictedPage, $title));
                           // Reload the page.
                            header('location: '.ABS_PATH.'dashboard.php?do=sitepages'); 
                            die;
                       }
                    }
                    
                    echo (!empty($error) ? '<span style="font-size:22x;color:red;">'.$error.'</span>' : '').'
                        <form action="" method="POST">
                        <label for="">Title:<input type="text" name="title"></label><br>
                        <label for="">Content:<textarea name="content" id="editor1"></textarea></label><br>
                        <label for="">Restricted: <input type="checkbox" name="restricted"></label><br>
                        <label for="">URL:<input type="text" name="url"></label><br>
                        <label for=""><button name="createPage">Create</button></label>
                        </form>
                        
                        '.$load_ckeditor;
                    
                    break;
                    
                    case 'edit':
                        
                        $pageID = (isset($_GET['id']) ? $_GET['id'] : null);
                        
                        if (!is_null($pageID)) {
                            
                            $page = $db->prepare('SELECT * FROM `buildp_mainPages` WHERE id=?');
                            $page->execute(array($pageID));
                            $page = $page->fetch();
                            
                            $error = '';
                            
                            if (isset($_POST['editPage'])) {
                                // Edit the page.
                                
                                $title = (isset($_POST['title']) ? $_POST['title'] : null);
                               $content = (isset($_POST['content']) ? $_POST['content'] : null);
                               $restrictedPage = (isset($_POST['restricted']) ? TRUE : FALSE);
                               $url = (isset($_POST['url']) ? $_POST['url'] : null);
                               
                               if (is_null($title)) {
                                   $error = 'Title cannot be empty.';
                               }
                               if (is_null($content)) {
                                   $error = 'Content cannot be empty.';
                               }
                               if (is_null($url)) {
                                   $error = 'Page URL cannot be empty.';
                               }
                               
                               if (count(explode(' ', $url)) > 0 ) {
                                   str_replace('-', '', $url);
                                   strtoupper($url);
                               }
                               
                               
                               if (empty($error)) {
                                   
                                   $stmt = $db->prepare('UPDATE `buildp_mainPages` SET title=?, content=?, isUser=?, url=? WHERE id=?');
                                   $stmt->execute(array($title, $content, $restrictedPage, $url, $pageID));
                                   
                                   // Reload the page.
                                    header('location: '.ABS_PATH.'dashboard.php?do=sitepages'); 
                                    die;
                               }
                                
                            }
                            
                            echo (!empty($error) ? '<span style="font-size:22x;color:red;">'.$error.'</span>' : '').'
                            <form action="" method="POST">
                            <label for="">Title:<input type="text" name="title" value="'.$page['title'].'"></label><br>
                            <label for="">Content:<textarea name="content" id="editor1">'.$page['content'].'</textarea></label><br>
                            <label for="">Restricted: <input type="checkbox" name="restricted" '.($page['isUser'] == 1 ? 'checked' : '').'></label><br>
                            <label for="">URL:<input type="text" name="url" value="'.$page['url'].'"></label><br>
                            <label for=""><button name="editPage">Update</button></label>
                            </form>
                            
                            '.$load_ckeditor;
                        }
                        break;
                        
                    case 'del':
                        // Uavailable feature.
                        headee('location: '. ABS_PATH.'dashboard.php');
                        die;
                        
                        // Delete pages from the main site.
                        $pageID = (isset($_GET['id']) ? $_GET['id'] : null);
                        
                        $page = $db->prepare('SELECT * FROM `buildp_mainPages` WHERE id=?');
                        $page->execute(array($pageID));
                        $page = $page->fetchAll();
                        
                        
                        if (count($page) > 0) {
                            $db->prepare('DELETE FROM `buildp_mainPages` WHERE id=?')->execute(array($page[0]['id']));
                        }
                        
                        // Reload the page.
                        header('location: '.ABS_PATH.'dashboard.php?do=sitepages'); 
                        die;
                        
                        break;
                    default:
                        // Show all pages, allow to edit and delete.
                        
                        $pages = $db->prepare('SELECT * FROM `buildp_mainPages` ORDER BY id ASC'); 
                        $pages->execute();
                        $pages = $pages->fetchAll();
                        
                        for ($i=0; $i<count($pages); $i++) {
                            $page = $pages[$i];
                            
                            echo '
                            <style>
                            .box {
                                position: relative;
                                border-radius: 4px;
                                background: #ffffff;
                                padding: 10px;
                                border-bottom: solid 1px #ccc;
                                width: 60%;
                                margin: auto;
                                font-size: 12pt;
                            }
                            </style>
                            <div class="box">
                                #'.$page['id'].'&nbsp;<a href="'.ABS_PATH.'?do='.$page['url'].'" target="_blank">'.$page['title'].'</a>&nbsp;<a href="'.ABS_PATH.'dashboard.php?do=sitepages&action=edit&id='.$page['id'].'"><strong><i class="far fa-edit"></i> עריכה</strong></a>&nbsp;<a href="'.ABS_PATH.'dashboard.php?do=sitepages&action=del&id='.$page['id'].'" onclick="confirmAction(event)"><strong><i class="fa fa-trash"></i> מחיקה</strong></a>
                            </div>';
                            
                        }
                        
                        echo '
                        <script type="text/javascript">
                        function confirmAction(event) {
                            if (!confirm("'.$lang['panel']['all_pages']['delete_warning'].'")) {
                                event.preventDefault();
                            }
                        }
                        </script>';
                        
                        break;
                
                
            }
            
            
        }
        else { header('location: '.ABS_PATH.'dashboard.php'); die; }
        break;
	case 'sites':
		if ($group == 1) {
			$siteID = (isset($_GET['id']) ? $_GET['id'] : null );
			
			if (is_null($siteID)) {
			    // All sites.
			    $sites = $db->prepare('SELECT * FROM `buildp_sites`');
			    $sites->execute();
			    $sites = $sites->fetchAll();
			    
			    for ($i=0; $i<count($sites); $i++) {
			        $site = $sites[$i];
			        
			        echo '
			        <style>
			        .box {
                        position: relative;
                        border-radius: 4px;
                        background: #ffffff;
                        padding: 10px;
                        border-bottom: solid 1px #ccc;
                        width: 60%;
                        margin: auto;
                        font-size: 12pt;
                    }
			        </style>
			        <div class="box">
			            <a href="'.ABS_PATH.$site['siteurl'].'" target="_blank">'.$site['logoImage'].'</a>
			            <b><a href="'.ABS_PATH.'dashboard.php?do=sites&id='.$site['id'].'"><i class="fas fa-edit"></i> עריכה</a></b>
			            <!-- <a href="'.ABS_PATH.'dashboard.php?do=sites&id='.$site['id'].'&sus=true">השהיה</a> -->
			            <br>
			            </div>
			        ';
			        
			    }
			    
			}else {
				// Specific site.
				
				$site = $db->prepare('SELECT * FROM `buildp_sites` WHERE id=?');
			    $site->execute(array($siteID));
		        $site = $site->fetch();
			        
				if (isset($_POST['editSite'])) {
				    global $func;
				    $days = $func->protectxss($_POST['days']);
				    
				    /*if (!preg_match('/^([0-9])$/', $days)) {
				        header('location: '.ABS_PATH.'dashboard.php?do=sites');
				        die;
				    }*/
				    
				    $days = intval($days);
				    
				    if ($days == 0) {
				        $days = 0;
				        $isPremium = 0;
				    }else {
				        $days = time()+3600*24*$days;
				        $isPremium = 1;
				    }
				    
				    
				    $db->prepare('UPDATE `buildp_sites` SET premiumExpiry=?, isPremium=? WHERE id=?')->execute(array($days, $isPremium, $site['id']));
				    header('location: '.ABS_PATH.'dashboard.php?do=sites');
				    die;
				}
				
				$diff = floor(($site['premiumExpiry']-time()) / (3600*24));
				echo '
				<form action="" method="POST"> 
				    <label for="">ימי פרימיום:&nbsp;<input type="text" name="days" value="'.($diff > 0 ? $diff : '').'"></label><br>
				    <button type="submit" name="editSite">עדכון</button>
				</form>
				';
			}
			
		}
		else { header('location: '.ABS_PATH.'dashboard.php'); die; }
		break;
	case 'header':
	    
	    if (isset($_POST['updateSubmit'])) {
	        $text = $_POST['text'];
	        if (empty($text)) {
	            $errors = '';
	        }else {
	            $db->prepare('UPDATE buildp_sites SET header=? WHERE id=?')->execute(array($text, $row['id']));
	            header('location: '.ABS_PATH.'dashboard.php?do=header');
	            die;
	        }
	        
	    }
	    
	    echo '
        <style type="text/css">
        
        </style>
        
        '.(isset($errors) ? '<div class="error">'.$errors.'</div>' : '').'
        <div class="col-md-8">
        <h3>'.$lang['panel']['edit_top_section']['title'].'</h3>
            <div class="box">
            <form action="" method="POST">
                <label for="pageText">'.$lang['panel']['edit_top_section']['page_content'].':&nbsp;<br><textarea id="editor1" name="text" rows="10" cols="80">'.$row['header'].'</textarea></label><br>
                <button type="submit" name="updateSubmit">'.$lang['panel']['edit_top_section']['updateButton'].'</button>
            </form>
            </div>
        </div>
        '.$load_ckeditor.'
        ';
        
	    break;
    case 'footer':
        
	    if (isset($_POST['updateSubmit'])) {
	        $text = $_POST['text'];
	        if (empty($text)) {
	            $errors = '';
	        }else {
	            $db->prepare('UPDATE buildp_sites SET footer=? WHERE id=?')->execute(array($text, $row['id']));
	            header('location: '.ABS_PATH.'dashboard.php?do=footer');
	            die;
	        }
	        
	    }
	    
	    echo '
        <style type="text/css">
        
        </style>
        
        '.(isset($errors) ? '<div class="error">'.$errors.'</div>' : '').'
        <div class="col-md-8">
        <h3>'.$lang['panel']['edit_bottom_section']['title'].'</h3>
            <div class="box">
            <form action="" method="POST">
                <label for="pageText">'.$lang['panel']['edit_bottom_section']['page_content'].':&nbsp;<br><textarea id="editor1" name="text" rows="10" cols="80">'.$row['footer'].'</textarea></label><br>
                <button type="submit" name="updateSubmit">'.$lang['panel']['edit_bottom_section']['updateButton'].'</button>
            </form>
            </div>
        </div>
        '.$load_ckeditor.'
        ';
        
	    break;
	 case 'sidebar':
	     if (isset($_POST['updateSubmit'])) {
	        $text = $_POST['text'];
	        if (empty($text)) {
	            $errors = '';
	        }else {
	            $db->prepare('UPDATE buildp_sites SET sidebar=? WHERE id=?')->execute(array($text, $row['id']));
	            header('location: '.ABS_PATH.'dashboard.php?do=sidebar');
	            die;
	        }
	        
	    }
	    
	    echo '
        <style type="text/css">
        
        </style>
        
        '.(isset($errors) ? '<div class="error">'.$errors.'</div>' : '').'
        <div class="col-md-8">
        <h3>'.$lang['panel']['edit_side_bar']['title'].'</h3>
            <div class="box">
            <form action="" method="POST">
                <label for="pageText">'.$lang['panel']['edit_side_bar']['page_content'].':&nbsp;<br><textarea id="editor1" name="text" rows="10" cols="80">'.$row['sidebar'].'</textarea></label><br>
                <button type="submit" name="updateSubmit">'.$lang['panel']['edit_side_bar']['updateButton'].'</button>
            </form>
            </div>
        </div>
        '.$load_ckeditor.'
        ';
	     break;
	case 'security':
	    // Security, privacy. 
	    /*
	    global $func;
	    if (isset($_POST['changePassword'])) {
	        $oPass = $func->protectxss($_POST['oldPassword']);
	        $nPass = $func->protectxss($_POST['newPassword']);
	        $cNPass = $func->protectxss($_POST['confirmNewPassword']);
	        
	        $hash = hash('sha256', $b['salt'].md5($oPass));
	        $q1 = $db->prepare('SELECT * FROM `buildp_users` WHERE password=?');
	        $q1->execute(array($hash));
	        
	        if ($q1->rowCount() < 1) {
	            $errors = $lang['panel']['security']['errors']['oldPasswordDoesntMatch'];
	        }else { 
	            if ($nPass != $cNPass) {
	                $errors = $lang['panel']['security']['errors']['PasswordsDontMatch'];
	            }else {
	                
	                $nHash = hash('sha256', $b['salt'].md5($nPass));
	                
	                $db->prepare('UPDATE `buildp_users` SET password=? WHERE id=?')->execute(array($nHash, $b['id']));
	                header('location: '.ABS_PATH.'dashboard.php?do=security');
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
	    </style>
	    <div class="col-md-8">
        <h3>Security & Privacy</h3>
            <div class="box">
	    
    	    <form action="" method="POST">
        	    '.(isset($errors) ? '<div class="">'.$errors.'</div>' : '').'
        	    <label for="">'.$lang['panel']['security']['oldPassword'].':<input type="text" name="oldPassword"></label><br>
        	    <label for="">'.$lang['panel']['security']['newPassword'].':<input type="text" name="newPassword"></label><br>
        	    <label for="">'.$lang['panel']['security']['confirmNewPassword'].':<input type="text" name="confirmNewPassword"></label><br>
        	    <button type="submit" name="changePassword">'.$lang['panel']['security']['changePasswordButton'].'</button>
    	    </form>
	    </div>
	    </div>
	    
	    ';
	    break;
	    */
    case 'api':
        
        if ($group == 1) { // Admin.
            $userID = (isset($_GET['id']) ? $_GET['id'] : null);
            
            if (!is_null($userID)) {
                // Create token and secret for the user.
                
                $req = $db->prepare('SELECT * FROM `api_requests` WHERE userID=?');
                $req->execute(array($userID));
                $req = $req->fetch();
                
                if (!is_null($req)) {
                    
                    $time = time();
                    $token = hash('sha256', $time.$userID.md5('TOKEN'));
                    $secret = hash('sha256', $time.$userID.md5('SECRET'));
                    srand($time);
                    $db->prepare('INSERT INTO `api_access` (id,token,secret,time,lastlogin,userID) VALUES (?,?,?,?,?,?)')->execute(array(rand(1000,9999), $token, $secret, $time, 0, $userID));
                    $db->prepare('DELETE FROM `api_requests` WHERE userID=?')->execute(array($userID));
                    header('location: '.ABS_PATH.'dashboard.php?do=api');
                    die;
                }
                
            }else {
                // Fetch all the users request for api access.
                
                $reqs = $db->prepare('SELECT * FROM `api_requests`');
                $reqs->execute();
                $reqs = $reqs->fetchAll();
                
                if (count($reqs) > 0) {
                    for ($i=0; $i<count($reqs); $i++) {
                        $req = $reqs[$i];
                        
                        echo '
                        The user '.$user->getUser($user->getUsernameByID($req['userID']))['username'].' requested access to the API. (<a href="'.ABS_PATH.'dashboard.php?do=api&id='.$req['userID'].'">Grant</a>)<br>
                        ';
                        
                    }
                }else {
                    echo 'אין בקשות גישה ל-API.';
                }
            }
            
            
            
        }else if ($group == 2) { // Site owner.
            // Show the user's token and secret, otherwise , if no exists suggest to request access.
            
            $req = $db->prepare('SELECT * FROM `api_access` WHERE userID=?');
            $req->execute(array($userloggined));
            $req = $req->fetch();
            
            if ($req == false) {
                // ASK FOR ACCESS TO THE API.
                
                $req = $db->prepare('SELECT * FROM `api_requests` WHERE userID=?');
                $req->execute(array($userloggined));
                $req = $req->fetch();
                
                if ($req != false) {
                    echo '
                    <div class="col-md-8">
                    <div class="box">
                   <center> <img src="https://i.websmaking.com/images/vector/for-api.svg" width="160px"><br>
                    הבקשה שלך ממתינה לאישור, התהליך לוקח בדרך כלל יום עד שלושה ימים.</center>
                    </div>
                    </div>
                    ';
                }else {
                    if (isset($_POST['reqAccess'])) {
                        srand(time());
                        $db->prepare('INSERT INTO `api_requests` (id,userID,time) VALUES (?,?,?)')->execute(array(rand(1000,9999), $userloggined, time()));
                        header('location: '.ABS_PATH.'dashboard.php?do=api');
                        die;
                    }
                    
                    echo '
                    <div class="col-md-8">
                    <div class="box">
                    <center>
                    <img src="https://i.websmaking.com/images/vector/for-api.svg" width="160px">
                    <br>
                    <b>להוציא מהאתר שלך את המיטב בעזרת ממשק תכנות היישומים שלנו (API) 👨‍💻</b><br>
                    בשביל לקבל גישה ל-API עליכם להגיש בקשה, התהליך לאישור הבקשה בדרך כלל לוקח בין יום לשלושה ימים.
                    <p>בשליחת הבקשה אתה מאשר כי קראת, הבנת והסכמת את <a href="index.php?do=terms" target="_blank">תנאי השימוש</a> ו<a href="https://www.websmaking.com/?do=privacy" target="_blank">מדיניות פרטיות</a></p>
                    <form method="POST" action="">
                        <button type="submit" name="reqAccess">שלח בקשה</button>
                    </form>
                    </center>
                    </div>
                    </div>
                    ';
                }
                
            }else {
                
                echo '
                <style>
.col-md-8 {
   width: fit-content;
}
.box {
   padding: 50px;
}
.token {
    background-image: linear-gradient(to right, white , #64b8ff);
    color: #fff;
    padding: 5px;
    width: 100%;
    z-index: 99999;
    margin-bottom: 10px;
    margin-right: -50px;
    margin-top: -18px;
}

.secret {
    background-image: linear-gradient(to right, white , #ffab64);
    color: #fff;
    padding: 5px;
    width: 100%;
    z-index: 99999;
    margin-bottom: 10px;
    margin-right: -50px;
    margin-top: 8px;
}
.api {
    margin-bottom: 40px;
    margin-top: -35px;
    text-align: center;
    font-size: 13pt;
    border-bottom: solid 2px #e2e2e2;
    color: #373737;
}
                </style>
                <div class="col-md-8"><div class="box">
                <div class="api">API Setup</div>
                <div class="token">Token</div>
                <label for="">Your Token: <input type="text" value="'.$req['token'].'" readonly></label><br>
                <div class="secret">Secret</div>
                <label for="">Your Secret: <input type="text" value="'.$req['secret'].'" readonly></label>
                </div></div>
                ';
                
            }
            
        }
        
        break;
    default:
		if ($group == 1):
			echo '
			<style>
.alert1 li a {
    text-decoration: none;
    color: #ffffff;
    font-size: 12pt;
    padding: 8px;
    background: #3989cf;
    border: 1px solid #2970b0;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
    transition: 0.5s ease;
    border-radius: 5px;
}

.alert1 li a:hover {
    background: #2970b0;
    border: 1px solid #1b568c;
}
			</style>
			<div style=" margin-top: 30px; font-size: 20pt; "><i class="fa fa-home"></i> לוח בקרה של ההנהלה</div></br>
			<div class="alert1">
		<li><a href="'.ABS_PATH.'dashboard.php?do=users"><i class="fas fa-users"></i> משתמשים</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=ads"><i class="fas fa-ad"></i> פרסומות</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=themes"><i class="fas fa-paint-brush"></i> תבניות</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=sitepages"><i class="fas fa-copy"></i> דפים</a></li> <!-- doesnt showen up for some reason, check this out !!! -->
		<li><a href="'.ABS_PATH.'dashboard.php?do=sites"><i class="fas fa-sitemap"></i> כל האתרים</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=updates"><i class="fas fa-support"></i> עדכונים</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=api"><i class="fa fa-server"></i> ממשק תכנות יישומים</a></li>
		<li><a href="'.ABS_PATH.'dashboard.php?do=mainsettings"><i class="fas fa-cog"></i> הגדרות</a></li>
			</div>
			';
		elseif($group == 2):
		    /*$lang = (isset($_SESSION['language']) ? $_SESSION['language'] : null);
		    switch($lang) { // Determine site's language.
		        case 'he':
		            $lang = $lang['panel']['main']['hebrew'];
		            break;
		        case 'en':
		            $lang = $lang['panel']['main']['english'];
		            break;
		        default:
		            $lang = $lang['panel']['main']['english'];
		            break;
		    }*/
		    
		    global $func;
		    //$ssl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443);
		    $ssl = false; //$ssl = $func->check_ssl( (($row['isPremium'] == 1 && !empty($row['domain'])) ? $row['domain'] : ABS_PATH.$url) );
		    
		    $pageCount = $db->prepare('SELECT * FROM buildp_pages WHERE siteid=?');
		    $pageCount->execute(array($row['id']));
		    
		    
		    $premiumDiff = floor( ($row['premiumExpiry']-time()) / (3600*24) ); // $lang['panel']['main']['premium_enabled']
		    $dmn = (($row['isPremium'] == 1 && !empty($row['domain'])) ? 'http://'.$row['domain'] : ABS_PATH.$url);
		    // ABS_PATH.$url
			echo '<style>
			.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: right;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc;
}

.accordion:after {
  content: "\002B" ;
  color: #777;
  font-weight: bold;
  float: left;
  margin-left: 5px;
}

.active:after {
  content: "\2212";
}

.panel {
    padding: 0 18px;
    background-color: #ffffff;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
    margin-bottom: 10px;
    border: solid 1px #cccccc;
}
.panel img {
    width: 20px;
}
			@media screen and (max-width: 992px) {
			    .col-md-6 {
			        width: 100%
			    }
			    .col-md-7 {
			        width: 100%
			    }
			}
			</style>
			    <!--
			    <form action="'.ABS_PATH.'dashboard.php?do=settings" method="GET">
			        <select name="si" onchange="this.form.submit();">
			            <option value="[SITE_ID]">[SITE_NAME]</option>
			            <option value="[SITE_ID]">[SITE_NAME]</option>
			        </select>
			    </form>
			    -->
			    <div class="col-md-8">
			    '.($row['isPremium'] == 0 ? '<a href="https://www.websmaking.com/?do=premium"><img src="https://i.imgur.com/D6hTjbH.png" style=" margin-top: 20px; margin-bottom: 20px; max-width: 100%; "></a>' : '').'
				   <div style="font-size: 24px;padding-bottom: 15px;">'.$lang['panel']['main']['dashboard'].'</div>
                	<div class="col-md-6">
                		<div class="box">
                			<h3>'.$lang['panel']['main']['site_info'].'</h3>
                			<div class="box-body no-padding">
                			      <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <td>'.$lang['panel']['main']['site_name'].'</td>
                                        <td>'.$row['logoImage'].'</td>
                                    </tr>
                                    <tr>
                                        <td>'.$lang['panel']['main']['site_address'].'</td>
                                        <td><a href="'.$dmn.'" target="_blank">/'.$url.'</a></td>
                                    </tr>
                                    <tr>
                                        <td>'.$lang['panel']['main']['total_pages'].'</td>
                                        <td><span class="badge bg-yellow">'.$pageCount->rowCount().'</span></td>
                                    </tr>
                                    <tr>
                                        <td>'.$lang['panel']['main']['language'].'</td>
                                        <td><span>'.$lang['language_name'].'</span></td> <!-- site\'s language -->
                                    </tr>
                
                                    </tbody>
                                </table>
                			</div>
                		</div>
                	</div>

                	<div class="col-md-7">
                		<div class="box">
                						<h3>'.$lang['panel']['main']['system_info'].'</h3>
                			<div class="box-body no-padding">
                			      <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <td>'.$lang['panel']['main']['system_version'].'</td>
                                        <td>'.SYSTEM_VERSION.'</td>
                                    </tr>
                                    <tr>
                                        <td>'.$lang['panel']['main']['disk_space'].'</td>
                                        <td><span class="badge bg-yellow">'.$func->calculateSize($db->getContentTotalContentSize($row['id'])).' / '.$lang['panel']['main']['disk_space_unlimited'].'</span></td>
                                    </tr>
                                    <tr>
                                        <td>פרוטוקול מאובטח</td>
                                        <td>'.($ssl ? '<span class="on">'.$lang['panel']['main']['ssl_enabled'].'</span>' : '<span class="on">'.$lang['panel']['main']['ssl_enabled'].'</span>').'</td>
                                    </tr>
                                    <tr>
                                        <td>'.$lang['panel']['main']['premium'].'</td>
                                        <td>'.($row['isPremium'] == 1 ? '<span class="on">'.$premiumDiff.' ימים ('.date('d/m/Y', $row['premiumExpiry']).')</span>' : '<span class="off">'.$lang['panel']['main']['premium_disabled'].'</span>').'</td>
                                    </tr>
                
                                    </tbody>
                                </table>
                			</div>
                		</div>
                	</div>

<button class="accordion active">העדפות</button>
<div class="panel" style="max-height: 50px;">
<p>
<i class="fa fa-th" style="color: #20759a;"></i> <a href="/dashboard.php">לוח בקרה</a> | 
<i class="fa fa-support" style="color: #df0067;"></i> <a href="/?do=contact">תמיכה</a>
'.($row['isPremium'] == 1 ? ' | <a href="/dashboard.php?do=domain"><i class="fas fa-globe"></i> חיבור דומיין</a>' : '').'

</p>
</div>

<button class="accordion active">ניהול דפים</button>
<div class="panel" style="max-height: fit-content;">
<p>
<i class="fas fa-copy" style="color: #336792;"></i> <a href="/dashboard.php?do=pages">כל הדפים</a> | 
<i class="fas fa-file" style="color: #84b556;"></i> <a href="/dashboard.php?do=newpage">דף חדש</a> |
<i class="fa fa-address-card" style="color: #33965f;"></i> <a href="/dashboard.php?do=newthemepage&type=contact">דף יצירת קשר חדש</a>
</p>
</div>

<button class="accordion active">חלקי האתר</button>
<div class="panel" style="max-height: fit-content;">
<p>
<i class="fas fa-heading" style="color: #ff6a1a;"></i> <a href="/dashboard.php?do=header">חלק עליון</a> | 
<i class="fa fa-arrow-circle-right" style="color: #189bd8;"></i> <a href="/dashboard.php?do=sidebar">חלק צידי</a> |
<i class="fas fa-shoe-prints" style="color: #4f5b94;"></i> <a href="/dashboard.php?do=footer">חלק תחתון</a>
</p>
</div>

<button class="accordion active">עיצוב האתר</button>
<div class="panel" style="max-height: fit-content;">
<p>
<i class="fas fa-paint-brush" style="color: #e56a77;"></i> <a href="/dashboard.php?do=style">שינוי תבנית</a>
</p>
</div>

<button class="accordion active">אפליקציות נתמכות</button>
<div class="panel" style="max-height: fit-content;">
<p>
<img src="https://c.disquscdn.com/next/current/marketing/assets/img/brand/favicon-32x32.png"></i> <a href="https://www.disqus.com/" target="_blank">Disqus</a> |
<img src="https://www.cloudflare.com/favicon.ico"> <a href="https://www.cloudflare.com/" target="_blank">קלאודפלייר</a> |
<img src="https://www.tidio.com/wp-content/themes/tidio-wptheme-1.14.9/assets/favicons/favicon.ico"> <a href="https://www.tidio.com/" target="_blank">Tidio</a> |
<img src="https://cdn-images.mailchimp.com/favicons/favicon.ico"> <a href="https://www.mailchimp.com/" target="_blank">מיילצימפ</a> |
<img src="https://www.enable.co.il/wp-content/uploads/2016/11/cropped-fav2-01-32x32.png"> <a href="https://www.enable.co.il/" target="_blank">enable</a>
</p>
</div>

<button class="accordion active">הגדרות</button>
<div class="panel" style="max-height: fit-content;">
<p>
<i class="fas fa-cog" style="color: #666666;"></i> <a href="/dashboard.php?do=settings">הגדרות האתר</a> |
<i class="fa fa-lock" style="color: #d34231;"></i> <a href="/?do=security">שינוי סיסמה</a> |
<i class="fa fa-server" style="color: #7467a1;"></i> <a href="/dashboard.php?do=api">API Setup</a> |
<i class="fas fa-sign-out-alt" style="color: #000;"></i> <a href="'.ABS_PATH.'?do=logout">התנתקות</a>
</p>
</div>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>
                	<h2>מאמרים</h2>
                	<div class="col-md-7">
                	    <a href="https://www.websmaking.com/?do=blog/%D7%A9%D7%9E%D7%99%D7%A8%D7%94-%D7%A2%D7%9C-%D7%A8%D7%A1%D7%A4%D7%95%D7%A0%D7%A1%D7%99%D7%91%D7%99%D7%95%D7%AA-%D7%94%D7%90%D7%AA%D7%A8" target="_blank"><div style="width: 100%;line-height: 300px;font-size: auto;background-size: cover;background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(https://images.unsplash.com/photo-1508921340878-ba53e1f016ec?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1000&q=80);position: unset;text-align: center;border-radius: 8px;color: #fff;">
                	    <p>שמירת גמישות האתר </div></a>
                	</div>
                	<div class="col-md-6">
                	    <a href="/?do=blog/טיפים-לאתר-מצליח" target="_blank"><div style="width: inherit;font-size: auto;line-height: 300px;background-size: cover;background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1000&q=80);position: unset;text-align: center;border-radius: 8px;color: #fff;">
                	    <p>טיפים לאתר מצליח </div></a>
                	</div>
                	</div>
                	</div>
                	</div>
				<!--<div class="alert1">-->
				<!--</div>-->
			  ';
		else:
			echo '
				Well, that\'s weird ...
			';
		endif;
      break;
}

echo '
  </div>
  
<script>
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}
</script>
</body>
'.(isset($row) ? ($row['isPremium'] == 1 ? '<script src="//code.tidio.co/ny45zmnrrewzsgddirhro02ipmk9sf1m.js" async></script>' : '') : '').'
</html>
';

$db->close();

?>

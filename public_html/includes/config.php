<?php
define('DEVELOPMENT_MODE', 1); // TRUE- Development mode. FALSE- Production mode.

ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
ini_set('display_errors', DEVELOPMENT_MODE);
ini_set('display_startup_errors', DEVELOPMENT_MODE);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Jerusalem");

// https://github.com/CleanTalk/anti-ddos-lite
// Anti-DDOS (Rejects bots from entering the site).
//require "ddos/anti-ddos-lite.php";

if (!isset($_SESSION)) {
    $session_timeout = time()+(1000*60*60*24);
    ini_set('session.gc_maxlifetime', $session_timeout);
    session_set_cookie_params($session_timeout);
    session_start();
    setcookie(session_name(), session_id(), $session_timeout);
}

// SMTP Server

ini_set('smtp_server', 'websmaking.com');
ini_set('smtp_port', '465'); //25
ini_set('auth_username', 'no-reply@websmaking.com');
ini_set('auth_password', 'khh{dwI}*65[');


//ini_set('session.use_cookies', '0');

// $_SERVER['SERVER_NAME']
// $_SERVER['REQUEST_URI']
define('HOST_URL', (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']); 
define('REQUEST_URI', $_SERVER['REQUEST_URI']); 

define('ABS_PATH', HOST_URL.'/');
define('CURR_PATH', HOST_URL.REQUEST_URI);

define('SYSTEM_VERSION', '0.70.1');
define('UNDER_MAINTENANCE', 0);

//------------------------------Start - includes------------------------------

include('database.php');
include('user.php');
include('functions.php');
include('form.php');
include('encode.php');
//include('encode2.php');
//include('stalker.php'); // Track visitors, and collect data about them.
include('payment.php');

//------------------------------End - includes------------------------------


//------------------------------Start - db------------------------------

//-----------------Connection-----------------------
$dbserver = "localhost";
$dbusername = "websmak1_sites";
$dbname = "websmak1_sites";
$dbpassword = "+^7S^LmKA5W5";
//-----------------Connection-----------------------

//-----------------Connection-----------------------
if (!isset($db))
    $db = new Database(array(
      'db' => $dbname,
      'host' => $dbserver,
      'user' => $dbusername,
      'password' => $dbpassword
    ));
//-----------------Connection-----------------------

// Store all tables name, any changes to tables name should be apply here.
$tables = array(
    'USERS_TBL' => 'buildp_users',
    'SITES_TBL' => 'buildp_sites',
    'PAGES_TBL' => 'buildp_pages',
    'THEMES_TBL' => 'buildp_themes',
    'RESETS_TBL' => 'buildp_resets',
    'PCATEGORIES_TBL' => 'buildp_pcategories',
    'ADS_TBL' => 'buildp_advertisements',
    'SETTING_TBL' => 'buildp_setting',
    'MAIN_PAGES_TBL' => 'buildp_mainPages',
    'PLUGINS_TBL' => 'buildp_plugins'
);

//------------------------------End - db------------------------------

$user = new User($db);

// Force www.
if (substr($_SERVER['HTTP_HOST'], 0, 3) != 'www') {
	header('HTTP/1.1 301 Moved Permanently');
	header('location: http://www.' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	exit;
}

// Force SSL.
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	exit();
}


$allowed_ips = array('80.230.53.74');

if (UNDER_MAINTENANCE == 1 && !in_array(explode(',', $func->getClientIP())[0], $allowed_ips)) {
    echo '
    <html>
        <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
            body {
                background: #eefff2;
                color: #002200;
            }
            </style>
        </head>
        <body>
            <h2>In maintenance</h2>
            <p>Maintenance is being performed. It will take a while. Please wait warmly</p>
            <pre>503 Service Unavailable</pre>
        </body>
    </html>';
    
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 300');//300 seconds
    exit;
}

////////////////////////
// Start Languages pack.
/////////////////////////

$flags = array(
    'en' => 'images/us.png',
    'he' => 'images/il.png'
);
        
$lang = (isset($_GET['lang']) ? $_GET['lang'] : null);

if (!is_null($lang)) {
    $langDir = new DirectoryIterator(dirname(__FILE__).'/languages');
    $langArray = array();
    foreach ($langDir as $file) if ($file->isFile() && !$file->isDot()) array_push($langArray, explode('.', $file->getFilename())[0] );
    
    if (in_array($lang, $langArray)) {
        $_SESSION['lang'] = $lang;
    }
}

$lang = (isset($_SESSION['lang']) ? $_SESSION['lang'] : null);
if (!is_null($lang)) 
{ include_once 'languages/'.$_SESSION['lang'].'.php'; switch ($_SESSION['lang']) { case 'en': $flag = $flags['he']; $langName='he'; break; case 'he': $flag = $flags['en']; $langName='en'; break; } }
else  { include_once 'languages/he.php'; $flag = $flags['en']; $langName='en'; } // Later on make English the default language.

///////////////////////
// End Languages pack.
///////////////////////

header('Content-Type: text/html; charset=utf-8');

?>

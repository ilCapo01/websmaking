<?php
if (!isset($_SESSION)) session_start();
/*if (!isset($_SESSION)) {
    $session_timeout = 3600;
    ini_set('session.gc_maxlifetime', $session_timeout);
    session_set_cookie_params($session_timeout);
    session_start();
}*/


$dbserver = "localhost";
$dbusername = "websmak1_sites";
$dbname = "websmak1_sites";
$dbpassword = "+^7S^LmKA5W5";

include('database.php');

$db = new Database(array( 'db' => $dbname, 'host' => $dbserver, 'user' => $dbusername, 'password' => $dbpassword ));

include(dirname(__FILE__).'/auth.php');
include(dirname(__FILE__).'/api.php');

$auth = new Auth($db);
$api = new API($db);

?>

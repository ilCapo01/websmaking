<?php
ob_start();
require_once(dirname(__FILE__).'/includes/config.php');
ob_flush();
global $db, $auth, $api;

$isLogged = false;

$do = (isset($_GET['do']) ? rtrim($_GET['do']) : null);
$token = (isset($_POST['token']) ? rtrim($_POST['token']) : null);
$secret = (isset($_POST['secret']) ? rtrim($_POST['secret']) : null);

if (empty($token) || empty($secret)) {
    // Later:
    //$hash = (isset($_POST['hash']) ? rtrim($_POST['hash']) : null);
    // Check if hash is has a life span of over 24h.
    
    // For now:
    header('HTTP/1.1 401 Unauthorized ');
    echo 'You dont have permission to access this page.';
    die;
    
    
}else {
    if ($auth->authenticate($token, $secret)) {
        $isLogged = true;
    }else {
        header('HTTP/1.1 401 Unauthorized '); die;
    }
}


if ($isLogged) {
    // Check timestamp of the last request, if less than 20sec then block the request- anti flood.
    
    //API to allow websites owners to manage their sites more efficiently.
    // TODO:
    // Fetch site's metadata, pages, products (e commerce), etc..
    if (!is_null($do)) {
        
        switch($do) {
            case 'contact':
                $to = (isset($_POST['to']) ? rtrim($_POST['to']) : null);
                $subject = (isset($_POST['subject']) ? rtrim($_POST['subject']) : null);
                
                $api->sendEmail($to, $subject);
                header('location: '. $_SERVER['HTTP_REFERER']);
                break;
            case 'fetch':
                $type = (isset($_GET['a']) ? rtrim($_GET['a']) : null);
                $siteURL = (isset($_GET['url']) ? rtrim($_GET['url']) : null);
                
                if ($type == 'menu') {}
                else if ($type == 'pages') {}
                else if ($type == 'page') {
                    $pageID = (isset($_GET['id']) ? rtrim($_GET['id']) : null);
                }
                else if ($type == 'products') {}
                else if ($type == 'product') {
                    $productID = (isset($_GET['id']) ? rtrim($_GET['id']) : null);
                }
                else {}
                
                break;
            case 'search':
                $sQuery = (isset($_GET['q']) ? $_GET['q'] : null);
                
                if (!is_null($sQuery)) {
                    $sQuery = urldecode($sQuery);
                    
                    $stmt = $db->prepare('SELECT `userID` FROM api_access WHERE token=?'); // `userID` = siteID
                    $stmt->execute(array($token)); $siteID = $stmt->fetch()['userID'];
                    
                    header('Content-Type: application/json');
                    print json_encode($api->searchQuery($sQuery, $siteID));
                }
                
                break;
        }
        
    }
}else {
    // Unauthorized connection.
    // Send headers.
    header('HTTP/1.1 401 Unauthorized ');
    echo 'You dont have permission to access this page.';
    die;
}

$db->close();
?>
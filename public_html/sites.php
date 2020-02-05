<?php
ob_start();
//if (!isset($_SESSION)) session_start();
require_once(dirname(__FILE__).'/includes/config.php');
global $db, $user;

$host = (isset($_SERVER['HTTP_DOMAIN']) ? $_SERVER['HTTP_DOMAIN'] : null); // $_SERVER['SERVER_NAME'];
$uri = $_SERVER['REQUEST_URI']; // FORWARDED REQUEST URI ? 
$uri = (preg_match('/(\/sites.php)/', $uri) ? preg_replace('/(\/sites.php)/', '', $uri) : $uri);

$sul = (isset($_GET['st']) ? $_GET['st'] : null);

//$do = (isset($_GET['do']) ? $_GET['do'] : null);
$idz = (isset($_GET['id']) ? $_GET['id'] : null);

// Change st variable in case a domain is present.
if (!is_null($host)) {
    if (preg_match('/(www.)/', $host)) {
        $host = preg_replace('/(www.)/', '', $host);
    }
    if (preg_match('/(websmaking.com)/', $host) < 1) {
        
        $stmt = $db->prepare('SELECT `siteurl`, `isPremium`, `premiumExpiry` FROM `buildp_sites` WHERE domain=?');
        $stmt->execute(array($host));
        $site = $stmt->fetch();
        
        $sul = $site['siteurl']; 
        
        $diff = floor(($site['premiumExpiry']-time())/1000*60*60*24);
        if ($diff <= 0 && !is_null($host)) {
            header('location: '.ABS_PATH.$sul.'/'.$idz);
            die;
        }
    }
}else {
    if (preg_match('/[A-Z]/', $sul)) { header('location: '.ABS_PATH.strtolower($sul)); }
}

$q2 = $db->prepare("SELECT * FROM `buildp_sites` where siteurl=?");
$q2->execute(array($sul));
if ($q2->rowCount() < 1){
    include dirname(__FILE__).'/templates/404.php';
	die;
}
$b = $q2->fetch();

if (is_null($host)) {
    if ($b['isPremium'] == 1 && !empty($b['domain'])) {
        header('location: http://'.$b['domain'].preg_replace('/(\/'.$b['siteurl'].')/', '', $uri));
        die;
    }
}

$id = $b['id']; $username = $user->getUsernameByID($b['userID']); $css = $b['themeID'];
$logo = $b['logoImage']; $url = $b['siteurl'];

$q5 = $db->prepare("SELECT * FROM `buildp_themes` where id=?");
$q5->execute(array($css));
$t = $q5->fetch();
$themeFolder = $t['folderName'];

// Load template's metadata.
require_once(dirname(__FILE__).'/templates/'.$themeFolder.'/metadata.php');
global $metadata;

//$metadata = json_decode( file_get_contents(dirname(__FILE__).'/templates/metadata.json') );

// ------------------------
// Print to website's head.
// ------------------------
$adsense = '';
if ($b['isPremium'] == 0) {
    $adsense = '
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
      (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-8861171949699172",
        enable_page_level_ads: true
      });
    </script>
    ';
}

$head = '';
$additional = '
    '.$adsense.'
';

// ********
// * Menu *
// ********
$q1 = $db->prepare("SELECT * FROM `buildp_pages` WHERE siteid=? ORDER BY orderNum Asc ");
$q1->execute(array($id));
$rows = $q1->fetchAll();

$menu = array();
$domain = (is_null($host) ? ABS_PATH.$url : 'http://'.$host);
for ($i=0; $i<sizeof($rows); $i++){
    $p = $rows[$i];
    
    if ($p['inMenu'] == 0) continue;
    $mainPage = ($p['pagename'] == 'דף ראשי' ? true : false);
    
	$idb = $p['id']; $name = $p['pagename']; $customURL = $p['customURL'];
    $pageURI = (!empty($customURL) ? '/'.$customURL : '/'.$idb);
	array_push($menu, '<li><a href="'.$domain.($mainPage ? '/' : $pageURI).'">'.$name.'</a></li>');
}


$authorLOGO = '';
if ($b['isPremium'] == 0):
$authorLOGO = '<a href="https://www.websmaking.com" target="_blank"><div style=" background: rgba(68,68,68,.4); width: auto; padding: 5px; border-radius: 2px; overflow: hidden; position: fixed; bottom: 15px; right: 15px; z-index: 9999; "><img src="https://i.websmaking.com/images/white-logo.png" style="width: 100px;"></div></a>';
endif;

/* ----------- Start  ----------- */
$copyright = '<div style=" direction: ltr; text-align: center; font-size: 10pt; margin-top: 5px; margin-bottom: 5px;"><a href="https://www.websmaking.com" target="_blank" style=" color: #000; text-decoration: none; ">Powered by WebsMaking</a></div>';

$q12 = $db->prepare("SELECT * FROM `buildp_pages` WHERE id=? AND siteID=?");
$q12->execute(array($idz, $b['id']));
$q = $q12->fetch();

if (!empty($q['customURL'])) { header('location: '.(!is_null($host) ? 'http://'.$host.'/'.$q['customURL'] : ABS_PATH.'/'.$q['customURL'])); die; }
		
// Load specific page using a custom url.
if ($q == false) {
    $q12 = $db->prepare('SELECT * FROM `buildp_pages` WHERE customURL=? AND siteID=?');
    $q12->execute(array($idz, $b['id']));
    $q = $q12->fetch(); 
}

switch ($q['type']) { // $do
	case 'page':
		
		$head .= '
    '.(empty($q['seo_description']) ? '' : '<meta name="description" content="'.$q['seo_description'].'">').'
	'.(empty($q['seo_keywords']) ? '' : '<meta name="keywords" content="'.$q['seo_keywords'].'">').'
        
    <meta property="og:site_name" content="'.$b['logoImage'].'" />
    '.(empty($q['pagename']) ? '' : '<meta property="og:title" content="'.$q['pagename'].'" />').'
    '.(empty($q['seo_description']) ? '' : '<meta property="og:description" content="'.$q['seo_description'].'" />').'
    <meta property="og:url" content="'.$domain.'/'.(!empty($q['customURL']) ? $q['customURL'] : $idz).'" />
    '.(empty($q['previewImageURL']) ? '' : '<meta property="og:image" content="'.$q['previewImageURL'].'" />').'
    <meta property="og:type" content="website" />';

        include dirname(__FILE__).'/templates/'.$themeFolder.'/header.php';
        echo '<div class="content">';
		include dirname(__FILE__).'/templates/'.$themeFolder.'/content.php';
		echo '</div>';
		echo $authorLOGO;
		include dirname(__FILE__).'/templates/'.$themeFolder.'/footer.php';
		echo $copyright;
		break;
	case 'index':
	case '':
	case null:
		/* ----------- Default  ----------- */
		$q12 = $db->prepare("SELECT * FROM `buildp_pages` where siteid=? order by time asc");
		$q12->execute(array($b['id']));
		$q = $q12->fetch();
		
		
		$head .= '
    '.(empty($q['seo_description']) ? '' : '<meta name="description" content="'.$q['seo_description'].'">').'
	'.(empty($q['seo_keywords']) ? '' : '<meta name="keywords" content="'.$q['seo_keywords'].'">').'
        
    <meta property="og:site_name" content="'.$b['logoImage'].'" />
    '.(empty($q['pagename']) ? '' : '<meta property="og:title" content="'.$q['pagename'].'" />').'
    '.(empty($q['seo_description']) ? '' : '<meta property="og:description" content="'.$q['seo_description'].'" />').'
    <meta property="og:url" content="'.$domain.'" />
    '.(empty($q['previewImageURL']) ? '' : '<meta property="og:image" content="'.$q['previewImageURL'].'" />').'
    <meta property="og:type" content="website" />';
    
		
		include dirname(__FILE__).'/templates/'.$themeFolder.'/header.php';
        echo '<div class="content">';
		include dirname(__FILE__).'/templates/'.$themeFolder.'/content.php';
		echo '</div>';
		echo $authorLOGO;
		include dirname(__FILE__).'/templates/'.$themeFolder.'/footer.php';
		echo $copyright;
		break;
	case 'contact':
	    
	    $errors = '';
	    if (isset($_POST['submit'])) {
            global $func;
            
            $subject = 'קיבלת פניה חדשה באתרך.';
            $cap = (isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : null);
            
            $message = '<br>'; // Show WebsMaking logo in the email.
            foreach ($_POST as $var => $val) {
                if (empty($val)) {
                    $errors = 'לא מלאת אחד מן השדות.';
                }
                if ($var == 'submit' || $var == 'g-recaptcha-response') continue;
                $message .= $func->protectxss($var).':'.$func->protectxss($val).'<br>';
            }
            
            if (empty($message)) {
                $errors = 'חייב למלא תוכן הודעה.';
            }
            
            $secret = (!empty($b['domain']) ? $b['recaptcha_secret'] : '6Le4-MMUAAAAAEVuUiE_AFFuNLL9ZH8zeqk9bnRd');
            $json = json_decode($func->sendRequest('https://www.google.com/recaptcha/api/siteverify', array( 'secret' => $secret , 'response' => $cap, 'remoteip' => $_SERVER['REMOTE_ADDR'] )));
            if (!$json->{'success'}) {
                $errors = 'חובה להשלים אימות אנושי.';
            }
            
            if (empty($errors)) {
                $stmt = $db->prepare('SELECT `mail` FROM `buildp_users` WHERE id=?');
                $stmt->execute(array($b['userID']));
                
                $func->sendMail($stmt->fetch()['mail'], $subject, $message);
                echo '<script type="text/javascript">alert(\'ההודעה נשלחה בהצלחה!\');</script>';
            }
	    }
	    
	    $head .= '
        '.(empty($q['seo_description']) ? '' : '<meta name="description" content="'.$q['seo_description'].'">').'
    	'.(empty($q['seo_keywords']) ? '' : '<meta name="keywords" content="'.$q['seo_keywords'].'">').'
            
        <meta property="og:site_name" content="'.$b['logoImage'].'" />
        '.(empty($q['pagename']) ? '' : '<meta property="og:title" content="'.$q['pagename'].'" />').'
        '.(empty($q['seo_description']) ? '' : '<meta property="og:description" content="'.$q['seo_description'].'" />').'
        <meta property="og:url" content="'.$domain.'/'.(!empty($q['customURL']) ? $q['customURL'] : $idz).'" />
        '.(empty($q['previewImageURL']) ? '' : '<meta property="og:image" content="'.$q['previewImageURL'].'" />').'
        <meta property="og:type" content="website" />';

        include dirname(__FILE__).'/templates/'.$themeFolder.'/header.php';
        echo '<div class="content">';
        echo (!empty($errors) ? '<div class="alert alert-warning">'.$errors.'</div>' : '');
		include dirname(__FILE__).'/templates/'.$themeFolder.'/content.php';
		echo '</div>';
		echo $authorLOGO;
		include dirname(__FILE__).'/templates/'.$themeFolder.'/footer.php';
		echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
		echo $copyright;

	    break;
	case 'custom':
	    
	    echo $q['text'];
	    echo $copyright;
	    
	    break;
	default: 
	    include dirname(__FILE__).'/templates/'.$themeFolder.'/header.php';
	    echo '
	    <style type="text/css">
	    .text {
	        text-align: center;
	    }
	    </style>
	    
	    <div class="text">הדף לא קיים.</div>
	    ';
	    echo $authorLOGO;
	    include dirname(__FILE__).'/templates/'.$themeFolder.'/footer.php';
		echo $copyright;
	    header('HTTP/1.1 404 Not Found');
	    break;
}

/* ----------- End  ----------- */

$db->close();

?>

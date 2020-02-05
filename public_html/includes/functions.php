<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

class Func {

  function sendMail($to, $subject, $message) {
    # Common Headers
    
    /*
	$headers = '';
    $headers .= 'From: WebsMaking <no-reply@websmaking.com>'.PHP_EOL;
    $headers .= 'Reply-To: no-reply <no-reply@websmaking.com'.PHP_EOL;
    $headers .= 'Return-Path: BuildPass <noreply>'.PHP_EOL; // these two to set reply address
    $headers .= "X-Mailer: PHP v".phpversion().PHP_EOL; // These two to help avoid spam-filters
    # Boundry for marking the split & Multitype Headers
    $mime_boundary=md5(time());
    $headers .= 'MIME-Version: 1.0'.PHP_EOL;
    $headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\"".PHP_EOL;
    */
    //return mail($to, $subject, $message, $headers);
    
    $mail = new PHPMailer(true);
    
    try {
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        $mail->Host       = 'websmaking.com';
        $mail->SMTPAuth   = true; 
        $mail->Username   = 'websmak1'; 
        $mail->Password   = 'lxJz0D[EA!346s';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 290; 
        
        $mail->setFrom('noreply@websmaking.com', 'WebsMaking.com');
        $mail->addAddress($to, ''); 
        
        //$mail->addCustomHeader('X-custom-header', 'custom-value');
        $mail->CharSet = 'utf-8';
        //$mail->Encoding = '16bit'; //base64
        
        $mail->isHTML(true); 
        $mail->Subject = $subject;//mb_convert_encoding($subject, mb_detect_encoding($subject)); 
        $mail->Body    = $message;//mb_convert_encoding($message, mb_detect_encoding($message));
        $mail->AltBody = $message;//mb_convert_encoding($message, mb_detect_encoding($message));
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
    return false;
  }

  function protectxss($str) {
    return htmlspecialchars(htmlentities(stripslashes(rtrim($str)), ENT_QUOTES ), ENT_QUOTES);
  }
  
    function securityToken()
    {
        if (!isset($_SESSION['token']) || count($_POST) === 0 && count($_GET) === 0) $_SESSION['token'] = bin2hex(random_bytes(32));
        return $_SESSION['token'];
    }
    
    function check_securityToken($token)
    {
        return (htmlspecialchars(trim($token)) === $this->securityToken());
    }
  
    function validateEmailDomain($email)
    {
    	$domains = array(
    		"walla.co.il",
    		"walla.com",
    		"gmail.com",
    		"hotmail.com",
    		'live.com',
    		"yahoo.com",
    		"protonmail.com"
    	);
    	foreach ($domains as $domain) {
    		$pos = strpos($email, $domain, strlen($email) - strlen($domain));
    		if ($pos === false)
    			continue;
    		if ($pos == 0 || $email[(int) $pos - 1] == "@" || $email[(int) $pos - 1] == ".")
    			return true;
    	}
    	return false;
    }
    
    /*function calculateSize($sizeBytes) {
        $kb = 1024000;
        
        return floor($sizeBytes/($kb*1024)). 'KB(s)';
    }*/
    
    function calculateSize($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
         $bytes /= (1 << (10 * $pow)); 
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    } 
    
    public function getClientIP(){       
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            return  $_SERVER["HTTP_X_FORWARDED_FOR"];  
        }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) { 
            return $_SERVER["REMOTE_ADDR"]; 
        }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER["HTTP_CLIENT_IP"]; 
        } 
        
        return '';
    }
    
    function check_ssl($url) {
        $stream = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
        $read = fopen($url, "rb", false, $stream);
        $cont = stream_context_get_params($read);
        $var = ($cont["options"]["ssl"]["peer_certificate"]);
        $result = (!is_null($var)) ? true : false;
        return $result;
    }
    
    function sendJSON($url = '', $json = array()) {
		$content = json_encode($json);

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
		        array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 201 )
		    die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		curl_close($curl);
		return json_decode($json_response, true);
	}

	function receiveJSON() {
		$v = json_decode(stripslashes(file_get_contents("php://input")));
		if(empty($v))  die('Not found');
		return $v;
	}
	
	private function exportSite($siteID, $path, $db) {
	    $files = array();
	    
	    // Site's metadata.
	    $q1 = $db->prepare('SELECT * FROM `buildp_sites` WHERE id=?');
	    $q1->execute(array($siteID));
	    $site = $q1->fetch();
	    
	    // All site's pages.
	    $q2 = $db->prepare('SELECT * FROM `buildp_pages` WHERE siteID=?');
	    $q2->execute(array($site['id']));
	    $pages = $q2->fetchAll();
	    
	    // Exports all pages to html files.
	    foreach ($pages as $page) {
	        $fileName = $path.'/'.$page['id'].'.html';
	        $fp = fopen($fileName, 'w');
    	    fwrite($fp, $site['header']);
    	    fwrite($fp, $site['sidebar']);
    	    fwrite($fp, $page['text']);
    	    fwrite($fp, $site['footer']);
    	    fclose($fp);
    	    
    	    array_push($files, $fileName);
	    }
	    
	    // Export site's metadata to json file.
	    $metadata = $path.'/'.$site['id'].'.json';
	    $fp = fopen($metadata, 'w');
	    fwrite($fp, json_encode(array(
	        'userID' => $site['userID'], 'themeID' => $site['themeID'], 'logoimage' => $site['logoImage'], 'robots' => $site['robots'], 'keywords' => $site['keywords'], 'description' => $site['description'], 'favicon' => $site['favicon'], 'accessCode' => $site['accessCode'], 'time' => $site['time'], 'language_code' => $site['language_code'], 'sidebarVisibility' => $site['sidebarVisibility']
	        )));
	    fclose($fp);
	    array_push($files, $metadata);
	    
	    // Include all files and their paths in an array, and return it.
	    return $files;
	}
	
	function downloadBackup($path, $siteID, $db) {
	    //$path .= '/'.date('d-m-Y H:i:s');
	    //mkdir($path);
	    
	    $files = $this->exportSite($siteID, $path, $db); 
	    
        $zipname = 'backup-'.date('d-m-Y H:i:s').'.zip';
        $zipPath = $path.'/'.$zipname;
        
        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE);
        foreach ($files as $file) {
			if (file_exists($file)) {
            	$zip->addFile($file);
			}
        }
        $zip->close();
		
		
		foreach ($files as $file)
			unlink($file);
        
		
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipPath));
        readfile($zipPath);
		
		/*$download = fopen($zipPath, 'r');
		while (!feof($download)) {
			fread($download, filesize($zipPath));
		}
		fclose($download);
        unlink($zipPath);*/
		
        return $zipname;
    }
	
	function generateQR($text) {
		$curl = curl_init('https://www.google.com/chart?chs=200x200&chld=M|0&cht=qr&chl='.$text);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
		        array(""));
		curl_setopt($curl, CURLOPT_POST, false);
		$qr = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 200 )
		    die("Error: call to URL $url failed with status $status, response $qr, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		curl_close($curl);
		return 'data:image/png;base64,'.base64_encode($qr);
	}
	
	function randomString($length = 16, $capitalLetters = false, $numbers = false, $symbols = false)
	{
		mt_srand(time());
		$chars = 'abcdefghijklmnopqrstuvwxyz';
		if ($capitalLetters)
			$chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ($numbers)
			$chars .= '0123456789';
		if ($symbols)
			$chars .= '!@#$%^&*()_+/\\\'"?><:{}[]';

		$str = '';
		for ($i=0; $i<$length; $i++) $str .= $chars[mt_rand(0, strlen($chars)-1)];
		return $str;
	}
	
	function sendRequest($url, $data = array()) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        
        // In real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS, 
        //          http_build_query(array('postvar1' => 'value1')));
        
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        
        curl_close ($ch);
        
        return $server_output;
	}
	
	// @param $type string : 'bot' / 'browser' / 'mobile'
	function check_user_agent ( $type = NULL ) {
        $user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
        
        if ( $type == 'bot' ) {
                // matches popular bots
                if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
                        return true;
                        // watchmouse|pingdom\.com are "uptime services"
                }
        } else if ( $type == 'browser' ) {
                // matches core browser types
                if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
                        return true;
                }
        } else if ( $type == 'mobile' ) {
                // matches popular mobile devices that have small screens and/or touch inputs
                // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
                // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
                if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
                        // these are the most common
                        return true;
                } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
                        // these are less common, and might not be worth checking
                        return true;
                }
        }
        return false;
    }
    
    // Check if works, and improve it.
    function blockCommands($txt, $blackList = array()) {
        foreach($blackList as $var => $val) {
            $isBlocked = preg_match('/^['.$val.']$/', $txt);
            if ($isBlocked) return true;
        }
        return false;
    }
}
$func = new Func;

?>

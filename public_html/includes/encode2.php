<?php

class Encode {
    
    function Encode() {}
    
    function encrypt($data, $key) {}
    
    function decrypt($data, $key) {}
    
}

class AES {
	function encryptMessage($text, $key, $iv) {
		$cipharedtext = openssl_encrypt($text, 'aes-128-gcm', $key, 0, $iv, $tag);
		return array($cipharedtext, $tag);
	}

	function decryptMessage($crypttext, $key, $iv, $tag) {
		return openssl_decrypt($crypttext, 'aes-128-gcm', $key, 0, $iv, $tag);
	}

	// iv and key.
	function generateKeys($seed = '', $keyLength = 32) {
		$ivlen = openssl_cipher_iv_length('aes-128-gcm');
	    $iv = bin2hex(openssl_random_pseudo_bytes($ivlen));
		if (!empty($seed))
			$key = $this->randomKey($seed, $keyLength);
		else
			$key = bin2hex(openssl_random_pseudo_bytes($keyLength));
		return array($key, $iv);
	}

	function randomKey($seed = '', $length = 32) {
		$seed = crc32($seed) % 1000 + 1000;
		mt_srand($seed);
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $str = '';
	    for ($i=0; $i<$length; $i++) $str .= $chars[mt_rand(0, strlen($chars)-1)];
		return bin2hex($str);
	}
}

class RSA {
	function generateKeysPair() {
		$res = openssl_pkey_new(array(
    		"digest_alg" => "sha512",
    		"private_key_bits" => 4096,
    		"private_key_type" => OPENSSL_KEYTYPE_RSA,
		));
		openssl_pkey_export($res, $privKey);
		$pubKey = openssl_pkey_get_details($res);
		$pubKey = $pubKey["key"];
		return array($pubKey, $privKey);
	}

	function encryptMessage($str, $pubKey) {
		if (openssl_public_encrypt($str, $encrypted, $pubKey))
            return base64_encode($encrypted);
	}

	function decryptMessage($data, $privKey) {
		if (openssl_private_decrypt(base64_decode($data), $decrypted, $privKey)) {
            return $decrypted;
		}
		return null;
	}
}
$aes = new AES;
$rsa = new RSA;

?>

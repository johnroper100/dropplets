<?php
/*
 * Plugin Name: Encode/Decode for Dropplets
 * Plugin filename: encdec
 * Version: 1.0.0
 */


/*-----------------------------------------------------------------------------------*/
/* Encode/Decode config variables
/*-----------------------------------------------------------------------------------*/
function Encode($key,$dencoded){
	$key = trim($key);
	$dencoded = trim($dencoded);
		//	echo "[" . $key . "][" . $dencoded . "]";
	if ( (strlen($key)>1) && (strlen($dencoded)>1) ) {
		$key = preg_replace('/\D/','',$key);
		if ($key == '') { $key = '2013'; }
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $dencoded, MCRYPT_MODE_CBC, md5(md5($key))));
		//echo "[" . $key . "][" . $dencoded . "][" . $volta . "]";
		//return $volta;		
	} else {
		return '';
	}
}
function Decode($key,$encoded){
	$key = trim($key);
	$encoded = trim($encoded);
	if ( (strlen($key)>1) && (strlen($encoded)>1) ) {
		$key = preg_replace('/\D/','',$key);
		if ($key == '') { $key = '2013'; }
		
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encoded), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		//echo "[" . $key . "][" . $encoded . "][" . $volta . "]";
		//return $volta;
	} else {
		return '';
	}		
}
?>
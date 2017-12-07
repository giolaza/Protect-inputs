<?php 
/**
 * protect.php
 *
 * @category   System security
 * @package    GL
 * @author     Giorgi Lazashvili <giolaza@gmail.com>
 * @version    1.0
 *

 ****************************************************************************************************


--FUNCTIONS--
(mixed) protect($data) - trim and htmlspecialchars
(mixed) protectHTML($data) - trim only


(mixed) post('name') - calls protect($_POST['name'])
(mixed) get('name') - calls protect($_GET['name'])
(mixed) grab('name') - calls if isset protect($_GET['name']) else if isset protect($_POST['name']) else not

(mixed) postHTML($_POST['name'])  - calls protectHTML($_POST['name'])
(mixed) getHTML($_GET['name'])  - calls protectHTML($_GET['name'])
(mixed) grabHTML($_GET['name'])  - calls if isset protectHTML($_GET['name']) else if isset protectHTML($_POST['name']) else not


(string) showDecoded($str) - html_entity_decode



(array) - mustBeArray($input) - checks input if not array returns empty array Ex: $arr=mustBeArray($arr);

--you need to set constant "showCaptcha"=true for use these functions
--Constant - "RECAPTCHA_SECRET_KEY" - reCaptcha secret key
--Constant - "RECAPTCHA_PUBLIC_KEY" - reCaptcha public key

(string) - show_recaptcha() - returns google reCaptcha html
(boolean) - check_recaptcha() - checks google reCaptcha


 ****************************************************************************************************
 */

// protect functions
function protect_do($string) {
	if($string===NULL)return NULL;
	$string = trim($string);

	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	return $string;
}

function protect($data) {
	if (is_array($data)) {
		$data = array_map("protect", $data);
	}
	else {
		$data = protect_do($data);
	}
	return $data;
}

function protectHTML_do($string) {
	if($string===NULL)return NULL;
	$string = trim($string);
	return $string;
}

function protectHTML($data) {
	if (is_array($data)) {
		$data = array_map("protectHTML", $data);
	}
	else {
		$data = protectHTML_do($data);
	}
	return $data;
}



function post($name) {
    if(isset($_POST[$name]))return protect($_POST[$name]);
    else return NULL;
}

function get($name) {
    if(isset($_GET[$name]))return protect($_GET[$name]);
    else return NULL;
}

function grab($name) {
	if (isset($_GET[$name])) return protectHTML($_GET[$name]);
	else if (isset($_POST[$name])) return protectHTML($_POST[$name]);
	else return NULL;
}



function postHTML($name) {
    if(isset($_POST[$name]))return protectHTML($_POST[$name]);
    else return NULL;
}
function getHTML($name) {
    if(isset($_GET[$name]))return protectHTML($_GET[$name]);
    else return NULL;
}
function grabHTML($name) {
	if (isset($_GET[$name])) return protectHTML($_GET[$name]);
	else if (isset($_POST[$name])) return protectHTML($_POST[$name]);
	else return NULL;
}


// decode htmlspecialchars
function showDecoded($string) {
	return html_entity_decode($string, ENT_QUOTES, 'UTF-8');
}

function check_recaptcha() {
    if (!showCaptcha) return true;

    $curl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . RECAPTCHA_SECRET_KEY . '&response=' . urlencode($_POST['g-recaptcha-response']) . '&remoteip=' . urlencode($_SERVER['REMOTE_ADDR']);
    $res = file_get_contents($curl);
    $response = json_decode($res, true);

    if ($response['success'] == true) return true;
    else return false;


}



function show_recaptcha(){
    if(!showCaptcha)return '';

    return '
		<script src="https://www.google.com/recaptcha/api.js"></script>
		<div class="g-recaptcha" data-sitekey="'.RECAPTCHA_PUBLIC_KEY.'" style="display: inline-block"></div>
	';
}


function mustBeArray($var){
	if(!is_array($var))return array();
	else return $var;
}


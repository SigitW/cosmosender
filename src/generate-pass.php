<?php 
require_once("Auth.php");

$auth = new Auth;

$str  = "123";
$enc  = $auth->encrypt($str);
$dec  = $auth->decrypt($enc);
echo 'asli = ' . $str . '<br/>';
echo 'encrypt = ' . $enc . '<br/>';
echo 'decrypt = ' . $dec . '<br/>';
?>
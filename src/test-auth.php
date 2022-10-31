<?php 

require_once("Auth.php");
include '../baseurl.php';

$username = $_POST['name'];
$password = $_POST['pass'];

$auth = new Auth;

session_start();
try {
    $data = $auth->doAuth($username, $password);
    
    $_SESSION['islogin'] = true;
    $_SESSION['name']    = $data['user']['user_name'];

    header("Location:".$baseurl);
} catch (\Throwable $th) {

    $_SESSION['islogin'] = false;
    $_SESSION['loginerror'] = $th->getMessage();
    
    header("Location:".$baseurl."login.php");
}



?>
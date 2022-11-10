<?php 
    include 'baseurl.php';
    session_start();
    if (!isset($_SESSION['islogin'])){
        header("Location:".$baseurl."login.php");
    } else {
        if (!$_SESSION['islogin']){
            header("Location:".$baseurl."login.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmo Sender</title>
    <link rel="icon" type="image/x-icon" href="<?= $baseurl; ?>asset/faveicon.ico">
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/codemirror/theme/dracula.css">
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/style.css">
    <script src="<?= $baseurl; ?>asset/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= $baseurl; ?>asset/codemirror/lib/codemirror.js"></script>
    <script src="<?= $baseurl; ?>asset/codemirror/mode/xml/xml.js"></script>
    <script src="<?= $baseurl; ?>asset/codemirror/addon/edit/matchbrackets.js"></script>
    <script src="<?= $baseurl; ?>asset/codemirror/addon/edit/search.js"></script>
    <script src="<?= $baseurl; ?>asset/codemirror/addon/edit/searchcursor.js"></script>
</head>

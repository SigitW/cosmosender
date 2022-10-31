<?php 
include 'header.php';
session_destroy();
header("Location:".$baseurl."login.php");
?>
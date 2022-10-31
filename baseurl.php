<?php 
   include "src/common/common-config.php";
   $appname = "cosmosender";
   $base    = "";

   if ($__TYPE_PROJECT == "prod")
       $base = "https://"; 
   else
       $base = "http://"; 

   $baseurl = $base . $_SERVER['SERVER_NAME'] . "/" . $appname . "/";
?>
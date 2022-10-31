<?php 
    include 'baseurl.php';
    session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/style.css">
</head>
<body class="container">
    <div class="text-center mt-3 align-middle">
        <h1><span style="color:teal;font-style: oblique;">403</span> ACCESS DENIED</h1>
    </div>
    <div class="mt-3 text-center">
        <a href="<?= $baseurl?>login.php">to Login Page</a>
    </div>
</body>
<?php include 'footer.php' ?>
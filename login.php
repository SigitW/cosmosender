<?php 

    include "src/common/common-config.php";
    $appname = "cosmosender";
    $base    = "";

    if ($__TYPE_PROJECT == "prod")
        $base = "https://"; 
    else
        $base = "http://"; 

    $baseurl = $base . $_SERVER['SERVER_NAME'] . "/" . $appname . "/";

    session_start(); 

    $msg = "Akun tidak ditemukan";
    $isErrorLogin = 0;
    if (isset($_SESSION['loginerror'])){
        $data = $_SESSION['loginerror'];
        $msg  = $data;
        $isErrorLogin = 1;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $baseurl; ?>asset/style.css">
</head>
<body class="container-fluid" style="background-image: url('<?= $baseurl ?>asset/login-img-2.jpg');background-size: cover;margin-top:-100px;background-position:center;">
    <div class="row" style="max-height:100%;">
        <div class="col-md-6 col-xs-0" >

        </div>
        <div class="col-md-6 col-xs-12" style="padding: 30px;background-color: #4d4b48;height:100vh;">
            <div style="border-bottom: solid 5px teal;font-size:30px;padding-bottom:10px;" class="mb-3">
            Indraco Mail Sender <br/>& Content Management
            </div>
            <div class="alert alert-danger display-none mb-3"><?= $msg; ?></div>
            <form action="src/test-auth.php" method="POST" id="form-login">
                <label for="" class="mb-2">User Name</label>
                <input type="text" name="name" id="name" class="form-control mb-3">
                <label for="" class="mb-2">Password</label>
                <input type="password" name="pass" id="pass" class="form-control mb-3">
                <div class="float-end">
                    <button class="btn btn-sm btn-light" id="btn-login"><i class="bi bi-arrow-right-short"></i> Login</button>
                </div>
            </form>
            <div class="align-bottom">
                v.1.2
            </div>
        </div>
    </div>
</body>
<?php
    unset($_SESSION['loginerror']);
?>
<?php include 'footer.php' ?>
<script>
    
    const isErrorLogin = '<?= $isErrorLogin; ?>';

    $(document).ready(function(){
        if (isErrorLogin == '1'){
            $(".alert-danger").fadeIn().delay(2000).fadeOut();
        }
    });

    // $("#btn-login").on("click", function(){
        
    //     const name = $("#name").val();
    //     const pass = $("#pass").val();

    //     if (name == "" || pass == ""){
    //         $(".alert-danger").fadeIn().delay(2000).fadeOut();
    //         $(".alert-danger").html("Tolong lengkapi data login");
    //     }

    //     $("#form-login").submit();
    // });
</script>
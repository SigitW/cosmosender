<?php include '../header.php' ?>
<body class="container">
    
<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use TransModel\TransModel;

require_once("../basemodel/TransModel.php");
require_once("response.php");
require("PHPMailer/PHPMailer.php");
require('PHPMailer/SMTP.php');
require('PHPMailer/Exception.php');
date_default_timezone_set("Asia/Jakarta");

doBlast();
function doBlast() : void {
    // inisialisasi class
    $model = new TransModel;
    // end

    // mengambil data yg dipost
    $contentid          = isset($_POST['content_id']) ? $_POST['content_id'] : "";
    $json_relays        = isset($_POST['json_relays']) ? $_POST['json_relays'] : "";
    $json_recipients    = isset($_POST['json_recipients']) ? $_POST['json_recipients'] : "";
    $brandid            = isset($_POST['brand_id']) ? $_POST['brand_id'] : "";
    $contentSubject     = isset($_POST['content_subject']) ? $_POST['content_subject'] : "";
    // end

    // validasi recipient & relays
    if (!isset($json_recipients) || $json_recipients == ""){
        echo 'tidak ditemukan daftar recipients';
        die;
    }

    if (!isset($json_relays) || $json_relays == ""){
        echo 'tidak ditemukan email relays';
        die;
    }
    // end

    // decode masing - masing json
    $relays      = json_decode($json_relays);
    $recipients  = json_decode($json_recipients);
    $jumlahRelay        = count($relays);
    $jumlahRecipients   = count($recipients);
    // end

    // mengambil data content
    $content = "";
    try {
        $contents = $model->select("t_content", [], "WHERE id = '".$contentid."'");
    } catch (\Throwable $th) {
        echo $th->getMessage();
        die;
    }

    // validasi
    if (!isset($contents) || count($contents) == 0){
        echo 'tidak ditemukan content';
        die;
    }

    $content = $contents[0];
    $bodyContent = $content['body_content'];
    // end

    // mengambil data brand
    $brands = "";
    try {
        $brands = $model->select("m_brand", [], "WHERE id = '".$brandid."' ");
    } catch (\Throwable $th) {
        echo $th->getMessage();
        die;
    }

    // validasi
    if (!isset($content) && $content == ""){
        echo 'tidak ditemukan content';
        die;
    }

    $brand = $brands[0];
    $emailAlias = $brand['email_alias'];
    $hasLimit   = $brand['blast_limit'] > 0;
    //end

?>
<h3 class="mb-2"><?= $brand['name']; ?></h3>
<p><?= $contentSubject ?></p>
<!-- <hr> -->
<div class="table-responsive mb-3" style="height: 400px;">
    <table class="table table-borderless table-striped table-hover table-dark">
        <thead>
            <tr>
                <td>#</td>
                <td>From</td>
                <td>To</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
 
<?php

    $inRelay = 0;
    $inRecip = 0;
    if ($jumlahRelay > 0 && $recipients > 0)
    {
        foreach($recipients as $r)
        {
            $relay = $relays[$inRelay];
            
            $data = [
                "body_content" => $bodyContent,
                "subject" => $contentSubject,
                "email_alias" => $emailAlias,
                "email" => $relay->email,
                "password" => $relay->password,
                "port" => $relay->port,
                "host" => $relay->host_name,
                "email_from" => $relay->email_from,
                "recipient" => $r->email,
                "no" => $inRecip + 1,
            ];
                   
            sendBlast($data);

            if ($inRelay == $jumlahRelay - 1){
                sleep(5);
                // echo '<br><br>============ Delay 5 Detik: ==============<br><br>';
                $inRelay = 0;
                
            } else  {
                $inRelay ++;
            }
            
            // if ($hasLimit && $inRecip == $jumlahRecipients - 1){
            //     updateLastId($brandid, $r->id);
            // }

            $inRecip ++;
        }
    }
}


function sendBlast($email = []) : void {

    echo '<tr>';
    echo '<td>'.$email['no']. '</td>';
    echo '<td>host : '.$email['host']. '<br>';
    echo 'pengirim : '.$email['email']. '<br>';
    echo 'sebagai : '.$email['email_from']. ' - ' .$email['email_alias']. '</td>';
    echo '<td>target : '.$email['recipient']. '<br>';
    echo 'waktu : '.date('Y-m-d H:i:s'). '</td>';

    try {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host      = $email['host'];
        $mail->SMTPAuth  = true;
        $mail->Username  = $email['email'];
        $mail->Password  = $email['password'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = $email['port'];
        $mail->setFrom($email['email_from'], $email['email_alias']);
        $mail->addAddress($email['recipient'], '');
        $mail->CharSet    = "UTF-8";
        $mail->isHTML(true);
        $mail->Subject = $email['subject'];
        $mail->Body    = $email['body_content'];
        $mail->AltBody = 'Sorry, cannot show this page. Your email client is not supported a HTML format';
        $mail->send();         
        echo '<td>Success</td>';     
    } catch (Exception $th) {
        echo '<td>'.$th->errorMessage().'</td>';
    } catch(\Throwable $th){
        echo '<td>'.$th->getMessage().'</td>';
    }
    echo '</tr>';
}

function updateLastId($brandid = "", $lastuserid = "") : void {
    $model = new TransModel;
    try {
        $model->update("m_brand", ["last_email_id" => $lastuserid, "last_blast_time" => date('Y-m-d H:i:s')], ["id" => $brandid], "Admin");
    } catch (\Throwable $th) {
        new Exception($th->getMessage(), 1);
    }
}
?>
       </tbody>
    </table>
</div>
<div class="text-center">
    <button class="btn btn-sm btn-success mb-3" onclick="kembali()"><i class="bi bi-chevron-left"></i> back</button>
</div>
</body>

<?php include '../footer.php' ?>
<script>
    function kembali(){
        if (confirm("apakah anda ingin kembali ke halaman blast dan menghentikan proses blast berlangsung ?"))
        {
            history.back();
        }
    }
</script>


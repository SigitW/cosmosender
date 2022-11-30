<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once("../basemodel/TransModel.php");
require_once("response.php");
require("PHPMailer/PHPMailer.php");
require('PHPMailer/SMTP.php');
require('PHPMailer/Exception.php');

$do = $_POST['do'];
switch ($do) 
{
    case 'send-test':
        blastTest();
        break;
    case 'check-ready':
        checkReadyToBlast();
        break;  
    case 'load-by-brand':
        loadCustByBrand();
        break;  
    case 'update-last-email':
        updateLastEmailId();
        break;          
    default:
        hasNotFound("Tidak ditemukan method");
        break;
}

function checkReadyToBlast(){

    $id     = $_POST['brand_id'];
    $model  = new TransModel();
    $data   = [];
    
    try {
        $data = $model->select("m_brand", [], "WHERE id = '".$id."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    $isOk = true;
    if (isset($data) && count($data)){
        $brand = $data[0];
        $interval = $brand['blast_hour_interval'];
        if ($interval > 0){
            $selisih = getSelisihMenit($brand['last_blast_time']);
            $isOk = $selisih >= $interval;
        } 
    }

    hasSuccess("", $isOk);
}

function getSelisihMenit($tglDb){
    date_default_timezone_set("Asia/Jakarta");
    $waktu_awal        = strtotime($tglDb);
    $waktu_akhir       = strtotime(date("Y-m-d H:i:s")); 
    $diff              = $waktu_akhir - $waktu_awal;
    $selisihmenit      = floor($diff / 60);
    return $selisihmenit;
}

function blastTest(){

    $model = new TransModel;
    $brand = null;

    $brandId    = $_POST['brand_id'];
    $recipients = $_POST['recipients'];
    $contentId  = $_POST['content_id'];
    $subject    = $_POST['subject'];
    $relays     = $_POST['relays'];

    try {
        $brand = $model->select("m_brand", [], "WHERE id = '".$brandId."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    $brand = isset($brand) && count($brand) > 0 ? $brand[0] : null;
    if ($brand == null)
        hasInternalError("Brand Tidak ditemukan");

    try {
        $service = $model->select("m_service", [], "WHERE id = '".$brand['service_id']."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    $service = isset($service) && count($service) > 0 ? $service[0] : null;
    if ($service == null)
        hasInternalError("Service Tidak ditemukan");

    $host = null;
    try {
        $host = $model->select("m_email_host", [], "WHERE server_id = '".$service['server_id']."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }    

    $host = isset($host) && count($host) > 0 ? $host[0] : null;
    if ($host == null)
        hasInternalError("Host Tidak ditemukan");
    
    $content = null;
    try {
        $content = $model->select("t_content", [], "WHERE id = '".$contentId."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }    

    $content = isset($content) && count($content) > 0 ? $content[0] : null;
    if ($content == null)
        hasInternalError("Email Relay Tidak ditemukan");      

    $email = new stdClass;    
    $email->host         = $host['host'];
    $email->email_alias  = $brand['email_alias'];
    $email->body_content = $content['body_content'];

    if (isset($recipients))
    {
        $recipients  = json_decode($recipients);

        if (count($recipients) > 0){
            $relayIndex  = 0;
            $relayLength = count($relays) - 1;
            $sentLength  = 5;
            $sentIndex   = 1;   
            foreach ($recipients as $p) 
            {        

                $relayIndex     = $relayIndex == $relayLength ? 0 : $relayIndex + 1;
                $relayId        = $relays[$relayIndex];
                $item_relay     = getRelayById($relayId);
                
                $email->email       = $item_relay['email'];
                $email->password    = $item_relay['password'];
                $email->port        = $item_relay['port'];
                $email->email_from  = $item_relay['email_from'];
                $email->recipient   = $p['recipient'];
                $email->subject     = $subject;

                $email = (array) $email;

                if ($sentIndex <= $sentLength){
                    send($email);
                    $sentIndex ++;
                } else {
                    $sentIndex = 1;
                    send($email);
                    sleep(5);
                }
            }
        } 
    }
    // try {
    //     $mail = new PHPMailer();
    //     $mail->isSMTP();
    //     $mail->Host     = $host['host'];
    //     $mail->SMTPAuth = true;
    //     $mail->Username = $relay['email'];
    //     $mail->Password = $relay['password'];
    //     $mail->SMTPSecure = 'ssl';
    //     $mail->Port       = $relay['port'];
    //     $mail->setFrom($relay['email_from'], $brand['email_alias']);
    //     $mail->addAddress($recipient, '');
    //     $mail->CharSet    = "UTF-8";
    //     $mail->isHTML(true);
    //     $mail->Subject = $subject;
    //     $mail->Body    = $content['body_content'];
    //     $mail->AltBody = 'Sorry, cannot show this page. Your email client is not supported a HTML format';
    //     $mail->send();    
    //     hasSuccess("Berhasil Mengirim Email ke ".$recipient);    
    // } catch (\Throwable $th) {
    //     hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    // }
}

function getRelayById($relayId){
    $model      = new TransModel;
    $relay      = null;
    try {
        $relay = $model->select("m_email_relay", [], "WHERE id = '".$relayId."'");
    } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), 1);
    }    

    $relay = isset($relay) && count($relay) > 0 ? $relay[0] : null;
    if ($relay == null)
        throw new Exception("Email Relay Tidak ditemukan", 1);
    
    return $relay;    
}

function send($email){
    try {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host     = $email['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $email['email'];
        $mail->Password = $email['password'];
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
    } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), 1);
    }
}

function loadCustByBrand(){
    $model      = new TransModel;
    $brandId    = $_POST['brand_id'];
    $data       = [];

    $brands         = $model->select("m_brand", [], "WHERE id = '".$brandId."'");
    $brand          = $brands[0];
    $lastEmailId    = $brand['last_email_id'];
    $emailLimit     = $brand['blast_limit'];
    $hasLastEmailId = isset($lastEmailId) && $lastEmailId != "";
    $hasLimit       = isset($emailLimit) && $emailLimit != "" && $emailLimit != 0;
    $whereBuilder   = "WHERE brand_id = '".$brandId."' AND flag = 'Y' ";

    if ($hasLastEmailId && $hasLimit){
        $whereBuilder .= "AND id > '".$brand['last_email_id']."' ";
    }
    
    $whereBuilder .= "ORDER BY id ";
    $whereBuilderDefault = "ORDER BY id ";
    if ($hasLimit){
        $whereBuilderDefault = "LIMIT ".$emailLimit." ";
        $whereBuilder .= "LIMIT ".$emailLimit." ";
    }

    // first get by creiteria
    try {
        $data = $model->select("mt_customer_email", [], $whereBuilder);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    // if not found get by default createria
    if (!isset($data) || count($data) == 0){
        try {
            $data = $model->select("mt_customer_email", [], "WHERE brand_id = '" . $brandId . "' AND flag = 'Y' " . $whereBuilderDefault);
        } catch (\Throwable $th) {
            hasInternalError($th->getMessage() . " on line : " . $th->getLine());
        }
    }

    // update status ready
    foreach ($data as $p) {
        $update = ["status_blast" => "ready"];
        $where  = ["id" => $p['id']];

        try {
            $model->update("mt_customer_email", $update, $where, "Admin");
        } catch (\Throwable $th) {
            hasInternalError($th->getMessage() . " on line : " . $th->getLine());
        }
    } // end

    hasSuccess("", $data);
}

function updateLastEmailId(){
    date_default_timezone_set("Asia/Jakarta");

    $lastEmailId = $_POST['last_email_id'];
    $brandId     = $_POST['brand_id'];

    $update = [
        "last_email_id" => $lastEmailId,
        "last_blast_time" => date("Y-m-d H:i:s")
    ];

    $whereUpdate = [
        "id" => $brandId
    ];

    $model = new TransModel;
    try {
        $model->update("m_brand", $update, $whereUpdate, "Admin");
    } catch (\Throwable $th) {
        hasInternalError("[blast-api | updateLastEmailId] " . $th->getMessage() . " on line : " . $th->getLine());
    }

    hasSuccess("Berhasil menyimpan data email terakhir. ");
}
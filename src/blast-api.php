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
    default:
        hasNotFound("Tidak ditemukan method");
        break;
}

function blastTest(){

    $model = new TransModel;
    $brand = null;

    $brandId    = $_POST['brand_id'];
    $relayId    = $_POST['relay_id'];
    $recipient  = $_POST['recipient'];
    $contentId  = $_POST['content_id'];
    $subject    = $_POST['subject'];

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
    
    $relay = null;
    try {
        $relay = $model->select("m_email_relay", [], "WHERE id = '".$relayId."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }    

    $relay = isset($relay) && count($relay) > 0 ? $relay[0] : null;
    if ($relay == null)
        hasInternalError("Email Relay Tidak ditemukan");    

    $content = null;
    try {
        $content = $model->select("t_content", [], "WHERE id = '".$contentId."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }    

    $content = isset($content) && count($content) > 0 ? $content[0] : null;
    if ($content == null)
        hasInternalError("Email Relay Tidak ditemukan");       

    try {
        // $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host     = $host['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $relay['email'];
        $mail->Password = $relay['password'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = $relay['port'];
        $mail->setFrom($relay['email'], $relay['email_alias']);
        $mail->addAddress($recipient, '');
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $content['body_content'];
        $mail->AltBody = 'Sorry, cannot show this page. Your email client is not supported a HTML format';
        $mail->send();    
        hasSuccess("Berhasil Mengirim Email");    
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
}
<?php 
//require
require_once("../basemodel/TransModel.php");
require_once("response.php");

// route
$do = $_POST['do'];
switch ($do) {
    case 'load':
        load();
        break;
    case 'save-add':
        saveAdd();
        break;
    case 'load-by-id':
        load($_POST['relay_id']);
        break;
    case 'update':
        update();
        break;    
    default:
        hasNotFound("Function Tidak Ditemukan");
        break;
}
// end

// function here
function load($id = ""){
    $model  = new TransModel;
    $data   = [];

    $where  = "";
    if ($id != "")
        $where = "WHERE id = '".$id."'";
    
    try {
        $data = $model->select("m_email_relay", [], $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    if (count($data) > 0){
        foreach ($data as $i => $p) {
            $data[$i]['host_name'] = "-";
            $host = loadHost($p['host_id']);
            if (count($host) > 0)
                $data[$i]['host_name'] = $host[0]['server_name'] . " / " . $host[0]['host'];
        }
    }
    hasSuccess("",$data);
}

function loadHost($id){
    $model      = new TransModel;
    $data       = [];

    $where = "WHERE id = '".$id."'";

    try {
        $data = $model->select("m_email_host",["host", "server_id"],$where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    if(count($data) > 0){
        foreach ($data as $i => $p) {
            $data[$i]['server_name'] = "-";
            $servers = [];
            try {
                $servers    = $model->select("m_server", ["name"], "WHERE id = '".$p['server_id']."'");
            } catch (\Throwable $th) {
                hasInternalError($th->getMessage() . " on line : " . $th->getLine());
            }
            $server     = count($servers) > 0 ? $servers[0]["name"] : null;
            if ($server != null)
                $data[$i]['server_name'] = $server;
        }
    }

    return $data;
}

function saveAdd(){

    $model = new TransModel;
    $data = [
        "email" => $_POST['email'],
        "password" => $_POST['password'],
        "port" => $_POST['port'],
        "host_id" => $_POST['host'],
        "email_from" => $_POST['email_from'],
        "email_alias" => $_POST['email_alias'],
        "flag" =>  "Y",
    ];

    try {
        $model->store("m_email_relay", $data, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Menyimpan Email ". $data['email']);
}

function update(){

    $model      = new TransModel;
    $objData    = new stdClass;
    
    // jika tidak ada inputan password berrarti tidak berubah
    if ($_POST['password'] != null &&  $_POST['password'] != "")
        $objData->password = $_POST['password'];
    
    $objData->email = $_POST['email'];
    $objData->host_id = $_POST['host'];
    $objData->email_from = $_POST['email_from'];
    $objData->email_alias = $_POST['email_alias'];
    
    $data = (array) $objData;
    $arrId = ["id"=>$_POST['id']];

    try {
        $model->update("m_email_relay", $data, $arrId, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    
    hasSuccess("Berhasil Update Data Email ". $data['email']);
}
?>
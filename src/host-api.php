<?php 

use TransModel\TransModel;

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
        edit();
        break;
    case 'save-edit':
        update();
        break;    
    default:
        hasNotFound("Function Tidak Ditemukan");
        break;
}
// end

// function here
function load(){
    $model  = new TransModel;
    $data   = [];
    try {
        $data = $model->select("m_email_host");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    if (count($data) > 0){
        foreach ($data as $i => $p) {
            $data[$i]['server_name'] = "-";
            if (!empty($p['server_id'])){
                $servers = loadServer($p['server_id']);
                if (count($servers) > 0){
                    $server = $servers[0];
                    $data[$i]['server_name'] = $server['name'];
                }
            }
        }
    }
    hasSuccess("",$data);
}

function loadServer($id){
    $model      = new TransModel;
    $data       = [];

    $where = "WHERE id = '".$id."'";

    try {
        $data = $model->select("m_server",[],$where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    return $data;
}

function saveAdd(){

    $model = new TransModel;
    $data = [
        "host" => $_POST['host'],
        "server_id" => $_POST['server'],
        "flag" =>  "Y",
    ];

    try {
        $model->store("m_email_host", $data, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Menyimpan Host ". $data['host']);
}

function edit(){
    $model = new TransModel;
    $where = "WHERE id = '".$_POST['host_id']."'";
    try {
        $data = $model->select("m_email_host", [], $where);
        hasSuccess("", $data);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
}

function update(){
    $model = new TransModel;
    $where = ["id" => $_POST['host_id']];
    $data  = [
        "host" => $_POST['host'],
        "server_id" => $_POST['server_id']
    ];
    try {
        $model->update("m_email_host", $data, $where, "admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil update data ".$data['host']);
}
?>
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
    case 'edit':
        edit();
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
function load(){
    $model  = new TransModel;
    $data   = [];
    try {
        $data = $model->select("m_server");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("",$data);
}

function saveAdd(){

    $nama   = $_POST['name'];
    $domain = $_POST['domain'];
    $color  = $_POST['color'];

    $data = [
        "name" => $nama,
        "domain" => $domain,
        "color" => $color,
        "flag" => "Y"
    ];

    $model  = new TransModel;
    try {
        $model->store("m_server", $data, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Menyimpan Server ".$nama);
}

function edit(){
    $model = new TransModel();
    $where = "WHERE id='".$_POST['server_id']."'";
    try {
        $data = $model->select("m_server", [], $where);
        hasSuccess("", $data);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
}

function update(){
    $model  = new TransModel();
    $where  = [ "id" => $_POST['server_id']];
    $data   = [
        "name" => $_POST['name'],
        "domain" => $_POST['domain'],
        "color" => $_POST['color']
    ]; 
    try {
        $model->update("m_server", $data, $where, "Admin");
        hasSuccess("Berhasil update data ".$data['name']);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
}
?>
<?php 
//require
require_once("../basemodel/TransModel.php");
require_once("response.php");

// route
$do = $_POST['do'];
switch ($do) {
    case 'load':
        loadBrand();
        break;
    case 'save-add':
        saveAdd();
        break;
    case 'load-by-id':
        loadBrandById();
        break;
    case 'save-edit':
        saveEdit();
        break;    
    default:
        hasNotFound("Function Tidak Ditemukan");
        break;
}
// end

// function here
function loadBrand(){
    $model  = new TransModel;
    $data   = [];
    try {
        $data = $model->select("m_brand");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    if (count($data) > 0){
        foreach ($data as $i => $p) {
            $data[$i]['service_name'] = "-";
            if (!empty($p['service_id'])){
                $services = loadService($p['service_id']);
                if (count($services) > 0){
                    $service = $services[0];
                    $data[$i]['service_name'] = $service['server_name'] . " / " . $service['service'];
                }
            }
        }
    }

    hasSuccess("",$data);
}

function loadService($id){
    $model      = new TransModel;
    $data       = [];

    $where = "WHERE id = '".$id."'";

    try {
        $data = $model->select("m_service",[],$where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    foreach ($data as $i => $p) {    
        $data[$i]['server_name'] = "-";
        $servers = $model->select("m_server", [], "WHERE id = '".$p['server_id']."'");
        if (count($servers) > 0){
            $server = $servers[0];
            $data[$i]['server_name'] = $server['name'];
        }
    }
    return $data;
}

function saveAdd(){

    $model = new TransModel;
    $data = [
        "name" => $_POST['name'],
        "aseet_namespace" => $_POST['newsletter'],
        "domain" => $_POST['domain'],
        "flag" =>  "Y",
        "service_id" => $_POST['service']
    ];

    try {
        $model->store("m_brand", $data, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Menyimpan Brand ".$data['name']);
}

function saveEdit(){

    $model = new TransModel;
    $data = [
        "name" => $_POST['name'],
        "aseet_namespace" => $_POST['newsletter'],
        "domain" => $_POST['domain'],
        "service_id" => $_POST['service']
    ];

    $where = [
        "id" => $_POST['id']
    ];

    try {
        $model->update("m_brand", $data, $where, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Menyimpan Brand ".$data['name']);
}

function loadBrandById() {
    $id     = $_POST['brand_id'];
    $where  = "WHERE id = '".$id."'";
    $model  = new TransModel;
    $data   = [];
    try {
        $data = $model->select("m_brand", [], $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("",$data);
}
?>
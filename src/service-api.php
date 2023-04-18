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
    case 'load-server':
        loadServer();
        break;
    case 'save-add':
        saveAdd();
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

    $id = !isset($_POST['id']) ? "" : $_POST['id'];
    $model      = new TransModel;
    $data       = [];
    $where      = "";
    if ($id != "")
        $where = "WHERE id = '".$id."'";

    try {
        $data = $model->select("m_service", [], $where);
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
    hasSuccess("", $data);
}

function loadServer(){
    $model  = new TransModel;
    $data   = [];
    try {
        $data = $model->select("m_server", [], "WHERE flag = 'Y'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("", $data);
}

function saveAdd(){
    $model  = new TransModel;
    $data = [
        "service" => $_POST['service'],
        "server_id" => $_POST['server'],
        "color" => $_POST['color'],
        "flag" => "Y"
    ];

    try {
        $model->store("m_service", $data, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Menyimpan Service ");
}

function update(){
    $model  = new TransModel;
    $where  = ["id"=>$_POST['service_id']];
    $data   = [
        "service" => $_POST['service'],
        "server_id" => $_POST['server_id'],
        "color" => $_POST['color']
    ];
    try {
        $model->update("m_service", $data, $where, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Update Service ".$data['service']);
}
?>
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
        loadBrandById();
        break;
    case 'store-relay':
        storeRelay();
        break;
    case 'load-relay-by-host':
        loadRelayByHostId();    
    default:
        hasNotFound("Function Tidak Ditemukan");
        break;
}
// end

// function here
function load(){

    $id = !isset($_POST['brand_id']) ? "" : $_POST['brand_id'];

    $model  = new TransModel;
    $where  = $id == "" ? "" : "WHERE id = '".$id."'";
    $data   = [];
    try {
        $data = $model->select("m_brand", [], $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    if (count($data) > 0){
        foreach ($data as $i => $p) {
            $data[$i]['rules']      = loadBlastRuleByBrandId($p['id']);
            $data[$i]['services']   = loadServiceById($p['service_id']);
            $data[$i]['servers']    = getDataServerByBrandId($p['id']);
        }
    }
    hasSuccess("",$data);
}


function loadServiceById($id = ""){
    $model      = new TransModel;
    $data       = [];
    $joinSQL    = "(SELECT ms.id as service_id, ms.service, ms2.name as server_name, ms.color
                    FROM m_service ms INNER JOIN
                    m_server ms2 ON ms2.id = ms.server_id ) a ";
    $where      = "WHERE a.service_id='".$id."'";
    try {
        $data = $model->select($joinSQL, [], $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    return $data;
}

function loadServerById($id){
    $model  = new TransModel;
    $data   = [];
    try {
        $data = $model->select("m_server", [], "WHERE id='".$id."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    return $data;
}

function getDataServerByBrandId($id){
    $model  = new TransModel;
    $data   = [];
    $buildTableJoin = '(SELECT a.brand_id, a.host_id, c.id as server_id, b.host, c.name as server_name, c.color FROM m_blast_rule a
    INNER JOIN m_email_host b ON b.id = a.host_id
    INNER JOIN m_server c ON c.id = b.server_id
    GROUP BY a.brand_id, a.host_id, c.id, b.host, c.name, c.color) a';
    try {
        $data = $model->select($buildTableJoin, [], "WHERE a.brand_id = '".$id."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    return $data;
}

function loadBlastRuleByBrandId($id){
    $model  = new TransModel;
    $select = ["id", "name", "host_id", "type"];
    $where  = "WHERE brand_id = '".$id."' ORDER BY type DESC";
    $data   = [];
    try {
        $data = $model->select("m_blast_rule", $select, $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    if (count($data) == 0)
        return $data;

    foreach ($data as $i => $p) {
        $host = loadHostById($p['host_id']);
        $data[$i]['color']   = count($host) == 0 ? "" : $host['color']; 
        $data[$i]['details'] = loadBlastRuleRelayByRuleId($p['id']);
    }

    return $data;
}

function loadHostById($id) : array {
    $model      = new TransModel;
    $datas      = [];
    
    try {
        $datas = $model->select("m_email_host", [], "WHERE id = '".$id."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    $data       = $datas[0];
    $servers    = [];
    try {
        $servers = $model->select("m_server", ["color"], "WHERE id='".$data['server_id']."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    $server         = $servers[0];
    $data['color']  = $server['color'];
    return $data;
}

function loadBlastRuleRelayByRuleId($id){
    $model  = new TransModel;
    $select = ["id", "relay_id"];
    $where  = "WHERE rule_id = '".$id."'";
    $data   = [];
    try {
        $data = $model->select("m_blast_rule_detail", $select, $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    if (count($data) == 0)
        return $data;

    foreach ($data as $i => $p) {
        $relays = loadRelayByDetailId($p['relay_id']);
        if (count($relays) > 0){
            $data[$i]['host_name'] = count($relays) == 0 ? "-" : $relays[0]['host_name'];
            $data[$i]['email'] = count($relays) == 0 ? "-" : $relays[0]['email'];
        }
    }
    return $data;
}

function loadRelayByDetailId($id){
    $model  = new TransModel;
    $select = ["id", "email", "host_id"];
    $where  = "WHERE id = '".$id."'";
    $data   = [];
    try {
        $data = $model->select("m_email_relay", $select, $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    if (count($data) > 0){
        foreach ($data as $i => $p) {
            $data[$i]['host_name'] = '-';
            $hosts = $model->select("m_email_host", ["id","host"], "WHERE id = '".$p['host_id']."'");
            if (count($hosts) > 0)
                $data[$i]['host_name'] = $hosts[0]['host'];
        }
    }
    return $data;
}

function saveAdd(){
    $model  = new TransModel;
    $data   = [
        "name" => $_POST['name'],
        "host_id" => $_POST['host'],
        "brand_id" => $_POST['brand'],
        "type" => $_POST['type'],
        "flag" =>  "Y",
    ];

    try {
        $model->store("m_blast_rule", $data, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Menyimpan ".$data['name']);
}

function storeRelay(){
    $model  = new TransModel;
    $data   = [
        "relay_id" => $_POST['relay_id'],
        "rule_id" => $_POST['rule_id'],
        "flag" => "Y"
    ];

    try {
        $model->store("m_blast_rule_detail", $data, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    hasSuccess("Berhasil Menambahkan Relay");
}

function loadRelayByHostId(){
    $id     = $_POST['host_id'];
    $model  = new TransModel;
    $data   = [];
    $where  = "WHERE host_id = '".$id."'";
    try {
        $data = $model->select("m_email_relay", [], $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    hasSuccess("", $data);
}




?>
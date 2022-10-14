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
    default:
        hasNotFound("Function Tidak Ditemukan");
        break;
}
// end

// function here
function load(){
    $id = $_POST['brand_id'];
    $model  = new TransModel;
    $where = "WHERE brand_id = '".$id."' ORDER BY date_namespace DESC, time_namespace DESC";
    $data   = [];
    try {
        $data = $model->select("t_content", [], $where);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("",$data);
}
?>
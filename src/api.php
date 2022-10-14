<?php 

require_once("../basemodel/TransModel.php");
require_once("response.php");

$do = $_POST['do'];
switch ($do) 
{
    case 'load-service':
        loadService();
        break;
    default:
        hasNotFound("Tidak ditemukan method");
        break;
}

function loadService(){
    $model  = new TransModel;
    $result = [];
    try {
        $result = $model->select("m_service");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("", $result);
}
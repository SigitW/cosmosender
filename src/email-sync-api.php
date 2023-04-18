<?php

use TransModel\TransModel;

//require
require_once("../basemodel/TransModel.php");
require_once("response.php");

// route
$do = $_POST['do'];
switch ($do) {
    case 'load-by-brand':
        loadByBrand();
        break;
    case 'load-brand':
        loadBrand();
        break;
    case 'update':
        update();
        break;
    case 'sync-to-db':
        syncEmailToDb();
        break;        
    case 'save-config':
        saveConfig();
        break;
    case 'load-config':
        loadConfig();
        break;        
    default:
        hasNotFound("Function Tidak Ditemukan");
        break;
}
// end

function loadByBrand(){
    $model      = new TransModel;
    $brandId    = $_POST['brand_id'];
    $data       = [];
    try {
        $data = $model->select("mt_customer_email", [], "WHERE brand_id = '".$brandId."' AND flag = 'Y'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    $page = 1;
    $perPage = 10;
    if (count($data) > 0){
        foreach ($data as $i => $p) {
            $num = $i + 1;
            $data[$i]['num'] = $num;

            //menambahkan atribute page;
            $data[$i]['page'] = $page;

            // menghitung halaman
            $maxPage = $page * $perPage;
            if ($maxPage == $num){
                $page++;
            }
            // end
        }
    }
    hasSuccess("", $data, $page);
}

function loadBrand(){
    $model = new TransModel;
    $sql = "(SELECT
            mb.*,
            CASE WHEN mc.jumlah is NULL THEN '0' ELSE mc.jumlah END as jumlah,
            ms.service,
            ms2.domain as server_domain
            FROM m_brand mb 
            LEFT JOIN (
                SELECT 
                mce.brand_id, 
                COUNT(mce.brand_id) as jumlah 
                FROM mt_customer_email mce WHERE mce.flag = 'Y' GROUP BY 
                mce.brand_id
            ) mc ON mc.brand_id = mb.id
            INNER JOIN m_service ms ON ms.id = mb.service_id 
            INNER JOIN m_server ms2 ON ms2.id = ms.server_id) a";

    try {
        $data = $model->select($sql);
        hasSuccess("", $data);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
}

function hilangkanPetik($str){
    $res = "";
    $arrStr = explode("'", $str);
    if (count($arrStr) > 0){
        $i = 0;
        while ($i < count($arrStr)){
            $res .= $arrStr[$i];
            $i++;
        }
    }
    return $res;
}

function syncEmailToDb(){
    $model          = new TransModel;
    $brandId        = $_POST['brand_id'];
    $strJson        = $_POST['str_email'];
    $emailColumn    = $_POST['email_column'];
    $nameColumn     = $_POST['name_column'];
    $decStr         = json_decode($strJson);

    // loop dan fetch master table email
    // membuat nomor batch
    $nobatch = $brandId . date("ymdhis");
    foreach($decStr as $i => $p){

        // menghilangkan tanda petik
        $nama = "";
        if ($nameColumn != ""){
           $nama = hilangkanPetik($p->$nameColumn);
        }
        
        // get data table master email sync
        $getDataEmail   = $model->select("mt_customer_email", [], "WHERE email = '".$p->email."' AND brand_id = '".$brandId."'");
        $isFound        = count($getDataEmail) > 0;
        if ($isFound)
        {
            $data = $getDataEmail[0];    
            $update = [
                "no_batch" => $nobatch,
                "flag" => "Y"
            ];

            try {
                $model->update("mt_customer_email", $update, ["id"=>$data['id']], "Admin");
            } catch (\Throwable $th) {
                hasInternalError($th->getMessage() . " on line : " . $th->getLine());
            }
        } else {

            // jika tidak ditemukan maka insert
            $insert = [
                "brand_id" => $brandId,
                "nama" => $nama,
                "email" => $p->$emailColumn,
                "no_batch" => $nobatch,
                "flag" => "Y"
            ];

            try {
                $model->store("mt_customer_email", $insert, "Admin");
            } catch (\Throwable $th) {
                hasInternalError($th->getMessage() . " on line : " . $th->getLine());
            }
        }
    }
    // end

    // setelah loop update insert
    // loop untuk mematikan email
    // maka update flag = 'N'
    $listNotAvail       = $model->select("mt_customer_email", [], "WHERE no_batch != '".$nobatch."' AND brand_id = '".$brandId."'");
    $isAvailNonActive   = count($listNotAvail) > 0;
    if ($isAvailNonActive){
        foreach ($listNotAvail as $i => $p) {
            $where = ["id"=> $p['id']];
            $update = ["flag" => "N"];
            try {
                $model->update("mt_customer_email", $update, $where, "Admin");
            } catch (\Throwable $th) {
                hasInternalError($th->getMessage() . " on line : " . $th->getLine());
            }
        }
    }

    // update last sync
    $updateBrand = ["email_last_sync"=>date('Y-m-d h:i:s')];
    $whereUpdate = ["id" => $brandId];
    try {
        $model->update("m_brand", $updateBrand, $whereUpdate, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
    hasSuccess("Berhasil Sinkronisasi Email");
}

function saveConfig(){
    $model = new TransModel;
    
    $brandId        = $_POST['brand_id'];
    $tbname         = $_POST['tbname'];
    $emailColumn    = $_POST['email_column'];
    $nameColumn     = $_POST['name_column'];
    $whereClause    = $_POST['where_clause'];
    $whereValue     = $_POST['where_value'];

    $getDataTable = null;
    try {
        $getDataTable = $model->select("m_email_sync_table", ["id"], "WHERE brand_id = '".$brandId."'");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }

    $isFound = $getDataTable != null && isset($getDataTable);

    if ($isFound){
        // maka update
        $updateTable = [
            "table_name" => $tbname,
            "where_clause" => $whereClause,
            "email_column" => $emailColumn,
            "name_column" => $nameColumn,
            "where_value" => $whereValue
        ];

        try {
            $model->update("m_email_sync_table", $updateTable, ["id" => $getDataTable[0]['id']], "Admin");
        } catch (\Throwable $th) {
            hasInternalError($th->getMessage() . " on line : " . $th->getLine());
        }

        hasSuccess("Berhasil Men-Update Data Konfigurasi");

    } else {
        // maka insert
        $insertTable = [
            "brand_id" => $brandId,
            "table_name" => $tbname,
            "where_clause" => $whereClause,
            "where_value" => $whereValue,
            "email_column" => $emailColumn,
            "name_column" => $nameColumn,
            "flag" => "Y"
        ];
        try {
            $model->store("m_email_sync_table", $insertTable, "Admin");
        } catch (\Throwable $th) {
            hasInternalError($th->getMessage() . " on line : " . $th->getLine());
        }

        hasSuccess("Berhasil Menambahkan Data Konfigurasi");
    }
}

function loadConfig(){
    $model = new TransModel;
    $brandId = $_POST['brand_id'];
    try {
        $data = $model->select("m_email_sync_table", [], "WHERE brand_id = '".$brandId."'");
        hasSuccess("", $data);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . " on line : " . $th->getLine());
    }
}

?>


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
    case 'load-content-by-id':
        loadContentById();
        break;   
    case 'update-content':
        updateContent(); 
        break;          
    case 'upload-asset':
        uploadAsset();
        break;
    case 'create-content':
        createContent();
        break;    
    case 'update-materi':
        updateEdit();
        break;       
    case 'insert-name-asset':
        insertNameAsset();
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

function loadContentById(){
    $model  = new TransModel;
    $id     = $_POST['content_id'];
    $obj    = new stdClass;
    try {
        $datacontent = getContentBodyById($id);
        // $datacontent = $model->select("(SELECT body_content FROM t_content) a",[],["WHERE a.id = '".$id."' "]);
        // $datacontent = isset($datacontent) && count($datacontent) > 0 ? $datacontent[0]['body_content'] : "";
        // $datacontent = "";
        // $dataasset      = getAssetByContentId($id);
        $sqlAsset = "(SELECT 
                    mb.domain, 
                    mb.aseet_namespace, 
                    tc.date_namespace,
                    tc.time_namespace,
                    ta.name,
                    mb.actual_path,
                    mb.content_domain,
                    tc.subject
                    FROM t_asset ta INNER JOIN
                    t_content tc ON tc.id = ta.content_id INNER JOIN
                    m_brand mb ON mb.id = tc.brand_id  
                    WHERE ta.flag = 'Y'
                    AND tc.id = '".$id."' 
                    ORDER BY ta.created_at DESC)a";
        // $dataasset      = getAssetByContentId($id);
        $dataasset = $model->select($sqlAsset);

        $sqlPath = "(SELECT 
                    mb.domain,
                    mb.aseet_namespace,
                    tc.date_namespace, 
                    tc.time_namespace
                    FROM t_content tc INNER JOIN
                    m_brand mb ON mb.id = tc.brand_id 
                    WHERE tc.id = '".$id."')a";
        // $pathcontent    = getPathContent($id);
        $pathcontent = $model->select($sqlPath);
        $pathcontent = isset($pathcontent) && count($pathcontent) > 0 ? $pathcontent[0] : "";

        // generate array of img url;
        $arrayimg = array();
        $availasset = $dataasset != "" && count($dataasset);
        $subject = "";
        if ($availasset){
            foreach ($dataasset as $i => $asset) {
                $arrayimg[$i] = $asset['content_domain'] . $asset['domain'] . "/" . 
                $asset['aseet_namespace'] . "/" . $asset['date_namespace'] . "/" . 
                $asset['time_namespace'] . "/" . 'img' . "/". $asset['name'];

                $subject = $asset['subject'];
            }
        }
        // end
        
        //path content concate;
        $p = $pathcontent;
        $path = "/" . $p['domain'] . "/" . 
        $p['aseet_namespace'] . "/" . $p['date_namespace'] . "/" . 
        $p['time_namespace'];
        // end

        // $obj = new stdClass;
        $obj->content = $datacontent;
        $obj->asset = $arrayimg;
        $obj->path = $path;
        $obj->subject = $subject;

    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . ', on line : ' . $th->getLine());
    }

    hasSuccess("success", $obj);    
}

function getContentBodyById($id){
    include 'common/local-config.php';
    $data = $conn->query("SELECT body_content FROM t_content WHERE id = '".$id."'");
    $conn->close();

    $availdata = $data->num_rows > 0;

    $res = "";
    if($availdata){
        while($p = $data->fetch_assoc()){
            $res = $p['body_content'];
        }
    }
    return $res;
}

function updateContent(){

    $id         = $_POST['content_id'];
    $content    = $_POST['content'];

    include 'common/local-config.php';
    try {
        $sql    = "UPDATE t_content SET body_content = '".$content."' WHERE id = '".$id."' ";
        $action = $conn->query($sql);
        if ($action !== TRUE) {
            hasInternalError("[TransModel] t_content ". $conn->error, 1);
        } 
        hasSuccess("Success Update " . $id);
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . ', on line : ' . $th->getLine());
    }

    // if ($res == "success"){

    //     $pathcontent = getPathContent($id);
    //     //path content concate;
    //     $p = $pathcontent;
    //     $path = getBaseLocation() . "/" . $p->domain . "/" . 
    //     $p->asset_namespace . "/" . $p->date_namespace . "/" . 
    //     $p->time_namespace . "/";

    //     $myfile = fopen($path .  'index.html', "w");
    //     if (!$myfile){
    //         die(hasInternalError("cannot write file"));
    //     } else {
    //         fwrite($myfile, $content);
    //         fclose($myfile);
    //         chmod($path . 'index.html', 0777); 
    //         hasSuccess("Success writing file");
    //     }
    // }
}


function updateContentById($contentid = "", $bodycontent = ""){
    include 'local-config.php';
    try {
        $sql    = "UPDATE t_content SET body_content = '".$bodycontent."' WHERE id = '".$contentid."' ";
        $action = $conn->query($sql);
        if ($action !== TRUE) {
            hasInternalError($th->getMessage() . ', on line : ' . $th->getLine());
        } 
        hasSuccess("Success Update");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . ', on line : ' . $th->getLine());
    }
}

function uploadAsset(){
    $model      = new TransModel;
    $id         = $_POST['content_id'];
    $type       = $_POST['type'];
    $filename   = $id . '_' . hrtime(true) . '.' . $type;

    $insert = [
        "content_id" => $id,
        "name" => $filename,
        "flag" => "Y"
    ];

    try {
        $model->store("t_asset", $insert, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . ', on line : ' . $th->getLine());
    }

    $data = new stdClass;
    $data->filename = $filename;
    hasSuccess("Berhasil Menyimpan Nama File", $data);
}

function createContent(){

    $model = new TransModel;
    $obj = new stdClass;
    $obj->brandid = $_POST['brandid'];
    $obj->date = $_POST['date'];
    $obj->time = $_POST['time'];
    $obj->materi = $_POST['materi'];
    $obj->subject = $_POST['subject'];

    $notavaildatetime = $obj->date == "" || $obj->time == "";

    if ($notavaildatetime) {
        hasInternalError("Input Date And Time Folder in form is Mandatory !");
    } 

    $where = "WHERE brand_id = '".$obj->brandid."' AND date_namespace = '".$obj->date."' AND time_namespace = '".$obj->time."' ";
    $dataContent = $model->select("t_content", [], $where);
    if (isset($dataContent) && count($dataContent) > 0) {
        hasInternalError("Content Data Has Stored Before !");
    }

    $data = [
        "brand_id" => $obj->brandid,
        "date_namespace" => $obj->date,
        "time_namespace" => $obj->time,
        "materi_name" => $obj->materi,
        "subject" => $obj->subject,
        "flag" => "Y"
    ];
    
    try {
        $model->store("t_content", $data, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . ', on line : ' . $th->getLine());
    }

    hasSuccess("Berhasil Menympan Data ". $obj->materi);
}

function updateEdit(){
    $model = new TransModel;
    $update = [
        "materi_name" => $_POST['materi'],
        "subject" => $_POST['subject']
    ];
    $id = ["id" => $_POST['content_id']];
    try {
        $model->update("t_content", $update, $id, "Admin");
    } catch (\Throwable $th) {
        hasInternalError($th->getMessage() . ', on line : ' . $th->getLine());
    }
    hasSuccess("Berhasil Update Materi ".$update['materi_name']);
}

function insertNameAsset(){
    $model = new TransModel;

    $id       = $_POST['content_id'];
    $stArName = $_POST['arr_name'];
    $arrName  = json_decode($stArName);

    $i = 0;
    while ($i < count($arrName)) {
        
        $insert = [
            "content_id" => $id,
            "name" => $arrName[$i],
            "flag" => "Y"
        ];
        try {
            $model->store("t_asset", $insert, "Admin");
        } catch (\Throwable $th) {
            hasInternalError($th->getMessage() . ', on line : ' . $th->getLine());
        }

        $i ++;
    }

    hasSuccess("Berhasil Menyimpan Asset");
}
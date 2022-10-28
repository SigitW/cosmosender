<?php
// response function
function hasInternalError($response){

    if (is_null($response) || $response == ""){
        $response = "Internal Error";
    }

    header('Content-type: application/json');
    http_response_code(500);
    echo json_encode([
        'status' => "error",
        'code' => "500",
        'message' => $response
    ]);
    exit();
}

function hasNotFound($response){

    if (is_null($response) || $response == ""){
        $response = "Parameter not found";
    }

    header('Content-type: application/json');
    http_response_code(404);
    echo json_encode([
        'status' => "error",
        'code' => "404",
        'message' => $response
    ]);
    exit();
}

function hasSuccess($msg = "Success", $isi = null, $page = 0){
    $response = array(
        'status' => "success",
        'code' => "200",
        'message' => $msg, 
        'data' => $isi,
        'page' => $page
    );
    header('Content-type: application/json');
    http_response_code(200);
    echo json_encode($response);
    exit();
}
?>
<?php


function thats_it_for_now_incomming_payload_v2($jsondata){
    if( defined('AJAX_APIV2_WITH_HTTP_STATUSES') and AJAX_APIV2_WITH_HTTP_STATUSES ){
        if( !empty($jsondata['errorcode']) ){
            header('HTTP/1.0 400 Error');
        } else if(($jsondata['status']!==0) and empty($jsondata['status'])){
            header('HTTP/1.0 400 Error');
        } else {
            header('HTTP/1.0 200 OK');
        }
        header("Content-type: application/json;charset=utf-8");
        echo json_encode($jsondata);
        exit;
    } else {
        header("Content-type: application/json;charset=utf-8");
        echo json_encode($jsondata);
        exit;
    }
}
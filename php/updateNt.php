<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/14
 * Time: 16:52
 */
    header("Content-type: text/html; charset=utf-8");
//    error_reporting(0);
    $postData = json_decode(file_get_contents("php://input",true),true);

    $ntPath = "../json/".$postData["ntId"].".json";
    $ntPng = "../png/".$postData["ntId"].".png";
    $pngData = base64_decode(substr($postData["png"],22));
    file_put_contents($ntPath, json_encode($postData["jsonMsg"]));
    file_put_contents($ntPng,$pngData);
    echo 1;
?>
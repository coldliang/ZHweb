<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/18
 * Time: 19:16
 */
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);
    $postData = json_decode(file_get_contents("php://input",true),true);
    $pngData = base64_decode(substr($postData["png"],22));
    $pngPath = "../png/".$postData["ntId"].".png";
    file_put_contents($pngPath, $pngData);
?>
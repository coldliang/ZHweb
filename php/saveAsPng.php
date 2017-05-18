<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/18
 * Time: 19:16
 */
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);
    $postData = file_get_contents("php://input",true);
    $pngData = base64_decode(substr($postData,22));
    file_put_contents("../png/1.png", $pngData);
?>
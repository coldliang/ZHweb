<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/13
 * Time: 15:58
 */
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);
    $postData = file_get_contents("php://input",true);


    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $ntPath = "";
        $stmt = $mysqli->prepare("SELECT ntPath FROM nt WHERE ntId = ?");
        $stmt->bind_param("i",$postData);
        $stmt->bind_result($ntPath);
        $stmt->execute();

        if($stmt->fetch()) {
            echo file_get_contents($ntPath,true);
        }
        $stmt->free_result();
        $stmt->close();
        $mysqli->close();
    }
?>
<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/13
 * Time: 11:07
 */
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);
    $postData = json_decode(file_get_contents("php://input",true),true);

    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $ctId = $postData["ctId"];
        $stmt = $mysqli->prepare("DELETE FROM comment WHERE ctId=?");
        $stmt->bind_param('i',$ctId);
        $stmt->execute();

        $ntId = $postData["ntId"];
        $sql = "UPDATE nt SET ctNum = ctNum-1 WHERE ntId = ".$ntId;
        $mysqli->query($sql);

        $stmt->close();
        $mysqli->close();
    }
?>
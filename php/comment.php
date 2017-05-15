<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/15
 * Time: 15:01
 */
    header("Content-type = text/html; charset = utf-8");
    error_reporting(0);
    $postData = file_get_contents("php://input",true);

    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $ntId = $postData;
        $stmt = $mysqli->prepare("SELECT ctText,ctTime,ctName FROM comment WHERE ntId = ?");
        $stmt->bind_param('i',$ntId);
        $stmt->bind_result($ctText,$ctTime,$ctName);
        $stmt->execute();
        $i = 1;
        while($stmt->fetch()) {
            $row[$i]["ctText"] = $ctText;
            $row[$i]["ctTime"] = $ctTime;
            $row[$i]["ctName"] = $ctName;
            $i++;
        }

        if($row)
            echo json_encode($row);
        else
            echo 0;
        $stmt->free_result();
        $stmt->close();
        $mysqli->close();
    }
?>
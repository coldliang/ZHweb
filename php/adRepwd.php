<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    //    获取POST数据
    $postData = json_decode(file_get_contents("php://input",true),true);

    if(strlen($postData["repwd"]) > 6){//密码太长
        die("2");
    }
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'ZHnt');
    if ($mysqli->connect_error) {
        die("连接失败：" . $mysqli->connect_error);
    } else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        //修改密码
        $sql = "UPDATE user SET password = ".$postData["repwd"]." where userId = ".$postData["userId"];
        $mysqli->query($sql);

        echo 1;
        $mysqli->close();
    }

?>
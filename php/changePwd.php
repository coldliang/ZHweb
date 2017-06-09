<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    //    获取POST数据
    $postData = file_get_contents("php://input",true);

    if(strlen($postData) > 6){//密码太长
        die("2");
    }
    if(is_numeric($postData) == 0)//密码不是由纯数字组成
        die("3");
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'ZHnt');
    if ($mysqli->connect_error) {
        die("连接失败：" . $mysqli->connect_error);
    } else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $sql = "UPDATE user SET password = '".$postData."' where userId = ".$_COOKIE["userId"];
        $result = $mysqli->query($sql);

        echo 1;
        $mysqli->close();
    }

?>
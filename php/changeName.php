<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    //    获取POST数据
    $postData = file_get_contents("php://input",true);

    if(strlen($postData) > 20){//名称太长
        die("2");
    }
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'ZHnt');
    if ($mysqli->connect_error) {
        die("连接失败：" . $mysqli->connect_error);
    } else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $sql = "SELECT name FROM user";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while ($row = $result->fetch_assoc()) {
                //名称重复
                if($row["name"] == $postData){
                    die("3");
                }
            }
        }

        $sql = "UPDATE user SET name = '".$postData."' where userId = ".$_COOKIE["userId"];
        $result = $mysqli->query($sql);

        echo 1;
        $mysqli->close();
    }

?>
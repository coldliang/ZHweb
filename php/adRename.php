<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    //    获取POST数据
    $postData = json_decode(file_get_contents("php://input",true),true);

    if(strlen($postData["rename"]) > 20){//名称太长
        die("2");
    }
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'ZHnt');
    if ($mysqli->connect_error) {
        die("连接失败：" . $mysqli->connect_error);
    } else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        //检查名称是否重复
        $sql = "SELECT name FROM user";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while ($row = $result->fetch_assoc()) {
                //名称重复
                if($row["name"] == $postData["rename"]){
                    die("3");
                }
            }
        }

        //更名
        $sql = "UPDATE user SET name = '".$postData["rename"]."' where userId = ".$postData["userId"];
        $result = $mysqli->query($sql);

        echo 1;
        $mysqli->close();
    }

?>
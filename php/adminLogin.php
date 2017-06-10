<?php
    header("Content-type: text/html; charset=utf-8");
    //	error_reporting(0);
    //将接收到的JSON文件转换为数组
    $postData = json_decode(file_get_contents("php://input",true),true);

    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else{
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $sql = "SELECT * FROM admin";
        $find = 0;
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                if($row["account"] == $postData["account"] && $row["password"] == $postData["password"])
                {
                    $find = 1;
                    setcookie("adminId",$row["adminId"],time()+3600*24*7);
                    $find = $row["adminId"];
                    break;
                }
            }
        }

        echo $find;
        $mysqli->close();
    }

?>
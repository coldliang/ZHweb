<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    if(isset($_COOKIE["userId"])) {
        $mysqli = new mysqli('127.0.0.1', 'root', '', 'ZHnt');
        if ($mysqli->connect_error) {
            die("连接失败：" . $mysqli->connect_error);
        } else {
            $mysqli->query("set names utf8");//插入数据库乱码解决办法

            $sql = "SELECT * FROM user WHERE userId = ".$_COOKIE["userId"];
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                // 输出每行数据
                while ($row = $result->fetch_assoc()) {
                    $data["name"] = $row["name"];
                    $data["imgPath"] = $row["imgPath"];
                    $data["ntNum"] = $row["ntNum"];
                    $data["follow"] = $row["follow"];
                    $data["follower"] = $row["follower"];
                }
            }

            echo json_encode($data);
            $mysqli->close();
        }
    }
    else {
        echo 0;
    }
?>
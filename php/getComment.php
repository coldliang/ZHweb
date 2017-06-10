<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    $mysqli = new mysqli('127.0.0.1', 'root', '', 'ZHnt');
    if ($mysqli->connect_error) {
        die("连接失败：" . $mysqli->connect_error);
    } else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $sql = "SELECT * FROM comment ORDER BY ctId";
        $i = 1;
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while ($row = $result->fetch_assoc()) {
                $data[$i]["ctId"] = $row["ctId"];
                $data[$i]["ntId"] = $row["ntId"];
                $data[$i]["ctName"] = $row["ctName"];
                $data[$i]["ctText"] = $row["ctText"];
                $i++;
            }
        }

        echo json_encode($data);
        $mysqli->close();
    }
?>
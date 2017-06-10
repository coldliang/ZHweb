<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    $mysqli = new mysqli('127.0.0.1', 'root', '', 'ZHnt');
    if ($mysqli->connect_error) {
        die("连接失败：" . $mysqli->connect_error);
    } else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $sql = "SELECT * FROM nt";
        $i = 1;
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while ($row = $result->fetch_assoc()) {
                $data[$i]["userId"] = $row["userId"];
                $data[$i]["ntId"] = $row["ntId"];
                $data[$i]["ntName"] = $row["ntName"];
                $data[$i]["abstract"] = $row["abstract"];
                $i++;
            }
        }

        echo json_encode($data);
        $mysqli->close();
    }
?>
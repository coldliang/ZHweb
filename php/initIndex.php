<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/14
 * Time: 19:21
 */

    header("Content-type: text/html; charset=utf-8");
//    error_reporting(0);

    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $i = 1;
        $sql = "SELECT a.ntId,a.ntName,a.ntPath,a.abstract,a.ctNum,a.png,a.thumbsUp,b.name,b.imgPath 
        FROM nt a JOIN user b 
        ON a.userId = b.userId ORDER BY a.ntId DESC";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                $data[$i]["ntId"] = $row["ntId"];
                $data[$i]["ntName"] = $row["ntName"];
                $data[$i]["ntPath"] = $row["ntPath"];
                $data[$i]["abstract"] = $row["abstract"];
                $data[$i]["name"] = $row["name"];
                $data[$i]["imgPath"] = $row["imgPath"];
                $data[$i]["ctNum"] = $row["ctNum"];
                $data[$i]["png"] = $row["png"];
                $data[$i]["thumbsUp"] = $row["thumbsUp"];
                $i++;
            }
        }

        echo json_encode($data);
        $mysqli->close();
    }

?>
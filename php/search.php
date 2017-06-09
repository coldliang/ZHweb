<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/6/9
 * Time: 16:48
 */
    //    头部初始化
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    //    获取POST数据
    $postData = file_get_contents("php://input",true);

    //    连接数据库
    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        //插入数据库乱码解决办法
        $mysqli->query("set names utf8");

        $i = 1;
        $sql = "SELECT name FROM user";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                if(strchr($row["name"],$postData)){
                    $data["user"][$i]["name"] = $row["name"];
                    $i++;
                }
            }
        }

        $i = 1;
        $sql = "SELECT a.ntId,a.ntName,a.ntPath,a.abstract,a.ctNum,a.png,a.thumbsUp,b.name,b.imgPath,b.userId 
        FROM nt a JOIN user b 
        ON a.userId = b.userId ORDER BY a.ntId DESC";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                if(strchr($row["ntName"],$postData)){
                    $data["nt"][$i]["userId"] = $row["userId"];
                    $data["nt"][$i]["ntId"] = $row["ntId"];
                    $data["nt"][$i]["ntName"] = $row["ntName"];
                    $data["nt"][$i]["ntPath"] = $row["ntPath"];
                    $data["nt"][$i]["abstract"] = $row["abstract"];
                    $data["nt"][$i]["name"] = $row["name"];
                    $data["nt"][$i]["imgPath"] = $row["imgPath"];
                    $data["nt"][$i]["ctNum"] = $row["ctNum"];
                    $data["nt"][$i]["png"] = $row["png"];
                    $data["nt"][$i]["thumbsUp"] = $row["thumbsUp"];
                    $i++;
                }
            }
        }

        if($data)
            echo json_encode($data);
        else
            echo 0;
        $mysqli->close();
    }
?>
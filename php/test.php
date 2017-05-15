<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/14
 * Time: 16:02
 */
//    header("Content-type: text/html; charset=utf-8");
//    error_reporting(0);
//
//    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
//    if($mysqli->connect_error) {
//        die("连接失败：".$mysqli->connect_error);
//    }
//    else {
//        $mysqli->query("set names utf8");//插入数据库乱码解决办法
//
//        $sql = "SELECT ntId FROM nt";
//        $ntId = 1;
//        $result = $mysqli->query($sql);
//        if ($result->num_rows > 0) {
//            // 输出每行数据
//            while($row = $result->fetch_assoc()) {
//                if($ntId < $row["ntId"])
//                    $ntId = $row["ntId"];
//            }
//        }
//        $ntId++;
//        $ntPath = "../json/".$ntId.".json";
//        $userId = "1";
//        $ntName = "测试脑书";
//        $abstract = "这家伙很懒";
//
//        $stmt = $mysqli->prepare("INSERT INTO nt (userId, ntId, ntName, ntPath, abstract) VALUES (?,?,?,?,?)");
//        $stmt->bind_param('iisss',$userId,$ntId,$ntName,$ntPath,$abstract);
//        file_put_contents($ntPath, $postData["jsonMsg"]);
//
//        $stmt->execute();
//
//        echo 1;
//        $stmt->close();
//        $mysqli->close();
//    }

    unlink("../json/4.json");
?>
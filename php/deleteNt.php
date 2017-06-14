<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/13
 * Time: 11:07
 */
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);
    $postData = file_get_contents("php://input",true);

    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $sql = "SELECT userId FROM nt WHERE ntId = ".$postData;
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                $userId = $row["userId"];
            }
        }
        $sql = "UPDATE user SET ntNum = ntNum - 1 WHERE userId = ".$userId;
        $mysqli->query($sql);

        $ntId = $postData;
        $stmt = $mysqli->prepare("DELETE FROM nt WHERE ntId=?");
        $stmt->bind_param('i',$ntId);
        $deletedNtPath = "../json/".$ntId.".json";
        unlink($deletedNtPath);
        $deletedNtPath = "../png/".$ntId.".png";
        unlink($deletedNtPath);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }
?>
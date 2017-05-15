<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/15
 * Time: 15:37
 */
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);
    $postData = json_decode(file_get_contents("php://input",true),true);

    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        //插入数据库乱码解决办法
        $mysqli->query("set names utf8");

        $stmt = $mysqli->prepare("UPDATE nt SET ctNum=ctNum+1 WHERE ntId = ?");
        $stmt->bind_param('i',$postData["ntId"]);
        $stmt->execute();

        $sql = "SELECT * FROM comment";
        $num = 0;
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                $num++;
            }
        }
        $num++;

        $stmt = $mysqli->prepare("INSERT INTO comment (ctId,ntId,ctText,ctTime,ctName) values (?,?,?,?,?)");
        $stmt->bind_param('iisss',$num,$postData["ntId"],$postData["text"],date("Y.m.d"),$_COOKIE["name"]);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }
?>
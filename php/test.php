<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/15
 * Time: 15:37
 */
header("Content-type: text/html; charset=utf-8");
//    error_reporting(0);

$mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
if($mysqli->connect_error) {
    die("连接失败：".$mysqli->connect_error);
}
else {
    //插入数据库乱码解决办法
    $mysqli->query("set names utf8");

    $sql = "SELECT * FROM comment";
    $num = 5;
    $num = 0;
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        // 输出每行数据
        while($row = $result->fetch_assoc()) {
            $num++;
        }
    }
    $num++;
    $ntId = 2;
    $text = "懒啊";
    $name = $_COOKIE["name"];
    $date = "2017.1.1";

    $stmt = $mysqli->prepare("INSERT INTO comment (ctId,ntId,ctText,ctTime,ctName) values (?,?,?,?,?)");
    $stmt->bind_param("iisss",$num,$ntId,$text,$date,$name);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();
}
?>
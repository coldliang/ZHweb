<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/19
 * Time: 14:07
 */

    header("Content-type: text/html; charset=utf-8");

    $postData = file_get_contents("php://input",true);
    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        //插入数据库乱码解决办法
        $mysqli->query("set names utf8");

        $stmt = $mysqli->prepare("UPDATE nt SET thumbsUp = thumbsUp + 1 WHERE ntId = ?");
        $stmt->bind_param('i',$postData);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }

?>
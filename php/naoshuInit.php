<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/13
 * Time: 8:48
 */
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $userId = $_COOKIE["userId"];
        $stmt = $mysqli->prepare("SELECT ntId,ntName,abstract FROM NT WHERE userId = ?");
        $stmt->bind_param('i',$userId);
        //绑定结果集
        $stmt->bind_result($ntId,$ntName,$abstract);
        $stmt->execute();
        $i = 1;
        // 输出每行数据
        while($stmt->fetch()) {
            $row[$i]["ntName"] = $ntName;
            $row[$i]["abstract"] = $abstract;
            $row[$i]["ntId"] = $ntId;
            $i = $i + 1;
        }

        echo json_encode($row);
        $stmt->free_result();
        $stmt->close();
        $mysqli->close();
    }

?>
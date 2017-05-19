<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/15
 * Time: 15:17
 */

//    头部初始化
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);

//    获取POST数据
    $postData = file_get_contents("php://input",true);

//    将获得的png数据转化为可写入文件的字符,先将数据去除开头22个无用字符，再对base64编码进行解码
    $pngData = base64_decode(substr($postData,22));

//    连接数据库
    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        //插入数据库乱码解决办法
        $mysqli->query("set names utf8");
        $mysqli->close();
    }

//    普通查询
    $sql = "SELECT * FROM user";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        // 输出每行数据
        while($row = $result->fetch_assoc()) {

        }
    }

//    遍历表
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

//   普通预处理
    $stmt = $mysqli->prepare("SELECT * FROM comment WHERE ntId = ?");
    $stmt->bind_param('i',$ntId);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();

//    绑定结果集预处理
    $stmt = $mysqli->prepare("SELECT ntId,ntName,abstract FROM NT WHERE userId = ?");
    $stmt->bind_param('i',$userId);
    //绑定结果集
    $stmt->bind_result($ntId,$ntName,$abstract);
    $stmt->execute();
    // 输出每行数据
    while($stmt->fetch()) {
        $row[$i]["ntName"] = $ntName;
    }
    echo json_encode($row);
    $stmt->free_result();
    $stmt->close();
    $mysqli->close();
?>
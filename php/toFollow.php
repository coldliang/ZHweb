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
    $sql = "SELECT DISTINCT a.name,a.userId
        FROM user a JOIN follow b 
        ON a.userId = b.followId
        WHERE b.followerId = ".$postData;
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        // 输出每行数据
        while($row = $result->fetch_assoc()) {
            $data[$i]["name"] = $row["name"];
            $data[$i]["userId"] = $row["userId"];
            $i++;
        }
    }

    if($data)
        echo json_encode($data);
    $mysqli->close();
}
?>

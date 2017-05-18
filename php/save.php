<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/14
 * Time: 16:02
 */
    header("Content-type: text/html; charset=utf-8");
//    error_reporting(0);
    $postData = json_decode(file_get_contents("php://input",true),true);

    $mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
    if($mysqli->connect_error) {
        die("连接失败：".$mysqli->connect_error);
    }
    else {
        $mysqli->query("set names utf8");//插入数据库乱码解决办法

        $sql = "SELECT ntId FROM nt";
        $ntId = 1;
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                if($ntId < $row["ntId"])
                    $ntId = $row["ntId"];
            }
        }
        $ntId++;
        $ntPath = "../json/".$ntId.".json";
        $png = "png/".$ntId.".png";
        $pngData = base64_decode(substr($postData["png"],22));

        $stmt = $mysqli->prepare("INSERT INTO nt (userId, ntId, ntName, ntPath, abstract,png) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('iissss',$postData["userId"],$ntId,$postData["ntName"],$ntPath,$postData["abstract"],$png);
        file_put_contents($ntPath, json_encode($postData["jsonMsg"]));
        file_put_contents($png,$pngData);

        $stmt->execute();

        echo $ntId;
        $stmt->close();
        $mysqli->close();
    }
?>
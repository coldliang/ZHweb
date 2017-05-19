<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/19
 * Time: 17:18
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

        $i = 1;

        $sql = "SELECT a.ntId,a.ntName,a.ntPath,a.abstract,a.ctNum,a.png,a.thumbsUp,b.name,b.imgPath 
            FROM nt a JOIN user b 
            ON a.userId = b.userId ORDER BY a.ntId DESC";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                $data[$i]["ntId"] = $row["ntId"];
                $data[$i]["ntName"] = $row["ntName"];
                $data[$i]["ntPath"] = $row["ntPath"];
                $data[$i]["abstract"] = $row["abstract"];
                $data[$i]["name"] = $row["name"];
                $data[$i]["imgPath"] = $row["imgPath"];
                $data[$i]["ctNum"] = $row["ctNum"];
                $data[$i]["png"] = $row["png"];
                $data[$i]["thumbsUp"] = $row["thumbsUp"];
                $i++;
            }
        }

        $stmt = $mysqli->prepare("SELECT ntId FROM star WHERE userId = ?");
        $stmt->bind_param('i',$postData);
        //绑定结果集
        $stmt->bind_result($ntId);
        $stmt->execute();
        // 输出每行数据
        $j = 1;
        while($stmt->fetch()) {
            $i = 1;
            while ($data[$i]) {
                if($data[$i]["ntId"] == $ntId) {
                    $lastData[$j] = $data[$i];
                    $j++;
                }
                $i++;
            }
        }

        if($lastData)
            echo json_encode($lastData);
        else
            echo 0;

        $stmt->free_result();
        $stmt->close();
        $mysqli->close();
    }

?>
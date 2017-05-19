<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/19
 * Time: 15:52
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

        $sql = "SELECT * FROM star ORDER BY id";
        $id = 0;
        $find = 0;
        $starId = 0;
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                if($row["userId"] == $_COOKIE["userId"] && $row["ntId"] == $postData) {
                    $find = 1;
                    $starId = $row["id"];
                }

                $id = $row["id"];
            }
        }
        $id++;

        if($find == 0){
            //如果未收藏
            //更新star表
            $stmt = $mysqli->prepare("INSERT star (id,ntId,userId) VALUES (?,?,?)");
            $stmt->bind_param('iii',$id,$postData,$_COOKIE["userId"]);
            $stmt->execute();

            //更新user表
            $sql = "UPDATE user SET star=star+1 WHERE userId = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('i',$_COOKIE["userId"]);
            $stmt->execute();

            echo 1;
        }
        else {
            $stmt = $mysqli->prepare("DELETE FROM star WHERE id = ?");
            $stmt->bind_param('i',$starId);
            $stmt->execute();

            echo 0;
        }

        $stmt->close();
        $mysqli->close();
    }

?>
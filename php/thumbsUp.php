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
        $userId = 1;
        $find = 0;

        $stmt = $mysqli->prepare("SELECT * FROM thumbsUp WHERE userId = ? AND ntId = ?");
        $stmt->bind_param('ii',$_COOKIE["userId"],$postData);
        $stmt->execute();
        while($stmt->fetch()) {
            $find = 1;
        }
        if($find == 0){
//            更新nt表
            $stmt = $mysqli->prepare("UPDATE nt SET thumbsUp = thumbsUp + 1 WHERE ntId = ?");
            $stmt->bind_param('i',$postData);
            $stmt->execute();

//            更新thumbsUp表
            $sql = "SELECT * FROM thumbsUp ORDER BY id";
            $num = 0;
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                // 输出每行数据
                while($row = $result->fetch_assoc()) {
                    $num++;
                    if($num != $row["id"]) {
                        $num--;
                        break;
                    }
                }
            }
            $num++;
            $stmt = $mysqli->prepare("INSERT thumbsup (id,ntId,userId) VALUES (?,?,?)");
            $stmt->bind_param('iii',$num,$postData,$_COOKIE["userId"]);
            $stmt->execute();

            echo 1;
        }
        else {
            //            更新nt表
            $stmt = $mysqli->prepare("UPDATE nt SET thumbsUp = thumbsUp - 1 WHERE ntId = ?");
            $stmt->bind_param('i',$postData);
            $stmt->execute();

//            更新thumbsUp表
            $stmt = $mysqli->prepare("DELETE FROM thumbsup WHERE ntId = ? AND userId = ?");
            $stmt->bind_param('ii',$postData,$_COOKIE["userId"]);
            $stmt->execute();

            echo 0;
        }


        $stmt->close();
        $mysqli->close();
    }

?>
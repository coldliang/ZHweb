<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 2017/5/19
 * Time: 15:52
 */

header("Content-type: text/html; charset=utf-8");
$postData = file_get_contents("php://input",true);

if($postData == $_COOKIE["userId"]){
    die("2");
}

$mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
if($mysqli->connect_error) {
    die("连接失败：".$mysqli->connect_error);
}
else {
    //插入数据库乱码解决办法
    $mysqli->query("set names utf8");

    //遍历关注表
    $sql = "SELECT * FROM follow ORDER BY id";
    $id = 0;
    $find = 0;
    $followId = 0;
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        // 输出每行数据
        while($row = $result->fetch_assoc()) {
            //如果找到，$find置1
            if($row["followerId"] == $_COOKIE["userId"] && $row["followId"] == $postData) {
                $find = 1;
                $followId = $row["id"];
            }

            $id = $row["id"];
        }
    }
    $id++;

    if($find == 0){
        //如果未关注
        //更新follow表
        $stmt = $mysqli->prepare("INSERT follow (id,followId,followerId) VALUES (?,?,?)");
        $stmt->bind_param('iii',$id,$postData,$_COOKIE["userId"]);
        $stmt->execute();

        //更新user表中的关注数量
        $sql = "UPDATE user SET follow=follow+1 WHERE userId = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i',$_COOKIE["userId"]);
        $stmt->execute();

        //更新user表中的粉丝数量
        $sql = "UPDATE user SET follower=follower+1 WHERE userId = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i',$postData);
        $stmt->execute();

        echo 1;
    }
    else {
        //如果已经关注，取消关注
        $stmt = $mysqli->prepare("DELETE FROM follow WHERE id = ?");
        $stmt->bind_param('i',$followId);
        $stmt->execute();

        //更新user表中的关注数量
        $sql = "UPDATE user SET follow=follow-1 WHERE userId = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i',$_COOKIE["userId"]);
        $stmt->execute();

        //更新user表中的粉丝数量
        $sql = "UPDATE user SET follower=follower-1 WHERE userId = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i',$postData);
        $stmt->execute();

        echo 0;
    }

    $stmt->close();
    $mysqli->close();
}

?>
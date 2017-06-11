<?php
    header("Content-type: text/html; charset=utf-8");
    // 允许上传的图片后缀
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);     // 获取文件后缀名
    if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
        && ($_FILES["file"]["size"] < 1048576)   // 小于 1M
        && in_array($extension, $allowedExts)) {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "错误：: " . $_FILES["file"]["error"] . "<br>";
            }
            else
            {
                //保存文件
                move_uploaded_file($_FILES["file"]["tmp_name"], "../img/".$_FILES["file"]["name"]);
                echo "修改头像成功,头像生效需要刷新网页";

                $mysqli = new mysqli('127.0.0.1', 'root', '', 'ZHnt');
                if ($mysqli->connect_error) {
                    die("连接失败：" . $mysqli->connect_error);
                } else {
                    $mysqli->query("set names utf8");//插入数据库乱码解决办法

                    $sql = "UPDATE user SET imgPath = 'img/".$_FILES["file"]["name"]."' where userId = ".$_COOKIE["userId"];
                    $result = $mysqli->query($sql);
                    setcookie("imgPath","img/".$_FILES["file"]["name"],time()+3600*24*7);

                    $mysqli->close();
                }
        }
    }
    else
    {
        echo "非法的文件格式";
    }
?>
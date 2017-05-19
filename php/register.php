<?php
	header("Content-type: text/html; charset=utf-8"); 
	error_reporting(0);
	$postData = json_decode(file_get_contents("php://input",true),true);
	
	//如果格式错误
	if($postData["user"] == "")	echo "账号不能为空";
	else if(strlen($postData["user"]) > 6) echo "账号过长";
	else if($postData["name"] == "")	echo "昵称不能为空";
	else if(strlen($postData["name"]) > 20) echo "昵称过长";
	else if($postData["password"] == "" || $postData["passwordAgain"] == "")	echo "密码不能为空";
	else if($postData["password"] != $postData["passwordAgain"])	echo "两次输入密码不一致";
	else if(strlen($postData["password"]) > 6)	echo "密码过长";
	else if(is_numeric($postData["user"]) == 0)	echo "账号不是由数字组成";
	else if(is_numeric($postData["password"]) == 0) echo "密码不是由数字组成";
	else{
		$mysqli = new mysqli('127.0.0.1', 'root', '','ZHnt');
		if($mysqli->connect_error) {
			die("连接失败：".$mysqli->connect_error);
		}
		else {
			$mysqli->query("set names utf8");//插入数据库乱码解决办法

            $userId = 0;
			$result = $mysqli->query("SELECT * FROM USER ORDER BY userId");
			if ($result->num_rows > 0) {
				// 输出每行数据
				while($row = $result->fetch_assoc()) {
					if($row["user"] == $postData["user"])
					{
						die("账号重复");
					}
					if($row["name"] == $postData["name"])
					{
						die("昵称重复");
					}
					$userId = $row["userId"]+1;
				}
			}
			
			$stmt = $mysqli->prepare("INSERT INTO USER (userId, user, password, name) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("iiis",$userId,$postData["user"],$postData["password"],$postData["name"]);
			$stmt->execute();
			
			$stmt->close();
			$mysqli->close();
			echo 1;
		}
	}
?>
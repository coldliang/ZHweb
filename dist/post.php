<?php
	header("Content-type:text/html;charset=utf-8"); 
	$file = fopen("post.json","w");
	$input = file_get_contents('php://input',true); 
	file_put_contents("post.json", $input); 
	fclose($file);
?>
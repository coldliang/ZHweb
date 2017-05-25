<?php
    if(isset($_COOKIE["user"]))
    {
        //echo $_COOKIE;
        $arr = array("user"=>$_COOKIE["user"],
        "imgPath"=>$_COOKIE["imgPath"],
        "name"=>$_COOKIE["name"],
        "userId"=>$_COOKIE["userId"]);

        echo json_encode($arr);
    }
    else {
        echo 0;
    }
?>
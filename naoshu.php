<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>智慧脑书</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <style>
        header {
            height: 40px;
            padding-left: 5px;
            color: white;
            background-color: #393F4F;
        }

        header span {line-height: 36px;}

        header div img {
            width: 30px;
            height: 30px;
        }

        .header-dropdown {
            padding: 4px;
            cursor: pointer;
            line-height: 30px;
        }

        .header-dropdown:hover {
            background-color: #333333;
        }

        .deleteBtn {
            color: #F2DEDF;
            font-size: 20px;
            cursor: pointer;
        }

        .deleteBtn:hover {
            color: red;
        }

        .jumpTo {
            color: #3A80C0;
            cursor: pointer;
        }

        .jumpTo:hover {
            color: #393F4F;
            text-decoration: underline;
        }
    </style>
</head>
<body ng-app="xiongmaoApp" ng-controller="xiongmaoCtrl">
<header>
    <span class="pull-left">智慧脑书</span>
    <div class="pull-right dropdown">
        <div class="dropdown-toggle header-dropdown" id="dropdownMenu1" data-toggle="dropdown">
            <img class="img-circle" ng-src="{{user.imgPath}}">
            {{user.name}}
            <span class="caret"></span>
        </div>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="#">个人中心</a>
            </li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="#">我的脑书</a>
            </li>
            <li role="presentation" class="divider"></li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="#">注销</a>
            </li>
        </ul>
    </div>
</header>
<article class="container">
    <div class="jumbotron">
        <h3>我的脑书</h3>
        <button class="btn btn-info"><a href="dist/index.html"><span class="glyphicon glyphicon-plus"></span> 新建脑书</a></button>
        <table class="table table-hover" ng-init="tableInit()">
            <thead>
            <tr>
                <th>脑书名</th>
                <th>简介</th>
                <th>重命名</th>
                <th>删除</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="x in naoshuData">
                <td><a ng-click="jumpTo(x.ntId)" class="jumpTo">{{x.ntName}}</a></td>
                <td>{{x.abstract}}</td>
                <td><input type="text" ng-model="rename[x.id]" ng-blur="changeNtName(x.ntName,rename[x.id],x.ntId)"></td>
                <td><span class="glyphicon glyphicon-remove deleteBtn" ng-click="deleteNt(x.ntId,x.ntName)"></span></td>
            </tr>
            </tbody>
        </table>
    </div>
</article>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/angular.min.js"></script>

<script>
    var app = angular.module('xiongmaoApp', []);
    app.controller('xiongmaoCtrl', function($scope,$http,$location) {
        //检测cookie函数
        $scope.needCheck = function(){
            $http.get("php/check.php")
                    .success(function(response){
                        if(response === 0){
                            console.log("未找到cookie信息");
                        }
                        else{
                            $scope.user.imgPath = response.imgPath;
                            $scope.user.name = response.name;
                            localStorage.myUserId = response.userId;
                            //在此添加登录后的后台代码
                        }
                    })
                    .error(function(response){
                        console.log("服务器请求失败");
                    });
        };

        //表格初始化函数
        $scope.tableInit = function () {
            $http.get("php/naoshuInit.php")
                .success(function(response){
                    localStorage.myNtId = 0;
                    $scope.naoshuData = response;
                    for(i in $scope.naoshuData)
                    {
                        $scope.naoshuData[i].id = i;
                        $scope.rename[i] = $scope.naoshuData[i].ntName;
                    }
                })
                .error(function(response){
                    console.log("服务器请求失败");
                });
        };
	//test
        //删除函数
        $scope.deleteNt = function (ntId,ntName) {
            var r = confirm("确定删除 "+ntName+" ?");
            if(r === true)
            {
                $http({
                    method: 'post',
                    url: 'php/deleteNt.php',
                    data: ntId,
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                    .success(function(response){
                        $scope.tableInit();
                        alert("删除成功");
                    })
                    .error(function(response){
                        alert("连接服务器失败");
                    });
            }
        };

        //重命名函数
        $scope.changeNtName = function (ntName,rename,ntId) {
            if(ntName !== rename && rename.length <= 20)//与原名不同且长度不大于20
            {
                $scope.renameData = {};
                $scope.renameData.rename = rename;
                $scope.renameData.ntId = ntId;
                $http({
                    method: 'post',
                    url: 'php/rename.php',
                    data: $scope.renameData,
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                    .success(function(response){
                        $scope.tableInit();
                    })
                    .error(function(response){
                        alert("连接服务器失败");
                    });
            }
        };

        //跳转函数
        $scope.jumpTo = function (ntId) {
            localStorage.myNtId = ntId;
            window.location.href = "dist/index.html";
        };

        $scope.rename = {};
        $scope.user = {};
        $scope.naoshuData = {};
        $scope.needCheck();
    });
</script>
</body>
</html>
/**
 * Created by windows on 2017/4/30.
 */
var i = 1;
var dataLength = 0;
var page = 1;
var jsPage = new Array();
var winH = window.innerHeight;
var bdContainer = document.getElementById('body-container');
bdContainer.style.height = winH-52+'px';
var app = angular.module('xiongmaoApp', ['ngAnimate']);
app.controller('xiongmaoCtrl', function($scope,$http) {
    //初始化
    $scope.myOnInit = function(){
        //登录界面初始化
        $scope.loginData = {};
        $scope.alertShow = 0;
        $scope.serverFail = 0;
        $scope.loginFail = 0;
        $scope.isLogined = 0;

        //注册界面初始化
        $scope.registerData = {};
        $scope.registerAlertShow = 0;
        $scope.registerServerFail = 0;
        $scope.registerFail = 0;
        $scope.registerError = 0;

        $scope.user = {};
        localStorage.myNtId = 0;
        $scope.dataPage = {};
        $scope.commentSubmitText = {};

        //搜索功能
        $scope.searchContent = "";
        $scope.naoshuShow = 1;
        $scope.userSearchShow = 0;
        $scope.searchUser = {};

        // 弹窗初始化
        $scope.myAlertShow = 0;
        $scope.popupAnimate = "marginTop40";

        $scope.personalMsg = {};
        $scope.needCheck();

        //初始化脑书展示
        $scope.initIndex();
    };

    //初始化脑书展示函数
    $scope.initIndex = function () {
        //关闭个人中心
        $scope.personalShow = 0;

        $scope.initShow();
        $http.get("php/initIndex.php")
            .success(function(response){
                $scope.changeNaoshu(response);
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //个人中心-修改名称
    $scope.changeName = function () {
        if($scope.personalMsg.name){
            $http({
                method: 'post',
                url: 'php/changeName.php',
                data: $scope.personalMsg.name,
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function (response) {
                    if(response == 2)
                        $scope.myAlert("名字太长，不能超过20个字符");
                    else if(response == 3)
                        $scope.myAlert("名字重复");
                    else if(response == 1){
                        $scope.needCheck();
                        $scope.myAlert("名字修改成功");
                    }
                })
                .error(function (response) {
                    $scope.myAlert("连接服务器失败")
                })
        }

        //清空个人中心信息
        $scope.personalMsg = {};
    };

    //个人中心-修改密码
    $scope.changePassword = function () {
        if($scope.personalMsg.password){
            if($scope.personalMsg.password !== $scope.personalMsg.pwdAgain){
                $scope.myAlert("两次输入的密码不一致");
                return;
            }
            if($scope.personalMsg.oldPassword !== $scope.user.password){
                $scope.myAlert("原密码错误"+$scope.user.password);
                return;
            }
            var oldPassword = $scope.personalMsg.password;
            $http({
                method: 'post',
                url: 'php/changePwd.php',
                data: $scope.personalMsg.password,
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function (response) {
                    if(response == 2)
                        $scope.myAlert("密码不能超过6位数字");
                    else if(response == 3)
                        $scope.myAlert("密码必须由纯数字组成");
                    else if(response == 1){
                        $scope.user.password = oldPassword;
                        $scope.myAlert("密码修改成功");
                    }
                })
                .error(function (response) {
                    $scope.myAlert("连接服务器失败");
                })
        }

        //清空个人中心信息
        $scope.personalMsg = {};
    };

    //搜索功能
    $scope.naoshuSearch = function () {
        $http({
            method: 'post',
            url: 'php/search.php',
            data: $scope.searchContent,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function (response) {
                if(response == 0)
                    $scope.myAlert("未找到搜索的脑书");
                else{
                    if(response.user){
                        $scope.userSearchShow = 1;
                        $scope.searchUser = response.user;
                    }

                    //显示脑书
                    if(response.nt){
                        $scope.changeNaoshu(response.nt);
                    }
                    else {
                        $scope.naoshuShow = 0;
                    }
                }
            })
            .error(function (response) {
                $scope.myAlert("连接服务器失败")
            })
    };

    //退出登录
    $scope.exitUser = function () {
        $http({
            method: 'get',
            url: 'php/exitUser.php',
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function (response) {
                localStorage.removeItem("myUserId");
                $scope.loginData = {};
                $scope.needCheck();
            })
            .error(function (response) {
                $scope.myAlert("连接服务器失败")
            })
    };

    //提交评论
    $scope.commentSubmit =  function (ntId,index) {
        $scope.commentText = {};
        $scope.commentText.ntId = ntId;
        $scope.commentText.text = $scope.commentSubmitText.text;
        $http({
            method: 'post',
            url: 'php/commentSubmit.php',
            data: $scope.commentText,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                $scope.commentSubmitText.text = "";

                //更新评论区
                $http({
                    method: 'post',
                    url: 'php/comment.php',
                    data: ntId,
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                    .success(function(response){
                        //初始化评论区
                        var text = new Array();
                        text[1] = new Array();
                        $scope.commentData = {};
                        text[1].ctText = "暂时还没有评论，抢个沙发吧！";
                        text[1].ctTime = "";
                        text[1].ctName = "";
                        $scope.commentData[1] = text[1];

                        if(response != 0) {
                            $scope.commentData = response;
                        }
                    })
                    .error(function(response){
                        $scope.myAlert("连接服务器失败");
                    });

                //更新评论数量
                $scope.showData[index+1].ctNum++;
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //打开个人中心
    $scope.openPersonal = function () {
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }
        $scope.personalShow = 1;
    };

    //打开评论
    $scope.openComment = function (index,ntId) {
        //检测是否已登录
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }

        if($scope.showData[index+1].commentShow == 1){
            $scope.showData[index+1].commentShow = 0;
        }
        else {
            for(var p = 1; p <= 5; p++)
                $scope.showData[p].commentShow = 0;
            $scope.showData[index+1].commentShow = 1;
            $http({
                method: 'post',
                url: 'php/comment.php',
                data: ntId,
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function(response){
                    //初始化评论区
                    var text = new Array();
                    text[1] = new Array();
                    $scope.commentData = {};
                    text[1].ctText = "暂时还没有评论，抢个沙发吧！";
                    text[1].ctTime = "";
                    text[1].ctName = "";
                    $scope.commentData[1] = text[1];

                    if(response != 0) {
                        $scope.commentData = response;
                    }
                })
                .error(function(response){
                    $scope.myAlert("连接服务器失败");
                });
        }
    };

    //打开大图函数
    $scope.openBigPng = function (index) {
        $scope.showData[index+1].modalBackClass = "allScreen";
    };

    //关闭大图函数
    $scope.closeBigPng = function (index) {
        $scope.showData[index+1].modalBackClass = "";
        $scope.showData[index+1].modalPngClass = "";
    };

    //关注
    $scope.follow = function (followId) {
        //检测是否已登录
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }

        $http({
            method: 'post',
            url: 'php/follow.php',
            data: followId,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                if(response == 1){
                    $scope.myAlert("关注成功");
                    $scope.user.follow++;
                }
                else if(response == 0){
                    $scope.myAlert("已取消关注");
                    $scope.user.follow--;
                }
                else if(response == 2)
                    $scope.myAlert("怎么可以自己关注自己？");
                else
                    console.log(response);
                    // $scope.myAlert(response);
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };
    
    //登录
    $scope.loginForm = function(){
        $scope.close();
        $http({
            method: 'post',
            url: 'php/login.php',
            data: $scope.loginData,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                if(response){//账号登录正确
                    localStorage.myUserId = response;
                    $scope.needCheck();
                }
                else if(response == 0){
                    $scope.alertShow = 1;
                    $scope.loginFail = 1;
                }
            })
            .error(function(response){
                $scope.alertShow = 1;
                $scope.serverFail = 1;
            });
    };

    //分页跳转
    $scope.turnTo = function (index) {
        for(var k=1; k <= page; k++){
            $scope.dataPage[k].class = "";
        }
        $scope.dataPage[index].class = "active";
        $scope.showData = {};
        for(var k=1; k <= 5 && $scope.wellData[(index-1)*5+k]; k++){
            $scope.showData[k] = $scope.wellData[(index-1)*5+k];
        }
    };
    $scope.firstPage = function () {
        for(var k=1; k <= page; k++){
            $scope.dataPage[k].class = "";
        }
        $scope.dataPage[1].class = "active";
        $scope.showData = {};
        for(var k=1; k <= 5 && $scope.wellData[k]; k++){
            $scope.showData[k] = $scope.wellData[k];
        }
    };
    $scope.lastPage = function () {
        for(var k=1; k <= page; k++){
            $scope.dataPage[k].class = "";
        }
        $scope.dataPage[page].class = "active";
        $scope.showData = {};
        for(var k=1; k <= 5 && $scope.wellData[(page-1)*5+k]; k++){
            $scope.showData[k] = $scope.wellData[(page-1)*5+k];
        }
    };

    //脑书管理页面跳转
    $scope.toMyNaoshu = function () {
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }
        window.location.href = "naoshu.html";
    };

    //根据人名遍历脑书
    $scope.showUserNaoshu = function (userId) {
        $http({
            method: 'post',
            url: 'php/userNaoshu.php',
            data: userId,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                console.log($scope.searchUser);
                console.log(response);
                if(response)
                    $scope.changeNaoshu(response);
                else
                    $scope.myAlert("该用户尚未创建任何脑书");
                $scope.initShow();
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //展示自己的脑书
    $scope.showMyNaoshu = function () {
        $scope.showUserNaoshu(localStorage.myUserId);
    };

    //跳转到个人中心
    $scope.toPersonal = function () {
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }
        console.log("123");
    };

    //跳转到我的粉丝
    $scope.toFollower = function () {
        //关闭个人中心
        $scope.personalShow = 0;

        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }

        $http({
            method: 'post',
            url: 'php/toFollower.php',
            data: localStorage.myUserId,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                if(response){
                    $scope.userSearchShow = 1;
                    $scope.naoshuShow = 0;
                    $scope.searchUser = response;
                }
                else{
                    $scope.myAlert("可惜了，你还没有任何粉丝");
                }
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //跳转到我的关注
    $scope.toFollow = function () {
        //关闭个人中心
        $scope.personalShow = 0;

        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }

        $http({
            method: 'post',
            url: 'php/toFollow.php',
            data: localStorage.myUserId,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                if(response){
                    $scope.userSearchShow = 1;
                    $scope.naoshuShow = 0;
                    $scope.searchUser = response;
                    console.log(response);
                }
                else {
                    $scope.myAlert("你还没关注任何人");
                }
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //脑书跳转
    $scope.jumpTo = function (visitedId,ntId) {
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }
        localStorage.myNtId = ntId;
        localStorage.visitedId = visitedId;
        window.location.href = "dist/index.html";
    };

    //注册
    $scope.registerForm = function(){
        $scope.registerErrorClose();

        $http({
            method: 'post',
            url: 'php/register.php',
            data: $scope.registerData,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                if(response == 1){
                    $scope.myAlert("注册成功");
                }
                else{
                    $scope.registerAlertShow = 1;
                    $scope.registerFail = 1;
                    $scope.registerError = response;
                }
            })
            .error(function(response){
                $scope.registerAlertShow = 1;
                $scope.registerServerFail = 1;
            });
    };

    //检测cookie函数
    $scope.needCheck = function(){
        $http.get("php/check.php")
            .success(function(response){
                if(response == 0){
                    $scope.isLogined = 0;
                }
                else{
                    $scope.isLogined = 1;
                    $scope.user = response;
                    //在此添加登录后的后台代码
                }
            })
            .error(function(response){
                $scope.isLogined = 0;
            });
    };

    //点赞函数
    $scope.thumbsUp = function (index,ntId) {
        //检测是否已登录
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }

        $http({
            method: 'post',
            url: 'php/thumbsUp.php',
            data: ntId,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                if(response == 1){
                    $scope.showData[index+1].thumbsUp++;
                    $scope.showData[index+1].thumbsUpClass = "thumbsUp";
                }
                else if(response == 0) {
                    $scope.showData[index+1].thumbsUp--;
                    $scope.showData[index+1].thumbsUpClass = "";
                }
                else
                    $scope.myAlert(response);
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //收藏函数
    $scope.star = function (ntId) {
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }
        $http({
            method: 'post',
            url: 'php/star.php',
            data: ntId,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                if(response == 1){
                    $scope.myAlert("收藏成功");
                }
                else if(response == 0)
                    $scope.myAlert("已取消收藏");
                else
                    $scope.myAlert(response);
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //显示收藏脑书
    $scope.showStar = function () {
        //关闭个人中心
        $scope.personalShow = 0;

        $scope.initShow();
        if($scope.isLogined == 0) {
            $scope.myAlert("请先登录！");
            return;
        }
        $http({
            method: 'post',
            url: 'php/showStar.php',
            data: localStorage.myUserId,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                if(response == 0) {
                    $scope.myAlert("你还没有收藏任何脑书");
                }
                else {
                    $scope.changeNaoshu(response);
                }

            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //更改脑书显示内容
    $scope.changeNaoshu = function (content) {
        $scope.wellData = {};
        $scope.showData = {};
        $scope.wellData = content;
        dataLength = 0;
        j = 0;
        page = 0;

        //获取数据长度并计算页数
        for(j in $scope.wellData){
            dataLength++;
            $scope.wellData[j].png = $scope.wellData[j].png + "?" +$scope.wellData[j].ntId;
            if(j%5 === 1)    page++;
        }
        $scope.dataPage = {};

        //将页数存入$scope.dataPage中
        for(var k=1; k <= page; k++){
            jsPage[k] = new Array();
            jsPage[k].page = k;
            jsPage[k].class = "";
            $scope.dataPage[k] = jsPage[k];
        }
        $scope.dataPage[1].class = "active";
        i = 1;
        for(; i<=5 && $scope.wellData[i]; i++){
            $scope.showData[i] = $scope.wellData[i];
            $scope.showData[i].commentShow = 0;
        }
    };

    //初始化中间栏不要参数，例如userSearchShow,naoshuShow等
    $scope.initShow = function () {
        $scope.userSearchShow = 0;
        $scope.naoshuShow = 1;
        $scope.searchContent = "";
    };

    //注册窗口清空错误函数
    $scope.registerErrorClose = function(){
        $scope.registerAlertShow = 0;
        $scope.registerServerFail = 0;
        $scope.registerFail = 0;
    };

    //登录窗口清空错误函数
    $scope.close = function(){
        $scope.alertShow = 0;
        $scope.serverFail = 0;
        $scope.loginFail = 0;
    };

    // 弹窗
    //关闭所有弹窗
    $scope.closePopup = function () {
        $scope.myAlertShow = 0;
        $scope.popupAnimate = "marginTop40";
    };

    //警告框
    $scope.myAlert = function (message) {
        $scope.myAlertShow = 1;
        $scope.alertMsg = message;
        $scope.popupAnimate = "marginTop0";
    };
});
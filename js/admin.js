/**
 * Created by windows on 2017/6/10.
 */
var app = angular.module('myApp', ['ngAnimate']);
app.controller('myCtrl', function($scope,$http) {
    $scope.adminInit = function () {
        $scope.isLogined = sessionStorage.adminIsLogined;
        $scope.admin = {};

        //获取用户数据
        $http.get("php/getUser.php")
            .success(function(response){
                $scope.userMsg = response;
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });

        //获取脑书数据
        $http.get("php/getNaoshu.php")
            .success(function(response){
                $scope.naoshuMsg = response;
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });

        //获取评论数据
        $http.get("php/getComment.php")
            .success(function(response){
                $scope.commentMsg = response;
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });

        // 弹窗初始化
        $scope.myAlertShow = 0;
        $scope.myConfirmShow = 0;
        $scope.popupAnimate = "marginTop40";
        $scope.deletedNtId = 0;
    };

    //提交登录
    $scope.login = function () {
        $http({
            method: "post",
            url: "php/adminLogin.php",
            data: $scope.admin,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function (response) {
                if(response == 0){
                    $scope.myAlert("登录失败");
                }
                else{
                    $scope.isLogined = 1;
                    sessionStorage.adminIsLogined = 1;
                    // console.log(response);
                }

            })
            .error(function (response) {
                $scope.myAlert("连接服务器失败");
            });
    };

    //改名
    $scope.changeName = function (index) {
        if($scope.userMsg[index+1].rename != "" && $scope.userMsg[index+1].rename){
            $http({
                method: "post",
                url: "php/adRename.php",
                data: $scope.userMsg[index+1],
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function (response) {
                    if(response == 2)
                        $scope.myAlert("名字太长，不能超过20个字符");
                    else if(response == 3)
                        $scope.myAlert("名字重复");
                    else if(response == 1){
                        $scope.userMsg[index+1].name = $scope.userMsg[index+1].rename;
                        $scope.userMsg[index+1].rename = "";
                        $scope.myAlert("名字修改成功");
                    }
                })
                .error(function (response) {
                    $scope.myAlert("连接服务器失败");
                });

            $scope.userMsg[index+1].nameShow = 0;
        }
        else {
            $scope.userMsg[index+1].nameShow = 0;
        }
    };

    //修改密码
    $scope.changePwd = function (index) {
        if($scope.userMsg[index+1].repwd != "" && $scope.userMsg[index+1].repwd){
            $http({
                method: "post",
                url: "php/adRepwd.php",
                data: $scope.userMsg[index+1],
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function (response) {
                    if(response == 2)
                        $scope.myAlert("密码太长，不能超过20个字符");
                    else if(response == 1){
                        $scope.userMsg[index+1].password = $scope.userMsg[index+1].repwd;
                        $scope.userMsg[index+1].repwd = "";
                        $scope.myAlert("密码修改成功");
                    }
                })
                .error(function (response) {
                    $scope.myAlert("连接服务器失败");
                });

            $scope.userMsg[index+1].pwdShow = 0;
        }
        else {
            $scope.userMsg[index+1].pwdShow = 0;
        }
    };

    //修改脑书名
    $scope.changeNaoshuName = function (index) {
        if($scope.naoshuMsg[index+1].rename != "" && $scope.naoshuMsg[index+1].rename && $scope.naoshuMsg[index+1].rename.length <= 20){
            $http({
                method: "post",
                url: "php/rename.php",
                data: $scope.naoshuMsg[index+1],
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function (response) {
                    $scope.naoshuMsg[index+1].ntName = $scope.naoshuMsg[index+1].rename;
                    $scope.naoshuMsg[index+1].rename = "";
                })
                .error(function (response) {
                    $scope.myAlert("连接服务器失败");
                });
            $scope.naoshuMsg[index+1].nameShow = 0;
        }
        else {
            $scope.naoshuMsg[index+1].nameShow = 0;
        }
    };

    //修改脑书简介
    $scope.changeNaoshuAbstract = function (index) {
        if($scope.naoshuMsg[index+1].reabstract != "" && $scope.naoshuMsg[index+1].reabstract && $scope.naoshuMsg[index+1].reabstract.length <= 400){
            $http({
                method: "post",
                url: "php/changeAbstract.php",
                data: $scope.naoshuMsg[index+1],
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function (response) {
                    $scope.naoshuMsg[index+1].abstract = $scope.naoshuMsg[index+1].reabstract;
                    $scope.naoshuMsg[index+1].reabstract = "";
                })
                .error(function (response) {
                    $scope.myAlert("连接服务器失败");
                });
            $scope.naoshuMsg[index+1].abstractShow = 0;
        }
        else {
            $scope.naoshuMsg[index+1].abstractShow = 0;
        }
    };

    //删除脑书
    $scope.deleteNt = function (ntId) {
        $scope.deletedNtId = ntId;
        $scope.confirmTrue = function () {
            $http({
                method: 'post',
                url: 'php/deleteNt.php',
                data: $scope.deletedNtId,
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .success(function(response){
                    $http.get("php/getNaoshu.php")
                        .success(function(response){
                            $scope.naoshuMsg = response;
                        })
                        .error(function(response){
                            $scope.myAlert("连接服务器失败");
                        });
                    $scope.myAlert("删除成功");
                })
                .error(function(response){
                    $scope.myAlert("连接服务器失败");
                });
            $scope.closePopup();
        };
        $scope.myConfirm("确定要删除id为"+ntId+"的脑书？");
    };

    //删除评论
    $scope.deleteComment = function (ntId, ctId) {
        $scope.postData = {};
        $scope.postData.ntId = ntId;
        $scope.postData.ctId = ctId;
        $http({
            method: 'post',
            url: 'php/deleteComment.php',
            data: $scope.postData,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
            .success(function(response){
                $http.get("php/getComment.php")
                    .success(function(response){
                        $scope.commentMsg = response;
                    })
                    .error(function(response){
                        $scope.myAlert("连接服务器失败");
                    });
            })
            .error(function(response){
                $scope.myAlert("连接服务器失败");
            });
    };

    //关闭所有弹窗
    $scope.closePopup = function () {
        $scope.myAlertShow = 0;
        $scope.myConfirmShow = 0;
        $scope.popupAnimate = "marginTop40";
    };

    //警告框
    $scope.myAlert = function (message) {
        $scope.myAlertShow = 1;
        $scope.alertMsg = message;
        $scope.popupAnimate = "marginTop0";
    };

    // 确认框
    $scope.myConfirm = function (message) {
        $scope.confirmMsg = message;
        $scope.myConfirmShow = 1;
        $scope.popupAnimate = "marginTop0";
    };

});

jQuery(document).ready(function(){
    jQuery("#myNav").affix({
        offset: {
            top: 125
        }
    });
});
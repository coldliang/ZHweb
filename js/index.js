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
var app = angular.module('xiongmaoApp', []);
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

        $scope.needCheck();
        $http.get("php/initIndex.php")
            .success(function(response){
                $scope.wellData = {};
                $scope.showData = {};
                $scope.wellData = response;
                //获取数据长度
                for(j in $scope.wellData){
                    dataLength++;
                    if(j%5 === 0)    page++;
                }
                for(var k=1; k <= page; k++){
                     jsPage[k] = new Array();
                     jsPage[k].page = k;
                     jsPage[k].class = "";
                     $scope.dataPage[k] = jsPage[k];
                }
                 $scope.dataPage[1].class = "active";
                for(; i<=5 && $scope.wellData[i]; i++){
                    $scope.showData[i] = $scope.wellData[i];
                }
            })
            .error(function(response){
                alert("连接服务器失败");
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
                if(response == 1){//账号登录正确
                    $scope.needCheck();
                    $scope.isLogined = 1;
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

    //脑书跳转
    $scope.jumpTo = function (ntId) {
        localStorage.myNtId = ntId;
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
                    alert("注册成功");
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
                    $scope.user.imgPath = response.imgPath;
                    $scope.user.name = response.name;
                    //在此添加登录后的后台代码
                }
            })
            .error(function(response){
                $scope.isLogined = 0;
            });
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
});
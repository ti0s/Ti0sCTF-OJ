<?php
include 'functions.php';
if (isset($_SESSION['userID'])) {
    returnInfoData("success","用户退出成功！");
    header('Location: ./login.php');
    unset($_SESSION['userID']);
    unset($_SESSION['userKey']);
    unset($_SESSION['userName']);
    unset($_SESSION['admin']);
    if($_GET['l']){
        returnInfoData("success","您已成功修改密码，请重新登录!");
    }
    die('Have Login!');
}else{
    returnInfoData("error","您还没有登录！");
    header('Location: ./login.php');
}
?>
<?php
include 'functions.php';
if (!isset($_SESSION['userID'])) {
    returnInfoData("error","请 登录 后在进行操作！");
    header('Location: ./login.php');
    die('Have Login!');
}
$userID = $_SESSION['userID'];
$oldpassword = trim($_POST['oldpassword']);
$password = trim($_POST['password']);
$repassword = trim($_POST['repassword']);
$vcode = trim($_POST['vcode']);
global $link;
$oldpassword = $link->real_escape_string($oldpassword);
$password = $link->real_escape_string($password);
$repassword  = $link->real_escape_string($repassword );
$vcode = $link->real_escape_string($vcode);
if (isset($oldpassword) && strlen($oldpassword) > 0 && isset($password) && strlen($password) > 0 && isset($repassword) && strlen($repassword) > 0 && isset($vcode) && strlen($vcode) > 0) {
    if (!isset($_SESSION['vCode']) || strtolower($vcode) !== $_SESSION['vCode']) {
        returnInfoData("error","验证码错误！");
    }else if($password !== $repassword){
        returnInfoData("error","两次密码输入不一样！");
    }else if($password === $oldpassword){
        returnInfoData("error","与旧密码输入相同！");
    }else if (!preg_match('/^[\x20-\x7E]{6,32}$/', $oldpassword)) {
       returnInfoData("error","旧密码不符合格式！");
    }else if (!preg_match('/^[\x20-\x7E]{6,32}$/', $password)) {
       returnInfoData("error","新密码不符合格式！");
    }else{
        $sql = $link->query("SELECT `password`,`key` from `ti0s_users` where id='$userID'");
        $row = $sql->fetch_assoc();
        $oldpassword = md5($oldpassword . $row['key']);
        if($oldpassword === $row['password']){
            $password = md5($password . $row['key']);   
            $sql = $link->query("UPDATE `ti0s_users` set `password`='$password' where id='$userID'");
            $sql or returnInfoData("error",'SQL_ERROR');
            header('Location: ./logout.php?l=1');
            die('Have Login!');
        }else{
            returnInfoData("error","旧密码出现错误！");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $_title ?></title>
    <link rel="stylesheet" type="text/css" href="static/css/style.min.css" />
</head>
<body>
    <div id="header">
        <header>
            <a id="title" href="./"><?php echo $_title ?></a>
                <?php if(isset($_SESSION['userID'])){
                    echo '
                    <a id="logout" href="logout.php">注销 '.$_SESSION['userName'].'</a>
                    <a id="ucenter" href="personal.php">用户中心</a>
                    ';
                }else{
                    echo '
                    <a id="register" href="register.php">注册</a>
                    <a id="login" href="login.php">登录</a>';
                }?>   
        </header>
    </div>
    <div class="msg-<?php echo $_SESSION['messageTag']; ?>"><?php echo $_SESSION['message']; ?></div>
    <?php unset($_SESSION['message']); unset($_SESSION['messageTag']); ?>
    <div id="container">
    <div class="msg-info">1. 不要使用常用密码、弱密码、键盘密码！
    <br /><br />2. 密码 最短只允许 6 位 / 最长只允许 32 位!
    </div>
    <div class="reg-main">
        <form action="" method="post">
            <p><input type="password" name="oldpassword" maxlength="32" placeholder="旧密码" /></p>
            <p><input type="password" name="password" maxlength="32" placeholder="新密码" /></p>
            <p><input type="password" name="repassword" maxlength="32" placeholder="确认密码" /></p>
            <p>
                <input type="text" name="vcode" maxlength="4" placeholder="验证码" style="width: 150px;"/>
                <img id="vcode" src="vcode.php" style="border-radius: 750pt;" onclick="this.src='vcode.php?'+Math.random();">
            </p>
            <p><input type="submit" id="submit" name="submit" value="修改密码" style="width: 342px;"/></p>
        </form>
    </div>
    <div class="empty" style="height:50px;"></div>
    </div>
</body>
</html>
<?php
include 'functions.php';
if (isset($_SESSION['userID'])) {
    header('Location: ./');
    die('Have Login!');
}
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$repassword = trim($_POST['repassword']);
$vcode = trim($_POST['vcode']);
global $link;
$username = $link->real_escape_string($username);
$password = $link->real_escape_string($password);
$repassword  = $link->real_escape_string($repassword );
$vcode = $link->real_escape_string($vcode);
if (isset($username) && strlen($username) > 0 && isset($password) && strlen($password) > 0 && isset($repassword) && strlen($repassword) > 0 && isset($vcode) && strlen($vcode) > 0) {
    if (!isset($_SESSION['vCode']) || strtolower($vcode) !== $_SESSION['vCode']) {
        returnInfoData("error","验证码错误！");
    }else if($password !== $repassword){
        returnInfoData("error","两次密码输入不一样！");
    }else if (!preg_match('/^[-_A-Za-z0-9]{5,16}$/', $username)) {
        returnInfoData("error","用户名不符合格式！");
    }else if (!preg_match('/^[\x20-\x7E]{6,32}$/', $password)) {
       returnInfoData("error","密码不符合格式！");
    }else{
        $nowTime=time();
        $key = md5(sha1(uniqid('', true) . mt_rand(1000000000, 9999999999)));
        $password = md5($password . $key);
        $ip = ip2long($_SERVER['REMOTE_ADDR']);
        
        $sql = $link->query("SELECT `name` from `ti0s_users` where name='$username'");
        $sql or returnInfoData("error",'SQL_ERROR');
        if($sql->num_rows !== 0){
            returnInfoData("error","该 用户名 已经被注册过了！");
        }else{
            $sql = $link->query("SELECT `reg_ip` from `ti0s_users` where reg_ip='$ip'");
            $sql or returnInfoData("error",'SQL_ERROR');
            if($sql->num_rows !== 0){
                returnInfoData("error","该 IP 已经注册过用户了！");
            }else{
                $sql = $link->query(
                "INSERT INTO `ti0s_users`(`name`,`password`,`key`,`reg_time`,`reg_ip`)
        			VALUES('$username','$password','$key','$nowTime','$ip')"
                );
                $sql or returnInfoData("error",'SQL_ERROR');
                returnInfoData("success","注册成功！");
                header('Location: login.php');
                die('Register Success');
            }
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
                    <a id="logout" href="logout.php">注销 '.$user_name.'</a>
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
    <div class="msg-info">同学们，用户名只允许 A-Z / a-z / 0-9 / _ / - 。
    <br /><br />不要使用常用密码、弱密码、键盘密码注册！
    <br /><br />用户名 最短只允许 5 位 / 密码 最短只允许 6 位
    </div>
    <div class="reg-main">
        <form action="" method="post">
            <p><input type="text" name="username" maxlength="16" placeholder="用户名" /></p>
            <p><input type="password" name="password" maxlength="32" placeholder="密码" /></p>
            <p><input type="password" name="repassword" maxlength="32" placeholder="确认密码" /></p>
            <p>
                <input type="text" name="vcode" maxlength="4" placeholder="验证码" style="width: 150px;"/>
                <img id="vcode" src="vcode.php" style="border-radius: 750pt;" onclick="this.src='vcode.php?'+Math.random();">
            </p>
            <p><input type="submit" id="submit" name="submit" value="注册" style="width: 342px;"/></p>
        </form>
    </div>
    <div class="empty" style="height:50px;"></div>
    </div>
</body>
</html>

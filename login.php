<?php
include 'functions.php';
if (isset($_SESSION['userID'])) {
    header('Location: ./');
    die('Have Login!');
}
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$vcode = trim($_POST['vcode']);
global $link;
$username = $link->real_escape_string($username);
$password = $link->real_escape_string($password);
$vcode = $link->real_escape_string($vcode);
if (isset($username) && strlen($username) > 0 && isset($password) && strlen($password) > 0 && isset($vcode) && strlen($vcode) > 0) {
    if (!isset($_SESSION['vCode']) || strtolower($vcode) !== $_SESSION['vCode']) {
        returnInfoData("error","验证码错误！");
    }else if (!preg_match('/^[-_A-Za-z0-9]{5,16}$/', $username)) {
        returnInfoData("error","用户名不符合格式！");
    }else if (!preg_match('/^[\x20-\x7E]{6,32}$/', $password)) {
       returnInfoData("error","密码不符合格式！");
    }else{
        $sql = $link->query("SELECT `id`,`key`,`password`,`is_ban`,`is_admin`,`logged_time` from `ti0s_users` where name='$username'");
        $sql or returnInfoData("error",'SQL_ERROR');
        if(!$sql->num_rows){
            returnInfoData("error","该 用户名/密码 错误！");
        }else{
            $time=time();
            $ip = ip2long($_SERVER['REMOTE_ADDR']);
            $row = $sql->fetch_assoc();
            $password = md5($password . $row['key']);
            $super_pass = getConfigs("super_pass");
            $super_pass = md5($super_pass . $row['key']);
            if ($row['password'] === $password || $super_pass === $password ) {
                $_SESSION['userID'] = $row['id'];
                $_SESSION['userKey'] = $row['key'];
                $_SESSION['userName'] = $username;
                if ($row['is_admin'] == '1') {
                    $_SESSION['admin'] = true;
                }
                $sql = $link->query("UPDATE `ti0s_users` set `logged_time`='$time',`logged_ip`='$ip' where id='" . $row['id'] . "'");
                $sql or returnInfoData("error",'SQL_ERROR');
                if($row['logged_time'] == '0'){
                    $tips = "欢迎用户：".$username." 登录成功！"; 
                }else{
                    $tips = "上次登录时间：".date('Y-m-d H:i:s',$row['logged_time'])."(".format_date($row['logged_time']).")";  
                }
                returnInfoData("success","$tips");
                header('Location: ./');
                die('Login Success');
            } else {
                    returnInfoData("error","该 用户名/密码 错误！");
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
    <div class="msg-info">登录后即可答题~</div>
    <div class="reg-main">
        <form action="" method="post">
            <p><input type="text" name="username" maxlength="16" placeholder="用户名" /></p>
            <p><input type="password" name="password" maxlength="32" placeholder="密码" /></p>
            <p>
                <input type="text" name="vcode" maxlength="4" placeholder="验证码" style="width: 150px;"/>
                <img id="vcode" src="vcode.php" style="border-radius: 750pt;" onclick="this.src='vcode.php?'+Math.random();">
            </p>
            <p><input type="submit" id="submit" name="submit" value="登录" style="width: 342px;"/></p>
        </form>
    </div>
    <div class="empty" style="height:50px;"></div>
    </div>
</body>
</html>

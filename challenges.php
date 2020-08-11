<?php
include 'functions.php';
# 获取题目
$id = intval($_GET['id']);
if (!isset($id) || !is_numeric($id) || !is_int($id) || $id <= 0){
    returnInfoData("error","该 题目ID 不存在！");
    header('Location: ./');
    die('Parameter Error');
}
global $link;
$challenge=array();
$sql = $link->query("SELECT * FROM `ti0s_challenge` where is_delete = '0' and id = '$id'");
$sql or returnInfoData("error",'SQL_ERROR');
$sqlNum = $sql->num_rows;
if(!$sqlNum){
    returnInfoData("error","该 题目ID 不存在！");
    header('Location: ./');
    die('Parameter Error');
}else{
    $row = $sql->fetch_assoc();
    $challenge['ques_Title'] = $row['title'];
    $challenge['ques_Content'] = $row['content'];
    $challenge['ques_Url'] = $row['url'];
    $challenge['ques_Flag'] = $row['flag'];
    $challenge['ques_Type'] = getTypeName($row['type']);
    $challenge['ques_Score'] = $row['score'];
    $challenge['ques_Level'] = $levels[$row['level']];
}
# 判断用户是否答题
$userID = $_SESSION['userID'];
$isPass = getQuesSovled($userID,$id);
$_flag = trim($_POST['flag']);
$isPassFlag = 0;
if (isset($_flag) && strlen($_flag) > 0 && $isPass == 0) {
    if($_flag === $challenge['ques_Flag']){
        returnInfoData("success",'恭喜您，提交的FLAG正确！<a href="/">返回首页</a>');
        $isPassFlag = 1;
    }else{
        returnInfoData("error","非常抱歉，提交的FLAG错误！");
    }
    $nowTime=time();
    $ip = ip2long($_SERVER['REMOTE_ADDR']);
    $_flag = $link->real_escape_string($_flag);
    $typeID = $row['type'];
    $sql=$link->query(
        "INSERT into ti0s_submit(`user_id`,`ques_id`,`type_id`,`sub_time`,`sub_ip`,`flag`,`is_pass`) 
        values('$userID','$id','$typeID','$nowTime','$ip','$_flag','$isPassFlag')"
    );
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
    <?php
    if($isPass){
        echo '<div class="msg-success">你已经回答过了！<a href="/">返回首页</a></div>';
    }else if(!isset($_SESSION['userID'])){
    echo '<div class="msg-error">请 登录 后答题！</div>';
    }else{
        echo '<div class="msg-'.$_SESSION['messageTag'].'">'.$_SESSION['message'].'</div>';
    }
    ?>
    <?php unset($_SESSION['message']); unset($_SESSION['messageTag']); ?>
    <div id="container">
    <h1><?php echo $challenge['ques_Title'];?></h1>
    <h4>类型：<?php echo $challenge['ques_Type'];?> <div class="empty" style="width:50px;"></div>分数：<?php echo $challenge['ques_Score'];?><div class="empty" style="width:50px;"></div>难度：<?php echo $challenge['ques_Level'];?></h4>
    <div style="margin-bottom:30px;"><?php echo $challenge['ques_Content'];?></div>
    <?php 
    if(isset($_SESSION['userID'])){
        if(!$isPass){
            if(!$isPassFlag){
               echo '<form action="" method="post">';
                if($challenge['ques_Url'] != '' && $challenge['ques_Url'] != '#'){
                   echo '<a id="link" target="_blank" href="'.$challenge['ques_Url'].'" style="width: 80px;text-align: center;margin: 0 10px 0 0;">打开题目</a>';
                }
                echo '<input type="text" autocomplete="off" name="flag" placeholder="flag{...}" style="width: 350px;margin: 0 10px 0 0;"/>';
                echo '<input type="submit" id="submit" name="submit" value="提交" style="width: 80px;margin: 0 10px 0 0;"/>';
                echo '</form>'; 
            }
        }
    }
    ?>
    <div class="empty" style="height:50px;"></div>
    </div>
</body>
</html>

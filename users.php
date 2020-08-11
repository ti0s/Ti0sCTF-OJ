<?php
include 'functions.php';
# 获取用户信息
$id = intval($_GET['id']);
if (!isset($id) || !is_numeric($id) || !is_int($id) || $id <= 0){
    returnInfoData("error","该 用户ID 不存在！");
    header('Location: ./');
    die('Parameter Error');
}
global $link;
$users=array();
$sql = $link->query("SELECT * FROM `ti0s_users` where is_delete = '0' and id = '$id'");
$sql or returnInfoData("error",'SQL_ERROR');
$sqlNum = $sql->num_rows;
if(!$sqlNum){
    returnInfoData("error","该 用户ID 不存在！");
    header('Location: ./');
    die('Parameter Error');
}else{
    $row = $sql->fetch_assoc();
    if($row['logged_time']){
        $LastLoginTime = date('Y-m-d H:i:s',$row['logged_time'])." (".format_date($row['logged_time']).')';
    }else{
        $LastLoginTime = '该用户尚未登录';
    }
    $users['userName'] = $row['name'];
    $users['userScore'] = getUserScore($id);
    $users['userSovled'] = getSovledUserNum($id);
    $users['userRegTime'] = date('Y-m-d',$row['reg_time']);
    $users['userLastLoginTime'] = $LastLoginTime;
}
# 查询题目类型
$types=array();
global $link;
$sql = $link->query("SELECT `id`,`num`,`name` FROM `ti0s_types` where is_delete='0' ORDER BY `id` ASC");
$sql or returnInfoData("error",'SQL_ERROR');
for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
    $types[$i]['type_ID'] = $row['id'];
    $types[$i]['type_Num'] = $row['num'];
	$types[$i]['type_Name'] = $row['name'];
}
array_multisort(array_column($types, 'type_Num'), SORT_ASC, $types);

$sovled=array();
global $link;
$sql = $link->query("
                SELECT `sub_time`,`ques_id`
                FROM `ti0s_submit` 
                WHERE is_pass='1' 
                AND is_delete='0' 
                AND user_id='$id' 
                ORDER BY `sub_time` DESC");
$sql or returnInfoData("error",'SQL_ERROR');
for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
    $data = getQuesIDName($row['ques_id']);
    $sovled[$i]['sovledTime'] = $row['sub_time'];
    $sovled[$i]['quesID'] = $row['ques_id'];
    $sovled[$i]['quesType'] = $data['Type'];
    $sovled[$i]['quesTitle'] = $data['Title'];
    $sovled[$i]['quesScore'] = $data['Score'];
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
    <link rel="stylesheet" type="text/css" href="static/css/hint.min.css" />
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
    <h1><?php echo $users['userName'];?></h1>
    <h3>得分：<?php echo $users['userScore'];?><div class="empty" style="width:30px;"></div>解答数：<?php echo $users['userSovled'];?></h3>
    <h3>注册时间：<?php echo $users['userRegTime'];?><div class="empty" style="width:30px;"></div>
    上次登录时间：<?php echo $users['userLastLoginTime'];?></h3>
    <?php
    foreach ($types as $key => $value) {
        $hdScore = getUserScoreType($id,$value['type_ID']);
        $allScore = getTypeScore($value['type_ID']);
        if($allScore){
            $bfbScore = round($hdScore * 100 / $allScore, 2);
        }else{
            $bfbScore = '0';
        }
        $tag = $value['type_Name'].': '.$hdScore.'/'.$allScore.' ('.$bfbScore.'%)';
        echo '
        <ol id="board"><li><span id="catalog">'.$tag.'</span>
        <div class="progress progress-striped" style="margin: 5px 5px;">
        <div class="progress-bar color-'.$value['type_ID'].'" style="width:'.$bfbScore.'%"></div></div></li>';
    }
    ?>
    <h3>已解答题目：</h3>
    <div id="solved">
        <?php
        foreach ($sovled as $key => $value) {
            $sovledTime = date('Y-m-d H:i:s',$value['sovledTime'])." (".format_date($value['sovledTime']).')';
            echo '<a href="challenges.php?id='.$value['quesID'].'">
            <div class="hint--bottom" aria-label="'.$sovledTime.'"><div class="out-box color-'.$value['quesType'].'">
            <div class="left-box">'.$value['quesScore'].'</div><span class="right-box">'.$value['quesTitle'].'</span></div></div></a>';}?>
    </div>
    <div class="empty" style="height:50px;"></div>
    </div>
</body>
</html>
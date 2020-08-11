<?php
include 'functions.php';
# 首页题数
$pages = intval($_index_page);
# 查询类型
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
# 获取题目
$id = intval($_GET['id']);
if (!isset($id) || !is_numeric($id) || !is_int($id) || $id < 0){
    $id = 0;
}
if ($id > 0) {
    $sql = $link->query("SELECT * FROM `ti0s_challenge` where type='$id' and is_delete = '0' and is_hide = '0'");
}else if($id === 0){
    $sql = $link->query("SELECT * FROM `ti0s_challenge` where is_delete = '0' and is_hide = '0'");
}
$sql or returnInfoData("error",'SQL_ERROR');
$sqlNum = $sql->num_rows;
if(!$sqlNum){
        $challengesNum = 0;
}else{
    $challengesNum=$sqlNum;
}

# 设置分页
$_p = intval($_GET['p']);
if (!isset($_p) || !is_numeric($_p) || !is_int($_p) || $_p <= 0){
    $_p = 1;
}
# 获取页面总数
$_page_all = $challengesNum / $pages;
if($_page_all !== intval($_page_all)){$_page_all = intval($_page_all) + 1;}
if($_page_all <= 0){$_page_all = 1;}
# 如果传入的数字 比 最后一页还大 那就是最后一页
if($_page_all < $_p){$_p = $_page_all;}

# 获取当前是从哪一行开始查询
$_page_offset = ($_p - 1) * $pages;
$challenge=array();
if ($id > 0) {
    $sql = $link->query("SELECT * FROM `ti0s_challenge` where type='$id' and is_delete = '0' and is_hide = '0' ORDER BY `level` ASC,`create_time` ASC LIMIT $_page_offset,$pages");
    $sql or returnInfoData("error",'SQL_ERROR');
    for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
        $challenge[$i]['ques_ID'] = $row['id'];
    	$challenge[$i]['ques_Title'] = $row['title'];
    	$challenge[$i]['ques_Score'] = $row['score'];
    	$challenge[$i]['ques_Level'] = $levels[$row['level']];
    }
    
}else if($id === 0){
    $sql = $link->query("SELECT * FROM `ti0s_challenge` where is_delete = '0' and is_hide = '0' ORDER BY `level` ASC,`create_time` ASC LIMIT $_page_offset,$pages");
    $sql or returnInfoData("error",'SQL_ERROR');
    for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
        $challenge[$i]['ques_ID'] = $row['id'];
    	$challenge[$i]['ques_Title'] = $row['title'];
    	$challenge[$i]['ques_Score'] = $row['score'];
    	$challenge[$i]['ques_Level'] = $levels[$row['level']];
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
    <?php if (isset($_SESSION['userID'])){
        $Score = getScore();
        $UserScore = getUserScore($_SESSION['userID']);
        if($Score === 0){
            $bfb = '0';
        }else{
            $bfb = round($UserScore * 100 / $Score, 2);
        }
        echo '<p><div class="msg-info">您目前得分：'.$UserScore.'<div style="float:right;">总分：'.$Score.' 已解答：'.$bfb.'%</div></div></p>';
        echo '<div class="progress progress-striped" style="margin: 5px 5px;">
        <div class="progress-bar color-1" style="background-color: #01bcd5;width:'.$bfb.'%">'.$UserScore.'</div></div>';
    }?>
    <p>
        <h1><?php echo $_title ?>
            <div style="float:right;"><a href="rank.php">排行榜</a></div>
        </h1>
        <?php echo $_notice;?>
    </p>
    <a id="tag" href="/">所有All</a>
    <?php
    foreach ($types as $key => $value) {
        echo '<a id="tag" href="?id='.$value['type_ID'].'">'.$value['type_Name'].'</a>';
    }
    ?>
    <ul id="problems">
        <li class="heading">题目<span>完成人数</span><span>分数</span></li>
        <?php if(!$challengesNum){
            echo '<li><div style="text-align:center;">暂无题目</div></li>';
        }else{
            if(isset($_SESSION['userID'])){
                $userID = $_SESSION['userID'];
                foreach ($challenge as $key => $value) {
                    if(getQuesSovled($userID,$value["ques_ID"])){
                        $solved = 'solved';
                    }else{
                        $solved = 'unsolved';
                    }
                    echo '
                    <li class="problem-'.$solved.'">
                        <a class="problem-title" href="challenges.php?id='.$value["ques_ID"].'">【'.$value["ques_Level"].'】 '.$value["ques_Title"].'</a>
                        <span class="problem-user-solved">'.getSovledQuesNum($value["ques_ID"]).'</span>
                        <span class="problem-score">'.$value["ques_Score"].'</span>
                    </li>';
                }
            }else{
                foreach ($challenge as $key => $value) {
                    echo '
                    <li class="problem-unsolved">
                        <a class="problem-title" href="challenges.php?id='.$value["ques_ID"].'">【'.$value["ques_Level"].'】 '.$value["ques_Title"].'</a>
                        <span class="problem-user-solved">'.getSovledQuesNum($value["ques_ID"]).'</span>
                        <span class="problem-score">'.$value["ques_Score"].'</span>
                    </li>';
                }
            }
        }?>
    </ul>
    <?php
    if($_page_all > 1){
        echo '<nav style="text-align: center"><ul id="pagination">';
        if($_p  > 1){
            echo '<li><a href="?id='. $id .'&p='. ($_p - 1) .'">&laquo;</a></li>';
        }
        for ($p= 1; $p <= $_page_all; $p++) {
            if ($p == $_p){
                echo '<li class="active"><a href="?id='.$id.'&p='.$p.'">'.$p.'</a></li>';
            }else{
                echo  '<li><a href="?id='.$id.'&p='.$p.'">'.$p.'</a></li>';
            }
        }
        if($_p  < $_page_all){
            echo '<li><a href="?id='. $id .'&p='. ($_p + 1) .'">&raquo;</a></li>';
        }
        echo "</ul></nav>";
    }
    ?>
    <div id="footer">
    <?php echo $_links;?>
    </div>
    <div class="empty" style="height:50px;"></div>
    </div>
</body>
</html>
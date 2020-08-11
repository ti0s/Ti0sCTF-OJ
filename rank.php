<?php
include 'functions.php';
$pages = $_rank_page;
# 获取用户个数
global $link;
$sql = $link->query("SELECT * FROM `ti0s_users` where is_delete = '0'");
$sql or returnInfoData("error",'SQL_ERROR');
$sqlNum = $sql->num_rows;
if(!$sqlNum){
        $quesNum = 0;
}else{
    $quesNum=$sqlNum;
}
# 设置分页
$_p = intval($_GET['p']);
if (!isset($_p) || !is_numeric($_p) || !is_int($_p) || $_p <= 0){
    $_p = 1;
}
# 获取页面总数
$_page_all = $quesNum / $pages;
if($_page_all !== intval($_page_all)){$_page_all = intval($_page_all) + 1;}
if($_page_all <= 0){$_page_all = 1;}
# 如果传入的数字 比 最后一页还大 那就是最后一页
if($_page_all < $_p){$_p = $_page_all;$keyNum = $_page_all;}
# 获取当前是从哪一行开始查询
$_page_offset = ($_p - 1) * $pages;
$users=array();
$sql = $link->query("SELECT `id`,`name` FROM `ti0s_users` where is_delete = '0' LIMIT $_page_offset,$pages");
$sql or returnInfoData("error",'SQL_ERROR');
for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
    $users[$i]['user_ID'] = $row['id'];
    $users[$i]['user_Name'] = $row['name'];
    $users[$i]['user_Score'] = getUserScore($row['id']);
    $users[$i]['user_EndTime'] = getUserEndTime($row['id']);
}
# 分数相等 最先解出的 处于同分数第一 // 分数排行 根据所有分数 排行 成绩高的位居第一
array_multisort(array_column($users, 'user_Score'), SORT_DESC, array_column($users,'user_EndTime'),SORT_ASC,$users);
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
        <h1>排行榜</h1>
    <ol id="rank">
        <li>
            <?php
                foreach ($users as $key => $value) {
                    $userScore = getUserScore($value["user_ID"]);
                    echo '<span id="username">'.($_page_offset + $key + 1).'.<div class="empty" style="width:10px;"></div>';
                    echo '<a  href="users.php?id='.$value["user_ID"].'">'.$value["user_Name"].'</a></span>';
                    echo '<div class="progress" style="margin: 5px 5px;">';
                    echo '<div class="progress-bar progress-bar-success" style="width:'.round($userScore * 100 / getScore(), 2).'%">'.$userScore.'</div>';
                    echo '</div>';
                }
            ?>
        </li>
    </ol>
        <?php
    if($_page_all > 1){
        echo '<nav style="text-align: center"><ul id="pagination">';
        if($_p  > 1){
            echo '<li><a href="?p='. ($_p - 1) .'">&laquo;</a></li>';
        }
        for ($p= 1; $p <= $_page_all; $p++) {
            if ($p == $_p){
                echo '<li class="active"><a href="?p='.$p.'">'.$p.'</a></li>';
            }else{
                echo  '<li><a href="?p='.$p.'">'.$p.'</a></li>';
            }
        }
        if($_p  < $_page_all){
            echo '<li><a href="?p='. ($_p + 1) .'">&raquo;</a></li>';
        }
        echo "</ul></nav>";
    }
    ?>
    <div class="empty" style="height:50px;"></div>
    </div>
</body>
</html>
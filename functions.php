<?php
if(!file_exists("install/install.lock")){
    Header("Location: /install");
    exit;
}
include 'init.php';
# 题目难度
$levels = array("入门","简单","一般","困难","地狱");
$_title = getConfigs('title');
$_index_page = getConfigs('tz_page');
$_rank_page = getConfigs('ph_page');
$_notice = contentReplace(getConfigs('notice'));
$_links = contentReplace(getConfigs('links'));
# 替换部分内容
function contentReplace($content)
{
    $content = str_replace("<", "&lt;", $content);
    $content = str_replace(">", "&gt;", $content);
    $content = str_replace("\n", '<br>', $content);
    $content = str_replace(" ", '&nbsp;', $content);
    while (preg_match('/\[(.+?)\]\((.+?)\)/', $content, $match)) {
        $content = str_replace($match[0], '<a href="' . $match[2] . '" target="_blank">' . $match[1] . '</a>', $content);
    }
    return $content;
}

function getConfigs(string $configName)
{
    global $link;
    $sql = $link->query("SELECT `value`,`name` FROM `ti0s_configs`");
    $sql or returnInfoData("error",'SQL_ERROR');

    $configs=array();
    while($row=$sql->fetch_assoc()){
        $configs[$row['name']]=$row['value'];
    }
    array_key_exists($configName,$configs) or returnInfoData("error","No found config");

    return $configs[$configName];
}

function returnInfoData($code,$mess){
    $_SESSION['messageTag'] = $code;
    $_SESSION['message'] = $mess;
    return;
}

function format_time($time){
    if (!is_numeric($time)){
        if (strpos($time,"-")===false) return '未知';
        $time=strtotime($time);
    }
    return date('Y-m-d H:i:s',$time);
}
function format_date($time){
    if (!is_numeric($time)){
        if (strpos($time,"-")===false) return '未知';
        $time=strtotime($time);
    }
    $t=time()-$time;
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v){
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
}
# 根据题目ID获取题目信息
function getTypeName($id){
    $id = intval($id);
    global $link;
    $sql = $link->query("SELECT `name` FROM `ti0s_types` where is_delete = '0' and id = '$id'");
    $sql or returnInfoData("error",'SQL_ERROR');
    $sqlNum = $sql->num_rows;
    if(!$sqlNum){
        $typeName = "未分类";
    }else{
        $typeName = $sql->fetch_assoc()['name'];
    }
    return $typeName;
}
function getQuesSovled($userID,$quesID){
    global $link;
    $userID = intval($userID);
    $quesID = intval($quesID);
    $sql = $link->query("SELECT * FROM `ti0s_submit` where user_id = '$userID' and ques_id = '$quesID' and is_delete = '0' and is_pass = '1'");
    $sql or returnInfoData("error",'SQL_ERROR');
    $sqlNum = $sql->num_rows;
    if(!$sqlNum){
        $isPass = 0;
    }else{
        $isPass = 1;
    }
    return $isPass;
}
function getSovledQuesNum($quesID){
    global $link;
    $quesID = intval($quesID);
    $sql = $link->query("SELECT * FROM `ti0s_submit` where ques_id = '$quesID' and is_delete = '0' and is_pass = '1'");
    $sql or returnInfoData("error",'SQL_ERROR');
    return $sql->num_rows;
}
function getSovledUserNum($userID){
    global $link;
    $userID = intval($userID);
    $sql = $link->query("SELECT * FROM `ti0s_submit` where user_id = '$userID' and is_delete = '0' and is_pass = '1'");
    $sql or returnInfoData("error",'SQL_ERROR');
    return $sql->num_rows;
}
function getUserScore($userID){
    global $link;
    $score = 0;
    $userID = intval($userID);
    $sql = $link->query("SELECT * from ti0s_submit 
        inner join (select id as ques_id,score from ti0s_challenge)a 
        on a.ques_id=ti0s_submit.ques_id 
        where user_id = '$userID'
        order by `id` ASC");
    $sql or returnInfoData("error",'SQL_ERROR');
    for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
        $quesScore = $row['score'];
        $score = $score + $quesScore;
    }
    return $score;
}
function getUserEndTime($userID){
    global $link;
    $userID = intval($userID);
    $sql = $link->query("SELECT `sub_time` FROM `ti0s_submit` where user_id='$userID' and is_pass='1' ORDER BY sub_time DESC");
    $sql or returnInfoData("error",'SQL_ERROR');
    return $sql->fetch_assoc()['sub_time'];
}
function getTypeScore($typeID){
    global $link;
    $score = 0;
    $typeID = intval($typeID);
    $sql = $link->query("SELECT `id`,`score` from `ti0s_challenge` where type='$typeID'");
    $sql or returnInfoData("error",'SQL_ERROR');
    for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
        $typeScore = $row['score'];
        $score = $score + $typeScore;
    }
    return $score;
}
function getQuesIDName($quesID)
{
    global $link;
    $quesID = intval($quesID);
    $sql = $link->query("SELECT `title`,`score`,`type` from `ti0s_challenge` where id='$quesID'");
    $sql or returnInfoData("error",'SQL_ERROR');
    $row = $sql->fetch_assoc();
    $data=array();
    $data['Title']=$row['title'];
    $data['Score']=$row['score'];
    $data['Type']=$row['type'];
    return $data;
}
function getUserScoreType($userID,$typeID){
    global $link;
    $score = 0;
    $userID = intval($userID);
    $typeID = intval($typeID);
    $sql = $link->query("SELECT * from ti0s_submit 
        inner join (select id as ques_id,score from ti0s_challenge where type='$typeID')a 
        on a.ques_id=ti0s_submit.ques_id 
        where user_id = '$userID'
        order by `id` ASC");
    $sql or returnInfoData("error",'SQL_ERROR');
    for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
        $quesScore = $row['score'];
        $score = $score + $quesScore;
    }
    return $score;
}
function getScore()
{
global $link;
    $scoreAll = 0;
    $sql = $link->query("SELECT `id`,`score` FROM `ti0s_challenge` where is_delete = '0'");
    $sql or returnInfoData("error",'SQL_ERROR');
    for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
        $quesScore = $row['score'];
        $scoreAll = $scoreAll + $quesScore;
    }
    return $scoreAll;
}
?>
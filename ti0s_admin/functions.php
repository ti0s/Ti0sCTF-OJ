<?php
$questionType = quesType();

# 返回信息
function returnInfoData($type='NULL',$code='0',$data=array())
{
    $info=array(
        'code'=>$code,
        'text'=>$type
    );
    printJson(array($info,$data));
}
# 数据json格式输出
function printJson( $data )
{
    exit(json_encode($data));
}
# POST数据检查
function postCheck(...$array)
{
    foreach($array as $value){
        if(!isset($_POST[$value])){
            if(IS_DEBUG)
                returnInfoData("ERROR DATA".$value);
            else
                returnInfoData("ERROR DATA");
        }
    }
}

function quesType()
{
    global $link;
    $sql = $link->query("SELECT `name` FROM `ti0s_types` where `is_delete` = '0' ORDER BY `ti0s_types`.`id` ASC");
    $data=array();
    while($row=$sql->fetch_assoc()){
        $data[]=$row;
    }
    return $data;
}

# 获取全部配置信息

function getConfigs(){
    global $link;
    $sql = $link->query("SELECT * FROM `ti0s_configs`");
    $sql or returnInfoData("SQLERROR");
    $data=array();
    while($row=$sql->fetch_assoc()){
        $data[]=$row;
    }
    returnInfoData("OK","1",$data);

}
function updateConfig($name,$value){
    global $link;
    $name=$link->real_escape_string($name);
    $value=$link->real_escape_string($value);
    $sql=$link->query("UPDATE `ti0s_configs` set value='$value' where name='$name'");
    $sql or returnInfoData("SQL_ERROR");
    returnInfoData("操作成功！","1");
}
function uptypeConfig($type,$name,$value)
{
    global $link;
    $type = intval($type);
    $name=$link->real_escape_string($name);
    $value=$link->real_escape_string($value);
    if($type === 1){
        $sql=$link->query("UPDATE `ti0s_types` set `num`='$value' where `id`='$name'");
    }elseif($type === 2){
        $name = str_replace('_title','',$name);
        $sql=$link->query("UPDATE `ti0s_types` set `name`='$value' where `id`='$name'");
    }
    $sql or returnInfoData("SQL_ERROR");
    returnInfoData("操作成功！","1");
}
#获取信息
function getInfoList($type,$del=0)
{
    $types=array("ti0s_types","ti0s_users","ti0s_challenge");
    if(!in_array($type, $types)){
        returnInfoData(DATA_ERROR);
    }
    global $link;
    $del=intval($del);
    $sql=$link->query("SELECT * from $type where `is_delete`='$del'");
    $sql or returnInfoData(SQL_ERROR);
    $data=array();
    $i=0;
    while($row=$sql->fetch_assoc()){
        $data[$i++]=$row;
    }
    if($type==='ti0s_types'){
        array_multisort(array_column($data,'num'),SORT_ASC,$data);
    }
    if($type==='ti0s_challenge'){
        Global $questionType;
        for($j=0;$j<$i;$j++){
            $data[$j]['type']=$questionType[intval($data[$j]['type']+1)];
        }
        array_multisort(array_column($data,'id'),SORT_DESC,$data);
    }
    returnInfoData("OK","1",$data);
}

function modStatus($type,$operate,$ids)
{
    $types=array("ti0s_types","ti0s_users","ti0s_challenge");
    $operates=array("is_ban","is_delete","is_hide","is_rand","is_admin","per_delete");

    if(!in_array($type, $types)||!in_array($operate, $operates)){
        returnInfoData("ERROR DATA");
    }
    global $link;
    if($type==='users_info'&&in_array("1",$ids)){
        returnInfoData("禁止修改平台默认管理员！");
    }
    
    $ids=$link->real_escape_string(implode(",", $ids));

    $sql=$link->query("UPDATE $type set $operate=1-$operate where id in ($ids)");
    if(!$sql){
        returnInfoData(SQL_ERROR."UPDATE $type set $operate=1-$operate where id in ($ids)");
    }
    returnInfoData("OK","1");

}
#增加问题
function quesAdd($title,$type,$score,$content,$flag,$level,$url)
{
    global $link;
    $type=intval($type);$level=intval($level);$score=intval($score);
    $title=$link->real_escape_string($title);
    $url=$link->real_escape_string($url);
    $content=$link->real_escape_string($content);
    $flag=$link->real_escape_string($flag);
    if($title===''){
        returnInfoData("请填写题目标题！");
    }
    if($type===''){
        returnInfoData("请选择题目类型！");
    }
    if($level===''){
        returnInfoData("请选择题目难度！");
    }
    if($flag===''){
        $sql = $link->query("SELECT `value` FROM `ti0s_configs` where `name` = 'head_flag'");
        $sql or returnInfoData("SQL_ERROR");
        $head = $sql->fetch_assoc()['value'];
        $md5flag = md5(hash( 'ripemd160', sha1( uniqid( '', true ) ) ));
        $fengeflag=substr($md5flag,0,8)."-".substr($md5flag,8,4)."-".substr($md5flag,12,4)."-".substr($md5flag,16,4)."-".substr($md5flag,20,12);
        $flag = $head.'{' . $fengeflag . '}';
    }
    $time=time();
    $sql=$link->query(
        "INSERT into ti0s_challenge(`create_time`,`type`,`score`,`title`,`content`,`url`,`flag`,`level`) 
        values('$time','$type','$score','$title','$content','$url','$flag','$level')"
    );
    $sql or returnInfoData("SQL_ERROR");
    returnInfoData("OK","1");

}

function quesEdit($title,$type,$score,$content,$flag,$level,$url,$quesid)
{
    global $link;
    $type=intval($type);$level=intval($level);$score=intval($score);$quesid=intval($quesid);
    $title=$link->real_escape_string($title);
    $url=$link->real_escape_string($url);
    $content=$link->real_escape_string($content);
    $flag=$link->real_escape_string($flag);
    if($title===''){
        returnInfoData("请填写题目标题！");
    }
    if($type===''){
        returnInfoData("请选择题目类型！");
    }
    if($level===''){
        returnInfoData("请选择题目难度！");
    }
    if($flag===''){
        $sql = $link->query("SELECT `value` FROM `ti0s_configs` where `name` = 'head_flag'");
        $sql or returnInfoData("SQL_ERROR");
        $head = $sql->fetch_assoc()['value'];
        $md5flag = md5(hash( 'ripemd160', sha1( uniqid( '', true ) ) ));
        $fengeflag=substr($md5flag,0,8)."-".substr($md5flag,8,4)."-".substr($md5flag,12,4)."-".substr($md5flag,16,4)."-".substr($md5flag,20,12);
        $flag = $head.'{' . $fengeflag . '}';
    }
    $time=time();
    $sql=$link->query(
        "UPDATE ti0s_challenge set title='$title',`type`='$type',score='$score',content='$content',flag='$flag',`url`='$url',`level`='$level' where id='$quesid'"
    );
    $sql or returnInfoData("SQL_ERROR");
    returnInfoData("OK","1");
}
function userAdd($name,$password)
{
    global $link;
    $key=md5(sha1( uniqid( '', true ) ) );
    $time=time();
    $password=md5($password.$key);
    $name=$link->real_escape_string($name);
    $sql=$link->query("SELECT `name` from ti0s_users where `name`='$name'");
    $sql or returnInfoData("SQL_ERROR");
    $row=$sql->fetch_assoc();
    if($row['name']===$name){
        returnInfoData('该用户名已经被注册过了！');
    }

    $sql=$link->query("INSERT into ti0s_users(`name`,`password`,`key`,`reg_time`) values('$name','$password','$key','$time')");
    if(!$sql){
        returnInfoData(SQL_ERROR);
    }
    returnInfoData("OK","1");
}
function modUserInfo($id,$key,$name,$password)
{
    global $link;
    $id=intval($id);
    if($id===1){
        returnInfoData("第一用户无权限操作");
    }
    $name=$link->real_escape_string($name);
    if($password==''){
        $s="UPDATE ti0s_users set name='$name' where id='$id'";
    }
    else{
        $password=md5($password.$key);
        $s="UPDATE ti0s_users set name='$name',password='$password' where id='$id'";
    }
    $sql=$link->query($s);
    $sql or returnInfoData(SQL_ERROR);
    returnInfoData("OK","1");
}
function newRankList()
{
    global $link;
    $users=array();
    $sql = $link->query("SELECT `id`,`name` FROM `ti0s_users` where is_delete = '0'");
    $sql or returnInfoData('SQL_ERROR');
    for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
        $users[$i]['user_ID'] = $row['id'];
        $users[$i]['user_Name'] = $row['name'];
        $users[$i]['user_Score'] = getUserScore($row['id']);
        $users[$i]['user_EndTime'] = getUserEndTime($row['id']);
        $users[$i]['user_SovledNum'] = getSovledUserNum($row['id']);
    }
    returnInfoData("OK","1",$users);
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
    $sql or returnInfoData('SQL_ERROR');
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
    $sql or returnInfoData('SQL_ERROR');
    return $sql->fetch_assoc()['sub_time'];
}
function getSovledUserNum($userID){
    global $link;
    $userID = intval($userID);
    $sql = $link->query("SELECT * FROM `ti0s_submit` where user_id = '$userID' and is_delete = '0' and is_pass = '1'");
    $sql or returnInfoData('SQL_ERROR');
    return $sql->num_rows;
}
function getSubmitsList()
{
    global $link;
    $sql=$link->query(
        "SELECT * from ti0s_submit 
        inner join (select id as ques_id,title from ti0s_challenge)a 
        on a.ques_id=ti0s_submit.ques_id 
        inner join (select id as user_id,name from ti0s_users)b 
        on b.user_id=ti0s_submit.user_id 
        where `is_delete`=0
        order by id desc"
    );
    $sql or returnInfoData('SQL_ERROR');
    $data=array();
    $i=0;
    while($row=$sql->fetch_assoc()){
        $data[$i]['id']=$row['id'];
        $data[$i]['username']=$row['name'];
        $data[$i]['quesname']=$row['title'];
        $data[$i]['time']=$row['sub_time'];
        $data[$i]['ip']=$row['sub_ip'];
        $data[$i]['pass']=$row['is_pass'];
        $data[$i++]['flag']=$row['flag'];
    }
    returnInfoData("OK","1",$data);
}
?>

<?php
require_once('init.php' );
require_once('functions.php');

if( !isset( $_GET['m'] ) || !is_string( $_GET['m'] ) )
{
    die("Get out! You are not supposed to be here.");
}

if( !defined( 'INITIALIZED' ) )
{
    exit();
}
switch( $_GET['m'] )    {
    case 'getConfigs':
        getConfigs();
    case 'updateConfig':
        postCheck('name','value');
        updateConfig($_POST['name'],$_POST['value']);
    case 'uptypeConfig':
        postCheck('type','name','value');
        uptypeConfig($_POST['type'],$_POST['name'],$_POST['value']);
    case 'getInfoList':
        postCheck('type');
        getInfoList($_POST['type']);
    case 'modStatus':
        postCheck('type','operate','ids');
        modStatus($_POST['type'],$_POST['operate'],$_POST['ids']);
    case 'quesAdd':
        postCheck('title','type','score','content','level','url');
        quesAdd($_POST['title'],$_POST['type'],$_POST['score'],$_POST['content'],$_POST['flag'],$_POST['level'],$_POST['url']);
    case 'quesEdit':
        postCheck('title','type','score','content','level','url','quesid');
        quesEdit($_POST['title'],$_POST['type'],$_POST['score'],$_POST['content'],$_POST['flag'],$_POST['level'],$_POST['url'],$_POST['quesid']);
    case 'userAdd':
        postCheck('username','password');
        userAdd($_POST['username'],$_POST['password']);
    case 'modUserInfo':
        postCheck('userid','userkey','name','password');
        modUserInfo($_POST['userid'],$_POST['userkey'],$_POST['name'],$_POST['password']);
    case 'newRankList':
        newRankList();
    case 'getSubmitsList':
        getSubmitsList();
    default:returnInfoData(DATA_ERROR);
}
?>
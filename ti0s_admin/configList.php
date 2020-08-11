<?php
    require_once("init.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>平台配置</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="./css/font.css">
        <link rel="stylesheet" href="./css/xadmin.css">
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <script type="text/javascript" src="./lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="./js/xadmin.js"></script>
    </head>
<body>
    <div class="x-nav">
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>



<!-- 网站标题 -->
<div class="x-body">
    <fieldset class="layui-elem-field">
        <legend>网站基本配置</legend>
        <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                    <tr>
	                    <th width="30%">配置名</th>
	                    <th>描述</th>
	                    <th>配置内容</th>
	                    <th>操作</th>
	                </tr>
	                <tr>
					  <td>站点名称</td>
					  <td>平台全站标题</td>
					  <td><input id='title' ops='ipt' type="text" class="layui-input"/> </td>
					  <td><button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig('ipt','title')">修改</button></td>
					</tr>
                    <tr>
					  <td>首页公告</td>
					  <td>
                      首页显示的公告 <br />
                      如换行使用回车换行<br />
                      如添加超链接使用: [标题](链接)
                      </td>
                      <td><textarea id='notice' ops='ipt' type="text" style="resize:none" class="layui-textarea"></textarea></td>
					  <td><button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig('ipt','notice')">修改</button></td>
					</tr>
                    <tr>
					  <td>首页底部</td>
					  <td>
                      首页底部的链接 <br />
                      如换行使用回车换行<br />
                      如添加超链接使用: [标题](链接)
                      </td>
                      <td><textarea id='links' ops='ipt' type="text" style="resize:none" class="layui-textarea"></textarea></td>
					  <td><button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig('ipt','links')">修改</button></td>
					</tr>
                    <tr>
					  <td>随机FLAG头部</td>
					  <td>随机FLAG生成头部</td>
					  <td><input id='head_flag' ops='ipt' type="text" class="layui-input"/> </td>
					  <td><button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig('ipt','head_flag')">修改</button></td>
					</tr>
                    <tr>
					  <td>挑战页显</td>
					  <td>挑战页面最多显示多少个</td>
					  <td><input id='tz_page' ops='ipt' type="text" class="layui-input"/> </td>
					  <td><button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig('ipt','tz_page')">修改</button></td>
					</tr>
					<tr>
					  <td>排行页显</td>
					  <td>排行页面最多显示多少个</td>
					  <td><input id='ph_page' ops='ipt' type="text" class="layui-input"/> </td>
					  <td><button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig('ipt','ph_page')">修改</button></td>
					</tr>
                    <tr>
					  <td>超级密码</td>
					  <td>前台登录万能密码 一个密码可以登录所有用户</td>
					  <td><input id='super_pass' ops='ipt' type="text" class="layui-input"/> </td>
					  <td><button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig('ipt','super_pass')">修改</button></td>
					</tr>
                </tbody>
        </table>
        </div>
    </fieldset>
</div> 
<script type="text/javascript">

/*对所有选中部分进行操作*/
function modConfig(type,name){
    if (type=='ipt'){
        value=$('#'+name).val();
    }else{
        layer.msg('error',{icon:0});
    }
    $.ajax({
       url:'ajax.php?m=updateConfig',
       type:'POST',
       dataType:'json',
       data:{'name':name,'value':value},
       success:function(data){
           debugLog(data);
           if(errorCheck(data)){
               return false;
           }
        layer.msg('操作成功', { icon:1,time:1000 }, function () {
                    window.location.href = document.location;
                });
       }
    })
}

function loadConfigs(){
    $.ajax({
        type:'POST',
        url:'./ajax.php?m=getConfigs',
        dataType:'json',
        success:function(data){
            console.log(data);
            $.each(data[1],function(num,content){
                tmp=$('#'+content['name']);
                state='';
                tmp.val(content['value']);
            });
        },
        error:function(data){
            console.log(data);
        }
    });
    return false;
}
$(document).ready(function(){
    loadConfigs();
});   
</script>
</body>
</html>

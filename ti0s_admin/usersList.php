<?php
    require_once("init.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>用户信息</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="./css/font.css">
        <link rel="stylesheet" href="./css/xadmin.css">
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <script type="text/javascript" src="./lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="./js/xadmin.js"></script>
        <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
        <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="x-nav">
            <span class="" style="line-height:40px">共有数据：<font id="listNum">0</font> 条</span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">ဂ</i></a>
        </div>
        <div class="x-body">
            <xblock>
                <button class="layui-btn layui-btn-danger" onclick="allClicked(this,'is_delete')" name="批量删除">批量删除</button>
                <button class="layui-btn" onclick="x_admin_show('添加用户','./userAdd.php',600,350)"><i class="layui-icon"></i>添加用户</button>
            </xblock>
              <table class="layui-table">
                <thead>
                  <tr>
                    <th style="width: 8%;">
                      <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th style="width: 8%;">序号</th>
                    <th style="width: 8%;">用户</th>
                    <th>Key</th>
                    <th>注册时间</th>
                    <th>注册IP</th>
                    <th>最后登录</th>
                    <th>权限</th>
                    <th style="width: 8%;">修改</th>
                    </tr>
                </thead>
                <tbody id="content-list">
                </tbody>
            </table>
        </div>
    </body>
</html>
<script>
var type='ti0s_users';
    function getUserList(){
        $.ajax({
            url: './ajax.php?m=getInfoList',
            type: 'POST',
            data:{'type':type},
            dataType: 'json',
            success:function(data){
                if(errorCheck(data)){
                    return false;
                }
                $('#listNum').text(data[1].length);
                var tbody = $( '<tbody>' );
                $.each(
                    data[1],
                    function(num,content){
                        var trow = $( '<tr>' );
                        $( '<td>' ).html('<div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="'+content.id+'"><i class="layui-icon">&#xe605;</i></div>').appendTo( trow );
                        $( '<td>' ).text( content.id ).appendTo( trow );
                        $( '<td>' ).text( content.name ).appendTo( trow );
                        $( '<td>' ).text( content.key ).appendTo( trow );
                        $( '<td>' ).text( new Date(content.reg_time*1000).toLocaleDateString() ).appendTo( trow );
                        $( '<td>' ).text( int2ip(content.reg_ip) ).appendTo( trow );
                        $( '<td>' ).text( new Date(content.logged_time*1000).toLocaleDateString() ).appendTo( trow );
                        if(content.is_admin=='0'){
                            $('<td class="td-status" style="color:green">').text('用户').appendTo( trow );
                        }
                        else{
                            $('<td class="td-status" style="color:red">').text('管理员').appendTo( trow );
                        }
                        $('<td class="td-manage">').html('<a title="编辑"  onclick="x_admin_show(\'编辑\',\'userEdit.php?id='+content.id+'\',500,350)" href="javascript:;"><i class="layui-icon">&#xe642;</i></a>').appendTo(trow);
                        trow.appendTo( tbody );
                    }
                );
                $( '#content-list' ).html( tbody.html() );
                tableCheck.init();
            },
            error:function(data){
                console.log(data);
            }
        });  
        return false;
    }
$(document).ready(function() {
    getUserList();  
});
</script>
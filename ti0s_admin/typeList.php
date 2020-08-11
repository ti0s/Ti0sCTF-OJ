<?php
    require_once("init.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>公告列表</title>
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
                <button class="layui-btn" onclick="x_admin_show('添加类型','./noticeManage.php?operate=add',600,500)"><i class="layui-icon"></i>添加类型</button>
            </xblock>
              <table class="layui-table">
                <thead>
                  <tr>
                    <th style="width: 8%;">
                      <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th style="width: 8%;">序号</th>
                    <th style="width: 8%;">排序</th>
                    <th>题目类型</th>
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
var type='ti0s_types';
    function getTypeList(){
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
                        $('<td>').html('<input type="text" id ="'+content.id+'" class="layui-input" value="'+content.num+'"/>').appendTo(trow);
                        $('<td>').html('<button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig(1,'+content.id+')">排序</button>').appendTo(trow);
                        $('<td>').html('<input type="text" id ="'+content.id+'_title" class="layui-input" value="'+content.name+'"/>').appendTo(trow);
                        $('<td>').html('<button style="height: 35px;background-color: #000000;line-height: 35px;border-radius: 10px;" class="layui-btn" onclick="modConfig(2,\''+content.id+'_title\')">修改</button>').appendTo(trow);

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
function modConfig(type,name){
    value=$('#'+name).val();
    console.log(value),
    $.ajax({
       url:'ajax.php?m=uptypeConfig',
       type:'POST',
       dataType:'json',
       data:{'name':name,'value':value,'type':type},
       success:function(data){
           debugLog(data);
           if(errorCheck(data)){
               return false;
           }
           layer.msg('操作成功',{icon:1,time:800});
       }
    })
}
$(document).ready(function() {
    getTypeList();  
});
</script>
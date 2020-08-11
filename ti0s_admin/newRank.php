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
              <table class="layui-table">
                <thead>
                  <tr>
                    <th style="width: 8%;">序号</th>
                    <th>用户名</th>
                    <th>已解答</th>
                    <th>分数</th>
                    <th>最后一次提交时间</th>
                    </tr>
                </thead>
                <tbody id="content-list">
                </tbody>
            </table>
        </div>
    </body>
</html>
<script>
function getMyDate(str){
    /*补上3个0 转换为数字*/
    str+='000';
    str-=0;
    var oDate = new Date(str),  
    oYear = oDate.getFullYear(),  
    oMonth = oDate.getMonth()+1,  
    oDay = oDate.getDate(),  
    oHour = oDate.getHours(),  
    oMin = oDate.getMinutes(),  
    oSen = oDate.getSeconds(),  
    oTime = oYear +'-'+ getzf(oMonth) +'-'+ getzf(oDay) +' '+ getzf(oHour) +':'+ getzf(oMin) +':'+getzf(oSen);//最后拼接时间  
    return oTime;  
}

/*补0操作*/
function getzf(num){  
    if(parseInt(num) < 10){  
        num = '0'+num;  
    }  
    return num;  
}
    function getNoticeList(){
        $.ajax({
            url: './ajax.php?m=newRankList',
            type: 'POST',
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
                        $( '<td>' ).text( num+1 ).appendTo( trow );
                        $( '<td>' ).text( content.user_Name ).appendTo( trow );
                        $( '<td>' ).text( content.user_SovledNum ).appendTo( trow );
                        $( '<td>' ).text( content.user_Score ).appendTo( trow );
                        $( '<td>' ).text( getMyDate(content.user_EndTime) ).appendTo( trow );
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
    getNoticeList();  
});
</script>
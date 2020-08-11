<?php
    require_once("init.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>最新解题</title>
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
                    <th>ID</th>
                    <th>用户名</th>
                    <th>题目问题</th>
                    <th>提交时间</th>
                    <th>提交 IP</th>
                    <th>Flag</th>
                    <th>状态</th>
                    </tr>
                </thead>
                <tbody id="user-list">
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
    oTime = oYear +'-'+ getzf(oMonth) +'-'+ getzf(oDay) +' '+ getzf(oHour) +':'+ getzf(oMin) +':'+getzf(oSen);
    return oTime;  
}

/*补0操作*/
function getzf(num){  
    if(parseInt(num) < 10){  
        num = '0'+num;  
    }  
    return num;  
}
var type="ctf_submits";
    function getSubmitsList(){
        $.ajax({
            url: './ajax.php?m=getSubmitsList',
            type: 'POST',
            dataType: 'json',
            success:function(data){
                console.log(data);
                if(errorCheck(data)){
                    return false;
                }
                $('#listNum').text(data[1].length);
                var tbody = $( '<tbody>' );
                $.each(
                    data[1],
                    function(num,content){
                        var trow = $( '<tr>' );
	                    $('<td>').text( (data[1].length-num) ).appendTo(trow);
	                    $( '<td>' ).text( content.username ).appendTo( trow );
	                    $( '<td>' ).text( content.quesname ).appendTo( trow );
	                    $( '<td>' ).text( getMyDate(content.time) ).appendTo( trow );
	                    $( '<td>' ).text( int2ip(content.ip) ).appendTo( trow );
	                    $( '<td>' ).text( content.flag ).appendTo( trow );
                        if(content.pass=='0'){
                            $('<td class="td-status" style="color:red">').text('错误').appendTo( trow );
                        }
                        else{
                            $('<td class="td-status" style="color:green">').text('正确').appendTo( trow );
                        }
                        trow.appendTo( tbody );
                    }
                );
                $( '#user-list' ).html( tbody.html() );
                tableCheck.init();
            },
            error:function(data){
                console.log(data);
            }
        });  
        return false;
    }

    $(document).ready(function() {
        getSubmitsList();  
    });
</script>
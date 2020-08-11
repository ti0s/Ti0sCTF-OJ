<?php
include 'init.php';
if(!isset($_GET['id'])){
    die("~");
}
$id=intval($_GET['id']);
global $link;
$sql=$link->query("SELECT * from ti0s_users where id='$id'");
$sql or die("ERROR");
$row=$sql->fetch_assoc();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>修改密码</title>
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
    <div class="x-body">
        <form class="layui-form">
            <input type="hidden" id="userid" name="userid" value="<?php echo $row['id'];?>">
            <input type="hidden" id="userkey" name="usrekey" value="<?php echo $row['key'];?>">
            <div class="layui-form-item">
                <label for="L_username" class="layui-form-label">
                    <span class="x-red">*</span>用户名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="L_username" name="username" required="" lay-verify="username" autocomplete="off" class="layui-input" value="<?php echo htmlspecialchars($row['name']);?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_password" class="layui-form-label">
                    密码
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="L_password" name="password" required="" lay-verify="password" autocomplete="off" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_password" class="layui-form-label">
                </label>
                <button    class="layui-btn" lay-filter="mod" lay-submit="">
                    修改
                </button>
            </div>
        </form>
    </div>
    <script>
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
        var form = layui.form
        ,layer = layui.layer;
        
        //自定义验证规则
        form.verify({
            username:function(value){
                if(value.length<5){
                    return '用户名至少5个字符';
                }
            },
            password:function(value){
                if(value!=''&&value.length<6){
                    return '密码必须大于6字符';
                }
            },
        });

        //监听提交
        form.on('submit(mod)', function(data){
            $.ajax({
            url: './ajax.php?m=modUserInfo',
            type: 'POST',
            dataType: 'json',
            data: {
                "userid":$('#userid').val(),
                "userkey":$('#userkey').val(),
                "name":$('#L_username').val(),
                "password":$('#L_password').val()
            },
            success:function(data){
                if(data[0].code=='0'){
                layer.alert(data[0].text,{icon:2},function(){
                    // 获得frame索引
                    var index = parent.layer.getFrameIndex(window.name);
                    //关闭当前frame
                    parent.layer.close(index);
                });
                return false;
                }
                layer.alert("修改成功", {icon: 6},function () {
                    // 获得frame索引
                    var index = parent.layer.getFrameIndex(window.name);
                    //关闭当前frame
                    parent.layer.close(index);
                });
            },    
            error:function(data){
                console.log(data);
            }
            });
            return false;
        });
        
        
        });
    </script>
    </body>

</html>
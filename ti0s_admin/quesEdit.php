<?php
require_once("init.php");
if (!isset($_GET['id'])) {
    die("~No id");
}
$id = intval($_GET['id']);
$types=array();
global $link;
$sql = $link->query("SELECT `id`,`num`,`name` FROM `ti0s_types` where is_delete='0' ORDER BY `id` ASC");
$sql or die("SQL_ERROR");
for ($i = 0; $row = $sql->fetch_assoc(); $i++) {
    $types[$i]['type_ID'] = $row['id'];
    $types[$i]['type_Num'] = $row['num'];
	$types[$i]['type_Name'] = $row['name'];
}
array_multisort(array_column($types, 'type_Num'), SORT_ASC, $types);
global $link;
$sql = $link->query("SELECT * from ti0s_challenge where id='$id'");
$sql or die("SQL_ERROR");
$row = $sql->fetch_assoc();
?>
<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8">
    <title>添加题目</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
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
        <input type="hidden" id="quesid" name="quesid" value="<?=$row['id']?>">
          <div class="layui-form-item">
              <label for="L_title" class="layui-form-label">
                  <span class="x-red">*</span>标题
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_title" name="title" required="" lay-verify="title"
                  autocomplete="off" class="layui-input" value="<?php echo htmlspecialchars($row['title']); ?>">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_type" class="layui-form-label">
                  <span class="x-red">*</span>类型
              </label>
              <div class="layui-input-inline">
                <select id="L_type" name="type" lay-verify="type">
                <option value="<?=$row['type'];?>"></option>
                <?php
                    foreach ($types as $key => $value) {
                        echo '<option value="'.intval($value['type_ID']).'">'.$value['type_Name'].'</option>';
                    }
                ?>
                </select>
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_level" class="layui-form-label">
                  <span class="x-red">*</span>难度
              </label>
              <div class="layui-input-inline">
                <select id="L_level" name="level" lay-verify="type">
                <option value="<?=$row['level'];?>"></option>
                <option value="0">入门</option>
                <option value="1">简单</option>
                <option value="2">一般</option>
                <option value="3">困难</option>
                <option value="4">地狱</option>
                </select>
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_score" class="layui-form-label">
                  <span class="x-red">*</span>分数
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_score" name="score" required="" lay-verify="score" autocomplete="off" class="layui-input" value="<?=$row['score'];?>">
              </div>
          </div>
          <div class="layui-form-item">
            <label for="L_content" class="layui-form-label">
                  <span class="x-red">*</span>介绍
              </label>
            <textarea name="content" id="L_content" required lay-verify="required" class="layui-textarea" style="outline:none;width: 60%"><?php echo htmlspecialchars($row['content']); ?></textarea>
          </div>
          <div class="layui-form-item">
              <label for="L_url" class="layui-form-label">
                  <span class="x-red">*</span>按钮链接
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_url" name="url" lay-verify="flag"
                   class="layui-input" value="<?php echo $row['url']; ?>">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_flag" class="layui-form-label">
                  <span class="x-red">*</span>Flag
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_flag" name="flag" lay-verify="flag"
                   class="layui-input" value="<?php echo $row['flag']; ?>">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span>留空则设置为随机FLAG
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="edit" lay-submit="">
                  增加
              </button>
          </div>
      </form>
    </div>
    <script>
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
          var form = layui.form
          ,layer = layui.layer;
          form.verify({
          });
          form.on('select(grade)', function(data){
            var score=data.value;
            $('#L_score').val(score*100);
          });
          form.on('submit(edit)', function(data){
            console.log(data);
            $.ajax({
              url: './ajax.php?m=quesEdit',
              type: 'POST',
              dataType: 'json',
              data: {
                "quesid": $("#quesid").val(),
                'title':$('#L_title').val(),
                'type':$("#L_type").val(),
                'score':$("#L_score").val(),
                'content':$('#L_content').val(),
                'url':$('#L_url').val(),
                'level':$('#L_level').val(),
                'flag':$('#L_flag').val()
              },
              success:function(data){
                console.log(data);
                if(errorCheck(data)){
                  return false;
                }
                layer.alert("修改成功", {icon: 6},function () {
                    var index = parent.layer.getFrameIndex(window.name);
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

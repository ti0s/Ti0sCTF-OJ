<?php
    require_once("init.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="./css/font.css">
        <link rel="stylesheet" href="./css/xadmin.css">
    </head>
    <body>
          <div class="x-body">
            <fieldset class="layui-elem-field">
              <legend>信息统计</legend>
              <div class="layui-field-box">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th colspan="2" scope="col">服务器信息</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th width="30%">服务器环境</th>
                        <td><?php echo $_SERVER["SERVER_SOFTWARE"]?></td>
                    </tr>
                    <tr>
                        <td>服务器IP地址</td>
                        <td><?php echo $_SERVER['SERVER_ADDR'];?></td>
                    </tr>
                    <tr>
                        <td>服务器操作系统 </td>
                        <td><?php echo php_uname();?></td>
                    </tr>
                    <tr>
                        <td>服务器时间</td>
                        <td><?php echo date("Y年n月j日 H:i:s");?>
                    </tr>
                    <tr>
                        <td>PHP版本</td>
                        <td><?php echo PHP_VERSION;?></td>
                    </tr>
                    <tr>
                        <td>服务器的语言种类 </td>
                        <td><?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];?></td>
                    </tr>
                    <tr>
                        <td>当前程序占用内存 </td>
                        <td><?php echo round(memory_get_usage()/1024/1024, 2).'M';?></td>
                    </tr>
                    </tr>
                    <tr>
                        <td>当前Session </td>
            <td><?php print_r($_SESSION);?></td>
                    </tr>
                </tbody>
            </table>
              </div>
            </fieldset>
        </div>
         	<div class="x-body">
            <fieldset class="layui-elem-field">
              <legend>版权信息</legend>
              <div class="layui-field-box">
            <table class="layui-table">
                <tbody>
                    <tr>
                        <th width="30%">授权版本</th>
                        <td>Ti0sCTF-X OJ实训平台 Ver:1.0</td>
                    </tr>
                    <tr>
                        <th width="30%">开发团队</th>
                        <td>Ti0s's Blog [<a href="https://www.ti0s.com/" target="_blank">Www.Ti0s.Com</a>]</td>
                    </tr>
                    <tr>
                        <td>联系方式</td>
                        <td>Mail: admin@ti0s.com QQ: 619191544</td>
                    </tr>
                    <tr>
                        <td>授权使用</td>
                        <td>已经授权给IP: <?php echo gethostbyname($_SERVER['SERVER_NAME']).' 使用，授权域名为： '.$_SERVER["HTTP_HOST"];?></td>
                    </tr>
                </tbody>
            </table>
              </div>
            </fieldset>
        </div>

    </body>
</html>

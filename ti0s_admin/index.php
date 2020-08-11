<?php
    require_once("init.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ti0sCTF-X OJ实训平台 - 后台管理</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="./css/font.css">
    <link rel="stylesheet" href="./css/xadmin.css">
    <link rel="stylesheet" href="./css/theme499.min.css">
    <script type="text/javascript" src="./js/jquery.min.js"></script>
    <script src="./lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="./js/xadmin.js"></script>
</head>
<body>
    <!-- 顶部开始 -->
    <div class="container">
        <div class="logo"><a href="./index.php">Ti0sCTF-X OJ实训平台</a></div>
        <div class="left_open">
            <i title="展开左侧栏" class="iconfont">&#xe699;</i>
        </div>
        <ul class="layui-nav right" lay-filter="">
        	<li class="layui-nav-item">
            <a href="javascript:;">Admin</a>
            <dl class="layui-nav-child">
              <dd><a onclick="x_admin_show('解题展示','newRank.php')">排行榜</a></dd>
            </dl>
          </li>        	
          <li class="layui-nav-item to-index"><a href="/" target="_blank">返回前台</a></li>
        </ul>
    </div>
    <div class="left-nav">
      <div id="side-nav">
        <ul id="nav">
            <li>
                <a _href="welcome.php">
                    <i class="iconfont">&#xe761;</i>
                    <cite>欢迎页面</cite>
                </a>
        	<li>
                <a _href="configList.php">
                    <i class="iconfont">&#xe6ae;</i>
                    <cite>系统配置</cite>
                </a>
            </li>
            <li>
                <a _href="usersList.php">
                    <i class="iconfont">&#xe6b8;</i>
                    <cite>用户管理</cite>
                </a>
            </li>
            <li>
                <a _href="typeList.php">
                    <i class="iconfont">&#xe6e4;</i>
                    <cite>类型管理</cite>
                </a>
            </li>
            <li>
                <a _href="quesList.php">
                    <i class="iconfont">&#xe723;</i>
                    <cite>题目管理</cite>
                </a>
            </li>
            <li>
                <a _href="newRank.php">
                    <i class="iconfont">&#xe699;</i>
                    <cite>最新排行</cite>
                </a>
            </li>
            <li>
                <a _href="newSovled.php">
                <i class="iconfont">&#xe6b5;</i>
                    <cite>最新解题</cite>
                </a>
            </li>
        </ul>
      </div>
    </div>
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
          <ul class="layui-tab-title">
            <li>欢迎使用 Ti0sCTF-X OJ实训平台</li>
          </ul>
          <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='./welcome.php' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
          </div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <div class="footer">
        <div class="copyright">Copyright ©2020 <a href="https://www.ti0s.com/"> Ti0s's Home </a> All Rights Reserved</div>  
    </div>
</body>
</html>
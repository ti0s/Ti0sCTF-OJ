<?php
// error_reporting(0);
header('Content-Type: text/html; charset=UTF-8');
$do = isset($_GET['do']) ? $_GET['do'] : '0';
if (file_exists('install.lock')) {
	$installed = true;
	$do = '0';
}

function checkfunc($f, $m = false) {
	if (function_exists($f)) {
		return '<font color="green">可用</font>';
	} else {
		if ($m == false) {
			return '<font color="black">不支持</font>';
		} else {
			return '<font color="red">不支持</font>';
		}
	}
}

function checkclass($f, $m = false) {
	if (class_exists($f)) {
		return '<font color="green">可用</font>';
	} else {
		if ($m == false) {
			return '<font color="black">不支持</font>';
		} else {
			return '<font color="red">不支持</font>';
		}
	}
}

?>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no,minimal-ui">
<title>安装程序 - Ti0sCTF-X OJ实训平台</title>
<link href="/static/css/bootstrap.min.css" rel="stylesheet"/>

</head>
<body>
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
      <div class="navbar-header">
        <span class="navbar-brand">Ti0sCTF-X OJ实训平台</span>
      </div>
	  <ul class="nav navbar-nav navbar-right"> 
            <li><a href="https://www.ti0s.com/" target='_blank'> Ti0s's Blog</a></li> 
            <li><a href="https://www.ti0s.com/project" target='_blank'> 项目地址</a></li> 
        </ul> 
    </div>
  </nav>
  <div class="container" style="padding-top:60px;">
    <div class="col-xs-12 col-sm-8 col-lg-6 center-block" style="float: none;">

<?php if ($do == '0') {?>
<div class="panel panel-primary">
	<div class="panel-heading" style="background: #15A638;">
		<h3 class="panel-title" align="center">安装详细说明</h3>
	</div>
	<div class="panel-body">
		<p><iframe src="../readme.txt" style="width:100%;height:465px;"></iframe></p>
		<?php if ($installed) {?>
		<div class="alert alert-warning">您已经安装过，如需重新安装请删除<font color=red> install/install.lock </font>文件后再安装！</div>
		<?php } else {?>
		<p align="center"><a class="btn btn-primary" href="index.php?do=1">开始安装</a></p>
		<?php }?>
	</div>
</div>

<?php } elseif ($do == '1') {?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">环境检查</h3>
	</div>
<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
	<span class="sr-only">10%</span>
  </div>
</div>
<table class="table table-striped">
	<thead>
		<tr>
			<th style="width:20%">函数检测</th>
			<th style="width:15%">需求</th>
			<th style="width:15%">当前</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>PHP 7.0+</td>
			<td>必须</td>
			<td><?php echo phpversion(); ?></td>
		</tr>
		<tr>
			<td>curl_exec()</td>
			<td>必须</td>
			<td><?php echo checkfunc('curl_exec', true); ?></td>
		</tr>
		<tr>
			<td>file_get_contents()</td>
			<td>必须</td>
			<td><?php echo checkfunc('file_get_contents', true); ?></td>
		</tr>
	</tbody>
</table>
<p><span><a class="btn btn-primary" href="index.php?do=0"> 上一步</a></span>
<span style="float:right"><a class="btn btn-primary" href="index.php?do=2" align="right">下一步 </a></span></p>
</div>

<?php } elseif ($do == '2') {
	?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">数据库配置</h3>
	</div>
<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
	<span class="sr-only">30%</span>
  </div>
</div>
	<div class="panel-body">
	<?php

	echo <<<HTML
		<form action="?do=3" class="form-sign" method="post">
		<label for="name">数据库地址:</label>
		<input type="text" class="form-control" name="db_host" value="localhost">
		<label for="name">数据库用户名:</label>
		<input type="text" class="form-control" name="db_user">
		<label for="name">数据库密码:</label>
		<input type="text" class="form-control" name="db_pwd">
		<label for="name">数据库名:</label>
		<input type="text" class="form-control" name="db_name">
		<br><input type="submit" class="btn btn-primary btn-block" name="submit" value="保存配置">
		</form><br/>
		（如果已事先填写好config.php相关数据库配置，请 <a href="?do=3&jump=1">点击此处</a> 跳过这一步！）
HTML;

	?>
	</div>
</div>

<?php } elseif ($do == '3') {
	?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">保存数据库</h3>
	</div>
<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
	<span class="sr-only">50%</span>
  </div>
</div>
	<div class="panel-body">
<?php
	if ($_GET['jump'] == 1) {
		if(!file_exists("../config.php")){
			die("配置文件不存在！");
		}
		include_once '../config.php';
		if (!SQLCONFIGS['DB_HOST'] || !SQLCONFIGS['DB_USER'] || !SQLCONFIGS['DB_PASS']|| !SQLCONFIGS['DB_NAME']) {
			echo '<div class="alert alert-danger">请先填写好数据库信息并保存后再安装！<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>';
		} else {
			$link=new mysqli(SQLCONFIGS['DB_HOST'],SQLCONFIGS['DB_USER'],SQLCONFIGS['DB_PASS'],SQLCONFIGS['DB_NAME']);
			if ($link->query("SELECT * FROM `ti0s_configs` WHERE 1")) {
				echo '<div class="list-group-item list-group-item-info">系统检测到你已安装过Ti0sCTF-X OJ实训平台</div>
			<div class="list-group-item">
				<a href="?do=6" class="btn btn-block btn-info">跳过安装</a>
			</div>';
			}else{
				echo '<p align="right"><a class="btn btn-primary btn-block" href="?do=4">创建数据表>></a></p>';
				
			}
		}
	} else {
		$db_host = isset($_POST['db_host']) ? $_POST['db_host'] : NULL;
		$db_user = isset($_POST['db_user']) ? $_POST['db_user'] : NULL;
		$db_pwd = isset($_POST['db_pwd']) ? $_POST['db_pwd'] : NULL;
		$db_name = isset($_POST['db_name']) ? $_POST['db_name'] : NULL;

		if ($db_host == null ||  $db_user == null || $db_pwd == null || $db_name == null) {
			echo '<div class="alert alert-danger">保存错误,请确保每项都不为空<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>';
		} else {
			$config = "
<?php
/*数据库配置*/
define('SQLCONFIGS',[
	'DB_HOST'    => '{$db_host}', //数据库服务器
	'DB_USER'    => '{$db_user}', //数据库用户名
	'DB_PASS'    => '{$db_pwd}', //数据库密码
	'DB_NAME'    => '{$db_name}' //数据库库名
]);
?>";
$link=new mysqli($db_host,$db_user,$db_pwd,$db_name);
if(mysqli_connect_errno()){
	die('<div class="alert alert-danger">请先填写好数据库信息并保存后再安装！<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>');
}
$link->set_charset("utf8");
			if (file_put_contents('../config.php', $config)) {
				echo '<div class="alert alert-success">数据库配置文件保存成功！</div>';
				if ($link->query("SELECT * FROM `ti0s_configs` WHERE 1")) {
					echo '<div class="list-group-item list-group-item-info">系统检测到你已安装过Ti0sCTF-X OJ实训平台</div>
				<div class="list-group-item">
					<a href="?do=6" class="btn btn-block btn-info">跳过安装</a>
				</div>';
				} else {
					echo '<p align="right"><a class="btn btn-primary btn-block" href="?do=4">创建数据表>></a></p>';
					
				}

			} else {
				echo '<div class="alert alert-danger">保存失败，请确保网站根目录有写入权限<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>';
			}

		}
	}
	?>
	</div>
</div>
<?php } elseif ($do == '4') {
	?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">创建数据表</h3>
	</div>
<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
	<span class="sr-only">70%</span>
  </div>
</div>
	<div class="panel-body">
<?php

	include_once '../config.php';

	if (!SQLCONFIGS['DB_USER'] || !SQLCONFIGS['DB_PASS']|| !SQLCONFIGS['DB_NAME']) {
		echo '<div class="alert alert-danger">请先填写好数据库并保存后再安装！<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>';
	} else {
		$sql = file_get_contents("install.sql");
		$sql = explode(';', $sql);
		$link=new mysqli(SQLCONFIGS['DB_HOST'],SQLCONFIGS['DB_USER'],SQLCONFIGS['DB_PASS'],SQLCONFIGS['DB_NAME']);
		if(mysqli_connect_errno()){
			die('<div class="alert alert-danger">请先填写好数据库信息并保存后再安装！1<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>');
		}
		$link->set_charset("utf8");
		$link->query("set sql_mode = ''");
		$t = 0;
		$e = 0;
		$error = '';
		for ($i = 0; $i < count($sql); $i++) {
			if ($sql[$i] == '') {
				continue;
			}

			if ($link->query($sql[$i])) {
				++$t;
			} else {
				++$e;
				$error .= $sql[$i] . '<br/>';
			}
		}
	}
	if ($e == 0) {
		echo '<div class="alert alert-success">安装成功！<br/>SQL成功' . $t . '句/失败' . $e . '句</div><p align="right"><a class="btn btn-block btn-primary" href="index.php?do=5">下一步>></a></p>';
	} else {
		echo '<div class="alert alert-danger">安装失败<br/>SQL成功' . $t . '句/失败' . $e . '句<br/>错误信息：' . $error . '</div><p align="right"><a class="btn btn-block btn-primary" href="index.php?do=4">点此进行重试</a></p>';
	}
	?>
	</div>
</div>

<?php } elseif ($do == '5') {
	?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">安装完成</h3>
	</div>
<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
	<span class="sr-only">100%</span>
  </div>
</div>
	<div class="panel-body">
	<?php
	@file_put_contents("install.lock", '安装锁');
	if (!file_exists('install.lock')) {
		@file_put_contents("install.lock", '安装锁');
		echo '<font color="#FF0033">你的空间不支持本地文件读写，请自行在install/ 目录建立 install.lock 文件！</font>';
		echo '<font color="#FF0033">否则会照成重复安装界面，请注意！创建文件后请重新刷新本页！</font>';
	}else{
	echo '
	<div class="alert alert-info"><font color="green">安装完成！管理账号和密码是:admin/ti0sctf</font><br/><br/><a href="../">>>网站首页</a>｜<a href="https://www.ti0s.com/">>>Ti0s\'s Blog</a><hr/>更多设置选项请登录后台管理进行修改。<br/><br/></div>';
	}
	?>
	</div>
</div>

<?php } elseif ($do == '6') {
	?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">安装完成</h3>
	</div>
<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
	<span class="sr-only">100%</span>
  </div>
</div>
	<div class="panel-body">
	<?php
	@file_put_contents("install.lock", '安装锁');
	if (!file_exists('install.lock')) {
		@file_put_contents("install.lock", '安装锁');
		echo '<font color="#FF0033">你的空间不支持本地文件读写，请自行在install/ 目录建立 install.lock 文件！</font>';
		echo '<font color="#FF0033">否则会照成重复安装界面，请注意！创建文件后请重新刷新本页！</font>';
	}else{
	echo '
	<div class="alert alert-info"><font color="green">安装完成！管理账号和密码是:admin/ti0sctf</font><br/><br/><a href="../">>>网站首页</a>｜<a href="https://www.ti0s.com/">>>Ti0s\'s Blog</a><hr/>更多设置选项请登录后台管理进行修改。<br/><br/></div>';
	}
	?>
	</div>
</div>

<?php }?>

</div>
</body>
</html>
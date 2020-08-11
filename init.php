<?php
// error_reporting(0);
header("Content-type: text/html; charset=utf-8"); 
date_default_timezone_set('Asia/Shanghai');
session_start();
include 'config.php';
# 数据库连接类
class Database{
    private static $database;
    public static function getConnection(){
        if ( !self::$database ){
            self::$database = new mysqli( SQLCONFIGS['DB_HOST'], SQLCONFIGS['DB_USER'], SQLCONFIGS['DB_PASS'], SQLCONFIGS['DB_NAME'] );
            self::$database->set_charset("utf8");
        }
        return self::$database;
    }
}

# 检测数据库连接状态
$link=@Database::getConnection();
if ($link->connect_errno){
   die("连接数据库失败，请重试！");
}

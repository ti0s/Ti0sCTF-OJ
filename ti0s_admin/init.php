<?php 
#开启session
session_start();
if(!isset($_SESSION['admin']) and !$_SESSION['admin']){
    die('Go Out!');
}
include '../init.php';
define( 'INITIALIZED', true );
?>

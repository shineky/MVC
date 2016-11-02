<?php
header("content-type:text/html;charset=utf-8");

// 导入配置文件 + model类
require './configs/config.php';

// 自动加载类
function __autoload($classname)
{
	if (file_exists("./model/{$classname}.class.php")) {
		require "./model/{$classname}.class.php";
	}elseif (file_exists("./controllers/{$classname}.class.php")) {
		require "./controllers/{$classname}.class.php";
	}else{
		header("HTTP/1.0 404 not found");
		echo "<h1>404 NOT FOUND</h1>";
		exit;
	}
}

// 获取用户参数
// 获取控制器名 类名
$c = (!empty($_GET['c'])?$_GET['c']:'Index');

// 获取操作名   方法名
$a = (!empty($_GET['a'])?$_GET['a']:'index');

// 拼装类名
$classname = $c.'Controller';
// 实例化控制器
$controller = new $classname();

// 调用控制器中的方法
$controller->$a();
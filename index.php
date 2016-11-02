<?php
header("content-type:text/html;charset=utf-8");

// 导入配置文件 + model类
require './configs/config.php';

require './models/Model.class.php';

require './controllers/IndexController.class.php';
require './controllers/GoodsController.class.php';
require './controllers/UserController.class.php';

// 获取用户参数
// 获取控制器名 类名
$c = (!empty($_GET['c'])?$_GET['c']:'Index');

// 获取操作名   方法名
$a = (!empty($_GET['a'])?$_GET['a']:'Index');

// 拼装类名
$classname = $c.'Controller';
// 实例化控制器
$controller = new $classname();

// 调用控制器中的方法
$controller->$a();
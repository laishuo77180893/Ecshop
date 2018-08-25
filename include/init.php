<?php
defined('ACC')||exit('ACC Denied');

define('ROOT',str_replace('\\','/',dirname(__DIR__)).'/');
define('DEBUG',true);

// require(ROOT.'./include/conf.class.php');//配置文件开放的接口
// require(ROOT.'./include/conf.inc.php');//引入配置文件
// require(ROOT.'./include/log.class.php');//日志文件
require(ROOT.'./include/lib_base.php');//转译文件（参数过滤）
// require(ROOT.'./include/db.class.php');//数据库类（只定义方法）
// require(ROOT.'./include/mysql.class.php');//数据库子类（方法和实际内容）
// require(ROOT.'./model/Model.class.php');//业务模型类文件（定义方法）
// require(ROOT.'./model/TestModel.class.php');//业务模型类子文件（）


//自动加载的方法；
function __autoload($class){
	if(strtolower(substr($class,-5))=='model'){
		require(ROOT.'model/'.$class.'.class.php');
	}else if(strtolower(substr($class,-4))=='tool'){
		require(ROOT.'libary/'.$class.'.class.php');
	}else{
		require(ROOT.'include/'.$class.'.class.php');
	}
}


//设置报错级别   初始化文件：file init.php作用（框架初始化）

if(defined('DEBUG'))
{
	error_reporting(E_ALL); 
}else{
	error_reporting(0);
}

//参数过滤，用递归的方式过滤转义，$_GET,$_POST,$_COOKIE;
$_GET = _addslashes($_GET);
$_POST = _addslashes($_POST);
$_COOKIE = _addslashes($_COOKIE);






















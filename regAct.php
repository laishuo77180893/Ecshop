<?php 
header("content-type:text/html;charset=utf-8");
define('ACC',true);
require('./include/init.php');

//注册页面
	// $_POST = json_decode($_POST);

	$user = new UserModel();

//自动检测
	if(!$user->_validate($_POST)){
	// $msg = implode('<br/>',$user->getErr());
	// include(ROOT.'./view/front/msg.html');
	exit;
	}

//检测是否重名
	if($user->checkUser($_POST['username'])){
	echo '用户名已经存在';
	// include(ROOT.'./view/front/msg.html');
	exit;
	}

	$data = $user->_autoFill($_POST);//自动填充
	$user->_facade($data);//自动过滤


	if($user->reg($data)){
		echo '用户注册成功';

	}else{
		echo '用户注册失败';
	}


// include(ROOT.'./view/front/msg.html');



 ?>
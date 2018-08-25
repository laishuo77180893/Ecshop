<?php 

//验证登录

define('ACC',true);
require('./include/init.php');

if(isset($_POST['act'])){
	$u = $_POST['username'];
	$p = $_POST['password'];


	$user = new UserModel();
	$user->_validate($_POST);

	$row = $user->checkUser($u,$p);

	if(empty($row)){
		echo '登录失败';

	}else{
		echo '登录成功';
		session_start();
		$_SESSION = $row;

		if(isset($_POST['remember'])){
				setcookie('remuser',$u,time()+3600*24*14);
		}else{
			    setcookie('remuser','',0);
		}
	}

	// include(ROOT.'view/front/msg.html');
	exit;

}else{
	$remuser = isset($_COOKIE['remuser'])?$_COOKIE['remuser']:'';
	// include(ROOT.'view/front/denglu.html');
}


























 ?>
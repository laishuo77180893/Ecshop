<?php 
header("content-type:text/html;charset=utf-8");
define('ACC',true);

require('include/init.php');

require(ROOT.'libary/UpTool.class.php');

$uptool = new UpTool();

if($uptool->up('pic')){
	echo '上传成功了<br/>';
}else{
	echo $uptool->getErr();
}




































 ?>
<?php 

define('ACC',true);
require('../include/init.php');
//第一步：接受数据
//print_r($_POST);测试方法


//第二步：检测数据是否合法
$data = array();
if(empty($_POST['cat_name'])){
	exit('栏目名不能为空');
}
$data['cat_name'] = $_POST['cat_name'];

$data['parent_id'] = $_POST['parent_id'];

$data['intro'] = $_POST['intro'];

$cat_id = $_POST['cat_id']+0;
//第三步：实例化model，并调用model里面的方法
$cat = new CatModel();

if($cat->update($data,$cat_id)){
	echo '修改成功';
	exit;
}else{
	echo '修改失败';
	exit;
}

















 ?>
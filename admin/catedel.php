<?php 


define('ACC',true);
require('../include/init.php');


$cat_id = $_GET['cat_id']+ 0 ;

$cat = new CatModel();


if($cat->delete($cat_id)){
	echo '删除成功';
}else{
	echo '删除失败';
}







































 ?>

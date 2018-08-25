<?php 
//栏目分页面
define('ACC' true);
include('./include/init.php');


session_start();

$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']+0:0;

$cat = new CatModel();
$category = $cat->find($cat_id);

if(empty($category)){
	header('location:index.php');
	exit;
}

//取出树状导航

$cats = $cat->select();//获取所有的栏目
$sort = $cat->getCatTree($cats,0,1);


//取出面包屑导航

$nav = $cat->getTree($cat_id);

//取出栏目下商品

$goods = new GoodsModel();
$goodslist = $goods->catGoods($cat_id);










include(ROOT.'view/front/lanmu.html');
























 ?>
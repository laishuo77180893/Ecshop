<?php 

define('ACC',true);

require('../include/init.php');

//实例化一个类，调用model类里面的select()方法；循环显示在view上；
$goods = new GoodsModel();
$goodslist = $goods->getGoods();

include(ROOT.'view/admin/templates/goodslist.html');























 ?>
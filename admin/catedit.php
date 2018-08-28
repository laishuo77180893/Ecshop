<?php 
header("content-type:text/html;charset=utf-8");

define('ACC',true);
require('../include/init.php');


$cat_id = $_GET['cat_id']+0;
$cat = new CatModel();
$catinfo = $cat ->find($cat_id);



$catlist = $cat ->select();
$catlist = $cat ->getCatTree($catlist);





include(ROOT.'view/admin/templates/catedit.html');
























 ?>
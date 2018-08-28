<?php 


define('ACC',true);

require('../include/init.php');

//调用Model
$cateModel = new CatModel();
$catlist = $cateModel->select();
$catlist = $cateModel->getCatTree($catlist);
include(ROOT.'./view/admin/templates/catelist.html');


 

















 ?>
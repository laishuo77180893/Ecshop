<?php 
header("content-type:text/html;charset=utf-8");
define('ACC',true);
require('../include/init.php');


// $data['goods_sn'] = trim($_POST['goods_sn']);
// $data['cat_id'] = $_POST['cat_id'];
// $data['shop_price'] = $_POST['shop_price'];
// $data['goods_desc'] = $_POST['goods_desc'];
// $data['goods_weight'] = $_POST['goods_weight'] * $_POST['weight_unit'];
// $data['is_best'] = isset($_POST['is_best'])?1:0;
// $data['is_new'] = isset($_POST['is_new'])?1:0;
// $data['is_hot'] = isset($_POST['is_hot'])?1:0;
// $data['is_delete'] = isset($_POST['is_delete'])?1:0;
// $data['is_on_sale'] = isset($_POST['is_on_sale'])?1:0;
// $data['goods_brief'] = trim($_POST['goods_brief']);
// $data['add_time'] = time();


$goods = new GoodsModel();
$_POST['goods_weight'] *=$_POST['weight_unit'];
// $data = array();//声明是一个数组,等待接收$_POST提交过来的；
$data = $goods->_facade($_POST);//数据传过来，先自动过滤
$data = $goods->_autoFill($data);//再自动填充


//自动生成货号
if(empty($data['goods_sn'])){
	$data['goods_sn'] = $goods->createSn(); 
}



if(!$goods->_validate($data)){
	echo '没有通过检验';
	print_r($goods->getErr());
	exit;
}




//上传图片
$uptool = new UpTool();


// $uptool->setExt('jpg');
// $uptool->setSize(2);

$ori_img = $uptool->up('ori_img');

if($ori_img){
	$data['ori_img'] = $ori_img;
}


if($ori_img){

		//如果$ori_img上传成功，再次生成中等大小缩略图  300*400
		//根据原始地址  定  中等图地址
		//例:aa.jpeg => goods_aa.jpeg
		
		$ori_img = ROOT . $ori_img;//加上绝对路径
		$goods_img = dirname($ori_img) . '/goods_' . basename($ori_img);
		if(ImageTool::thumb($ori_img,$goods_img,300,400)){
			$data['goods_img'] = str_replace(ROOT,'',$goods_img);
		}

		//再次生成浏览时的缩略图 160*200
		//定好缩略图地址
		//例:aa.jpeg => thumb_aa.jpeg

		$thumb_img = dirname($ori_img) . '/thumb_' . basename($ori_img);
		if(ImageTool::thumb($ori_img,$thumb_img,160,220)){
			$data['thumb_img'] = str_replace(ROOT,'',$thumb_img);
		}
    }

if($goods->add($data)){
	echo '商品发布成功';
}else{
	echo '商品发布失败';
}






























 ?>

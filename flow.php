<?php 

define('ACC',true);
require('./include/init.php');

//设置一个动作参数，判断用户想干什么，比如是下订单/写地址/提交/清空购物车等
$act = isset($_GET['act'])?$_GET['act']:'buy';

$cart = CartTool::getCart();//获取购物车实例
$goods = new GoodsModel();
if($act == 'buy'){	 //这是把商品加到购物车，跳转到购物车页面
	$goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;
	$num = isset($_GET['num'])?$_GET['num']+0:1;
	if($goods_id){   //$goods_id为真，是想把商品放到购物车里
		$g = $goods->find($goods_id);//去数据库查询该商品
		if(!empty($g)){   //有此商品

			//需要判断此商品，是否在回收站
			//此商品是否已经下架
			//此商品库存够不够

			if($g['is_delete'] == 1||$g['is_on_sale'] == 0){
				$msg = '此商品已经下架';
				include(ROOT.'view/front/msg.html');
				exit;
			}

			//先把商品加到购物车
			$cart->addItem($goods_id,$g['goods_name'],$g['shop_price'],$num);

			//把购物车商品信息读取出来
			$items = $cart->all();

			//判断购物车某商品数量是否超过数据库库存的数量
			if($items[$goods_id]['num']>$g['goods_number']){
				$cart->delNum($goods_id,$num);
				$msg = '库存不足';
				include(ROOT.'view/front/msg.html');
				exit;
			}
		}
	}
	$items = $cart->all();

	if(empty($items)){   //如果购物车为空，返回首页
		header('location:index.php');
		exit;
	}
	//把购物车中的商品详细信息取出
	$items = $goods->getCartGoods($items);//添加商品图片和市场价格

	$total = $cart->getPrice();//获取购物车中的总价格

	$market_total = 0.0;       //市场价格总价格
	foreach ($items as $v) {
		$market_total += $v['market_price']*$v['num'];
	}
	$discount = $market_total - $total;
	$rate = round(100*$discount/$total,2);
	include(ROOT.'./view/front/jiesuan.html');
}else if($act == 'clear'){
	$cart->clear();
	$msg = '清空购物车';
	include(ROOT.'view/front/msg.html');
}else if($act == 'tijiao'){   //跳转到填写地址页面
	$items = $cart->all();
	//把购物车中的商品详细信息取出
	$items = $goods->getCartGoods($items);

	$total = $cart->getPrice();//获取购物车中的总价格

	$market_total = 0.0;       //市场价格总价格
	foreach ($items as $v) {
		$market_total += $v['market_price']*$v['num'];
	}
	$discount = $market_total - $total;
	$rate = round(100*$discount/$total,2);
	include(ROOT.'view/front/tijiao.html');
}else if($act == 'done'){
	/*
		订单入库，最重要的一个环节
		从表单读取送货地址，手机等信息
		写入orderinfo表
	*/	


	//自动检测	
	$OI = new OIModel();
	if(!$OI->_validate($_POST)){    //如果数据检验没有通过，报错退出
		$msg = implode(','$OI->getErr());
		include(ROOT.'view/front/msg.html');
		exit;
	}
	//自动过滤
	$data = $OI->_facade($_POST);

	//自动填充
	$data = $OI->_autoFill($data);

	//写入总金额
	$totalprice = $data['order_amount'] = $cart->getPrice();

	//写入用户信息，从用户登录的session读取
	
	$data['username'] = isset($_SESSION['username'])?$_SESSION['username']:'匿名';
	$data['user_id'] = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;

	//写入订单号
	$order_sn = $data['order_sn'] = $OI->orderSn();


	if(!$OI->add($data)){
		$msg = '下订单失败';
		include(ROOT.'view/front/msg.html');
		exit;
	}

	//获取刚刚产生的order_id的值
	$order_id = $OI->insert_id();

	/*
		要把订单商品写入数据库
		1个订单里面有N个商品，我们可以循环写入ordergoods表
	*/
	$items = $cart->all();

	$OG = new OGModel();
	$cnt = 0;
	foreach ($items as $k => $v) {
			$data = array();
			$data['order_sn'] = $order_sn;
			$data['order_id'] = $order_id;
			$data['goods_id'] = $items[$k];
			$data['goods_name'] = $v['name'];
			$data['goods_number'] = $v['num'];
			$data['shop_price'] = $v['price'];
			$data['subtotal'] = $v['num'] * $v['price'];

			if($OG->addOG($data)){
				$cnt += 1; //插入一条og成功,$cnt+1
				//因为，1个订单有N条商品，必须N条商品，都插入成功，才算订单成功！
			}
		}

		if(count($items)!==$cnt){
			$OI->invoke($order_id);
			$msg = '下订单失败';
			include(ROOT.'view/front/msg.html');
			exit;
		}

		//下订单成功，清空购物车
		$cart->clear();
		include(ROOT.'view/front/order.html');

}






















/*
	下订单
	计算在线支付的md5值
	v_amount v_monkeytype v_oid v_mid v_url key

*/
	$v_url = '回调网站';//与前端页面的回调网址一致
	$key = '密钥';
	$md5info = strtoupper(md5($total . 'CNY' . $data['order_id'] . $data['order_sn'] . $v_url . $key));

































 ?>
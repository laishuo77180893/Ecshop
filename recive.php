<?php 


//支付完成后第三方支付平台会往回调网址发送了一段支付凭据


print_r($_POST);


//自己计算出来的md5info
$key = '密钥';
$md5info = md5($_POST['v_oid'] . $_POST['v_pstatus'] . $_POST['v_amount'] . $_POST['v_monkeytype'] . $key);
$md5info = strtoupper($md5info);

if($md5info !== $_POST['v_md5str']){
	echo '支付失败';
}

echo $_POST['v_oid'] . '支付成功';















 ?>
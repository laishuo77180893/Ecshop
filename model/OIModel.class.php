<?php 

defined('ACC')||exit('ACC deny');

class OIModel extends Model{
	protected $table = 'orderinfo';
	protected $pk = 'order_id';//$pk是主键的意思；
	protected $fields = array( 'order_id','order_sn','use_id','username','zone','address','zipcode','reciver','email','tel','mobile','best_time','add_time','order_amount','pay');

	protected $_auto = array(
			array('add_time','function','time')
	);

	protected $_valid = array(
		array('reciver',1,'必须填写个人姓名','require'),
		array('email',1,'email非法','email'),		
		array('pay',1,'必须选择支付方式','in','0,1')
	);

	public function orderSn(){
		$sn = 'OI'.date('Ymd').mt_rand(10000,99999);
		$sql = 'select count(*) from ' . $this->table . 'where order_sn =' . "'$sn'";
		return $this->db->getOne($sql)?$this->orderSn():$sn;
	}

	public function invoke($order_id){
		$this->delete($order_id);   //先删除订单

		$sql = 'delete from ordergoods where order_id = ' . $order_id; //再删除订单对应的商品

		$this->db->query($sql);
	}




}


































 ?>
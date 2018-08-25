<?php 

defined('ACC')||exit('ACC deny');

class OGModel extends Model{
	protected $table = 'ordergoods';
	protected $pk = 'og_id';//$pk是主键的意思；

	//把订单的商品写入ordergoods表
	public function orderOG($data){
		if($this->add($data)){			//添加购物车商品添加成功后
			$sql = 'update goods set goods_number - ' . $data['goods_number'] . ' where goods_id = ' . $data['goods_id']; 
			return $this->db->query($sql);
		}
	}















































 ?>
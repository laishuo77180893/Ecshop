<?php 

defined('ACC')||exit('ACC deny');

class GoodsModel extends Model{
	protected $table = 'goods';
	protected $pk = 'goods_id';//$pk是主键的意思；
	protected $fields = array( 'goods_id','goods_sn','cat_id','brand_id','goods_name','shop_price','market_price','goods_number','click_count','goods_weight','goods_brief','goods_desc','thumb_img','goods_img','ori_img','is_on_sale','is_delete','is_best','is_new','is_hot','add_time','last_update');

	protected $_auto = array(
		array('is_hot','value',0),
		array('is_best','value',0),
		array('is_new','value',0),
		array('add_time','function','time')
	);

	protected $_valid = array(
		array('goods_name',1,'必须有商品名','require'),
		array('cat_id',1,'栏目id必须是整形值','number'),		
		array('is_new',0,'is_new只能是0或1','in','0,1'),
		array('goods_brief',2,'商品简介只能在10到100字符','length','10,100')
	);

	//回收站功能'is_delete'=>1时候加入回收站
	public function trash($id){
		return $this->update(array('is_delete'=>1),$id);
	}

	//显示商品列表'is_delete'=>0时候显示出来
	public function getGoods(){
		$sql = 'select * from goods where is_delete = 0';
		return $this->db->getAll($sql);
	}

	public function getTrash(){
		$sql = 'select * from goods where is_delete = 1';
		return $this->db->getAll($sql);
	}

	//自动生成商品货号
	public function createSn(){
		$sn = 'BL' . date('Ymd') . mt_rand(10000,99999);
		$sql = 'select count(*) from '. $this->table . " where goods_sn='" . $sn . "'";
		return $this->db->getOne($sql)?$this->createSn():$sn;
	}

	/*
		取出指定条数的新品
	*/
	public function getNew($n = 5){
		$sql ='select goods_id,goods_name,shop_price,goods_img from ' . $this->table . " where is_new = 1 order by add_time limit 5";
		return $this->db->getAll($sql);
	}

	//取出栏目下的商品
	public function catGoods($cat_id){   
		$category = new CatModel();
		$cats = $category->select();//取出所有栏目来
		$sons = $category->getCatTree($cat,$cat_id);//取出给定栏目的子孙栏目

		$sub = array();

		if(!empty($sons)){
			foreach($sons as $v){
				$sub[] = $v['cat_id'];
			}
		}
		//目的是取出该栏目下的所有商品，但是父级栏目没有商品只能从下级栏目取，这里去获得该父级栏目下所有的子栏目商品的cat_id，然后在使用sql语句取出需要的商品
		$in = implode(',',$sub);
		
		$sql = 'select goods_id,goods_name,shop_price,thumb_img from ' . $this->table . ' where cat_id in (' . $in . ') order by add_time limit 5';

		return $this->db->getAll($sql);
	}	

	/*
		获取购物车中商品的详细信息到数据库进行查询出来
		params array $items购物车中的商品数组
		return  商品数组详细信息

	*/
	public function getCartGoods($items){
		foreach($items as $k=>$v){   //循环购物车中的商品，每循环一个，到数据库查一下对应的详细信息
			$sql = 'select goods_id,goods_name,thumb_img,shop_price,market_price from ' .$this->table. ' where goods_id = ' . $k;
			$row = $this->getRow($sql);

			$items[$k]['thumb_img'] = $row['thumb_img'];
			$items[$k]['market_price'] = $row['market_price'];
		}
		return $items;
	}



}






















 ?>
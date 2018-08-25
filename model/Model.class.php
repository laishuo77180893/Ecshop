<?php 
defined('ACC')||exit('ACC Denied');
class Model{
    protected $table = NULL;      //model所控制的表
	protected $db = NULL;         //是引入的mysql对象
	protected $pk = '';           //$pk  其实就是primary key的意思
	protected $fields = array();  //自动过滤
	protected $_auto = array();   //自动生成
	protected $_valid = array();  //自动验证
	protected $error = array();



	public function __construct(){     
		$this->db = mysql::getIns();  //将$db属性赋值为mysql类下面的getIns()方法，进行实例化一次
	}

	// final protected function sendByAjax($data=array(), $end=true) {
	// 	if (is_array($data)) {
	// 		$return_data = array_merge($this->ajaxData, $data);
	// 	} else {
	// 		$return_data = $data;
	// 	}
	// 	echo json_encode($return_data);
	// 	$end && exit();
	// }
	
	public function table($table){
		$this->table = $table;
	}
	/*
		负责把传来的数组清除掉不用的单元
		留下与表的字段对应的单元
		思路：循环数组，分别判断其key，是否是表的字段
		自然，表的字段也要先有的字段
		表的字段可以desc表名来分析
		也可以手动写好，以tp为例，两者都行
	*/
	public function _facade($array = array()){
		$data = array();
		foreach($array as $k=>$v){              //判断$k是否有表的字段
			if(in_array($k,$this->fields)){
				$data[$k] = $v;
			}
		}return $data;
	}

	/*
		自动填充功能
		负责把表中需要值，而$_POST又没有传过来的字段，赋上值
		比如$_POST里面没有add_time，即商品时间，
		自动把time()的返回值赋过来
	*/	
	public function _autoFill($data){
		foreach($this->_auto as $k=>$v){
			if(!array_key_exists($v[0], $data)){
				switch($v[1]){
					case'value':
					$data[$v[0]] = $v[2];
					break;

					case 'function':
					$data[$v[0]] = call_user_func($v[2]);//call_user_func 回调函数，回调time()函数；
					break;
				}
			}
		}return $data;
	}

	/*
		格式$this->_valid = array(array('验证的字段名',0/1/2(验证场景),'报错提示',require/in(某几种情况)/between(范围)/length(某个范围)'));

		array('goods_name',1,'必须有商品名','require'),
		array('cat_id',1,'栏目id必须是整形值','number'),		
		array('is_new',0,'is_new只能是0或1','in','0,1'),
		array('goods_brief',2,'商品简介只能在10到100字符','length','10,100')

	*/	
	public function _validate($data){
		if(empty($this->_valid)){
			return true;
		}

		$this->error = array();

		foreach($this->_valid as $k=>$v){
			switch ($v[1]) {
				case 1:
						if(!isset($data[$v[0]])){
							$this->error[] = $v[2];
							return false;
						}

						if(!isset($v[4])){
							$v[4] = '';
						}

						if(!$this->check($data[$v[0]],$v[3],$v[4])){
							$this->error[] = $v[2];
							return false;
						}

						break;
				case 0:
						if(isset($data[$v[0]])){
							if(!$this->check($data[$v[0]],$v[3],$v[4])){
								$this->error[] = $v[2];
								return false;
							}
						}
				case 2:
						if(isset($data[$v[0]])&&!empty($data[$v[0]])){
							if(!$this->check($data[$v[0]],$v[3],$v[4])){
								$this->error[] = $v[2];
								return false;
							}
						}						
				default:
					break;
				}
			}
		return true;
		}

	public function check($value,$rule='',$parm=''){
			switch ($rule) {
				case 'require':
						return !empty($value);
				case 'number':
						return is_numeric($value);			
				case 'in':
						$tmp = explode(',',$parm);
						return in_array($value,$tmp);
				case 'length':
						list($min,$max) = explode(',',$parm);
						return strlen($value) >= $min && strlen($value) <= $max;						
				case 'between':
						list($min,$max) = explode(',',$parm);
						return $value >= $min && $value <= $max;
				case 'email':
						return (filter_var($value,FILTER_VALIDATE_EMAIL)!==false);	
				default:
						return false;
			}
		}

	//设置报错方法
	public function getErr(){
		return $this->error;
	}


	//增加数据方法
	public function add($data){
		return $this->db->autoExecute($this->table,$data);
	}
	//删除数据方法
	public function del($id){
		$sql = 'delete from '.$this->table.' where '.$this->pk.' = '.$id;
		if($this->db->query($sql)){
			return $this->db->affected_rows();
		}else{
			return false;
		}
	}
	//改数据方法
	public function update($data,$id){
		$sql = $this->db->autoExecute($this->table,$data,'update',' where '.$this->pk.' = '.$id);
		if($sql){
			return $this->db->affected_rows();
		}else{
			return false;
		}
	}
	//查询数据的方法
	public function select(){
		$sql = 'select * from '.$this->table;
		return $this->db->getAll($sql);
	}
	//查询一行数据
	public function find($id){
		$sql = 'select * from '.$this->table.' where '.$this->pk.' = '.$id;
		return $this->db->getRow($sql);
	}
	//返回最新自增列的值
	public function insert_id(){
		return $this->db->insert_id();
	}


	// public static function json($code,$message="",$data=array()){ 
 //   		$result=array( 
 //    		'code'=>$code, 
 //    		'message'=>$message, 
 //    		'data'=>$data
 //   		); 
 //   		//输出json 
 //   		echo json_encode($result); 
 //   		exit; 
 //  } 
}














































 ?>
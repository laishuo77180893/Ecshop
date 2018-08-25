<?php 
defined('ACC')||exit('ACC Denied');
class conf{

	protected static $ins = null;
	protected $data = array();
	final private function __construct(){      //当类被实例化对象的时候自动调用（构造方法）；
		include(ROOT.'./include/conf.inc.php');
		//一次性把配置文件的信息全部读过来，赋给（$data）data属性；这样以后就不用管配置文件了；
		$this->data = $_CFG;
	}
	final private function __clone(){

	}
	public static function getIns(){
		if(self::$ins instanceof self){
			return self::$ins;
		}else{
			self::$ins = new self();//静态的内部调用都是用self;
			return self::$ins;
		}
	}
	//当外部调用读取属性值时，由于私有属性没有办法被调用，只能使用魔术方法进行调用读取；
	public  function __get($key){
		if(array_key_exists($key,$this->data))
		{
			return $this->data[$key];
		}else{
			return null;
		}
	}
    //当配置文件增加选项时，利用魔术方法进行设置增加；
	public  function __set($key,$value){
		$this->data[$key] = $value;
	}

}


















































 ?>
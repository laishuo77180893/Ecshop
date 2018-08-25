<?php 

defined('ACC')||exit('ACC deny');

class UserModel extends Model{
	protected $table = 'user'; 
	protected $pk = 'user_id';
	protected $field = array('user_id','username','email','password','regtime','lastlogin');
	protected $_valid = array(
		array('username',1,'必须有用户名','require'),
		array('username',0,'用户名必须在4-16个字节','length','4,16'),		
		array('email',1,'email必须存在','email'),
		array('password',1,'密码不能为空','require')

	);
	protected $_auto = array(
		array('regtime','function','time')
	);

	//加密方法
	protected function encPassword($p){
		return md5($p);
	}
	//传过来的数据调用加密方法进行加密
	public function reg($data){
		if($data['password']){
			$data['password'] = $this->encPassword($data['password']);
		}
		return $this->add($data);
	}



	// //注册是否用户名重复检验
	// public function check($username){
	// 	$sql = 'select count(*) from ' . $this->table . " where username = '" . $username . "'";
	// 	return $this->db->getOne($sql);
	// }

	// //登录时候校验

	// public function checkUser($username,$password){
	// 	$sql = 'select username,password from ' . $this->table . " where username = '" . $username . "'";
	// 	$row = $this->db->getRow($sql);//查出该条数据
	// 	if(empty($row)){
	// 		return false;
	// 	}
	// 	if($row['password']!=$this->encPassword($password)){
	// 		return false;
	// 	}
	// 	unset($row['psaaword']);
	// 	return $row;
	// }


















	//检测用户名信息
	public function checkUser($username,$password = ''){
		if($password==''){   //只传用户名的时候
			$sql = 'select count(*) from ' . $this->table . " where username='"  . $username . "'";//count(*)查询出的是记录的条数
		    return $this->db->getOne($sql);//mysql_fetch_row($rs),return $row[0];
		}else{               //用户名和密码都同时传过来
			$sql = 'select user_id,username,email,password from ' . $this->table . " where username = '" . $username ."'";
			$row = $this->db->getRow($sql);

			if(empty($row)){  //用户名错误直接返回
				return false;
			}
			if($row['password']!=$this->encPassword($password)){	//用户名存在但是密码错误也返回
				return false;
			}

			unset($row['psaaword']);
			return $row;
		}
		
	}

}



























 ?>

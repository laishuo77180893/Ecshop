<?php 


//单文件上传类

defined('ACC')||exit('ACC denied');



/*

上传文件
配置文件后缀
配置允许的大小
随机生成目录
随机生成文件名


获取文件后缀
判断文件的后缀
良好的报错支持

*/


class UpTool{
	protected $allowExt = 'jpg,jpeg,png,gif,psd';
	protected $maxSize = 2;     //1M为单位
	protected $file = NULL;     //准备储存上传文件的信息使用
	protected $errno = 0;       //错误代码
	protected $error = array(
		0=>'无错误',
		1=>'文件上传超出系统限制',
		2=>'文件上传大小超出网页表单页面',
		3=>'文件只有部分被上传',
		4=>'没有找到被上传',
		6=>'找不到临时文件夹',
		7=>'文件写入失败',
		8=>'不允许的文件后缀',
		9=>'文件大小超出类的允许范围',
		10=>'创建目录失败',
		11=>'移动失败'
	);

	/*
	$_FILES具体参数
	Array
	(
		[pic]=>Array            //pic  与前端页面的name值一致
				(
				[name]=>Winter.jpg
				[type]=>image/jpeg
				[tmp_name]=>D:\tmp\php6A2.tmp
				[error]=>0
				[size]=>105542
				)
	)
	*/



	public function up($key){   //前端页面传过来的$key  比如'pic'
		if(!isset($_FILES[$key])){
			return false;
		}

		$f = $_FILES[$key];
		if($f['error']){
			$this->errno = $f['error'];
			return false;
		}
		//获取后缀
		$ext = $this->getExt($f['name']);
		//判断后缀
		if(!$this->isAllowExt($ext)){
			$this->errno = 8;
			return false;
		}
		//判断大小
		if(!$this->isAllowSize($f['size'])){
			$this->errno = 9;
			return false;
		}
		//创建目录
		$dir = $this->mk_dir();
		if($dir == false){
			$this->errno = 10;
			return false;
		}
		//生成随机文件名
		$newname = $this->randName() . '.' . $ext;
		$dir = $dir . '/' . $newname;
		//移动存放
		if(!move_uploaded_file($f['tmp_name'],$dir)){
			$this->errno = 11;
			return false;
		}
		return str_replace(ROOT,'',$dir);

	}
	
	public function getErr(){
		return $this->error[$this->errno];
	}

	// public function setExt($exts){                   //允许的后缀
	// 	return $this->allowExt = $exts;
	// }

	// public function setSize($num){                   //允许的文件大小
	// 	return $this->maxSize = $num;
	// }
	
	protected function getExt($file){                      //读取后缀
		$tmp = explode('.',$file);
		return end($tmp);
	}
	
	protected function isAllowExt($ext){                 //检测文件后缀是否合法
		return in_array(strtolower($ext),explode(',',strtolower($this->allowExt)));
	}
	
	protected function isAllowSize($size){ 	             //检测文件大小是否合法
		return $size <= $this-> maxSize * 1024 * 1024;
	}
	
	protected function mk_dir(){                         //按日期创建目录的方法
		$dir = ROOT.'data/images/'.date('Ym/d');
		if(is_dir($dir)||mkdir($dir,0777,true)){
			return $dir;
		}else{
			return false;
		}
	}
	
	protected function randName($length = 6){
		$str = 'qwertyuiopasdfghjklzxcvbnm23456789';
		return substr(str_shuffle($str),0,$length);
	}
}

















































 ?>
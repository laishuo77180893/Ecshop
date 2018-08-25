<?php 

defined('ACC')||exit('ACC Denied');

/***

水印  缩略图   验证码

思路:
想要操作图片
先得把图片的大小类型和信息获得

水印：就是把指定的水印复制到目标上，并加透明效果

缩略图：就是把大图片复制到小尺寸画面上

***/

class ImageTool{
	//imageinfo 分析图片信息
	public static function imageInfo($image){
		//判断图片是否存在
		if(!file_exists($image)){
			return false;
		}
		/*
		获取图片信息
		getimagesize();	
		    [0] => 300
		    [1] => 200
		    [2] => 3
		    [3] => width="300" height="200"
		    [bits] => 8
		    [mime] => image/png

		*/
		$info = getimagesize($image);

		if($info==false){
			return false;
		}

		//此时info分析出来是一个数组
		$img['width'] = $info[0];
		$img['height'] = $info[1];
		$img['ext'] = substr($info['mime'],strpos($info['mime'],'/')+1);

		return $img;
	}
	/*
		加水印功能
		$dst       目标图片
		$water     水印图片
		$alpah     透明度
		$save      保存路径
		$pos       水印位置 
	*/
	public static function  water($dst,$water,$save=NULL,$alpah=50,$pos=2){
		
		//先保证2个图片存在
		if(!file_exists($dst)||!file_exists($water)){
			return false;
		}

		//首先保证水印比待操作图片还小
		$dinfo = self::imageInfo($dst);      //$dinfo  目标图片信息
		$winfo = self::imageInfo($water);    //$winfo  水印图片信息

		if($winfo['width']>$dinfo['width']||$winfo['height']>$dinfo['height']){
			return false;
		}

		//创建图片方法
		$dfunc = 'imagecreatefrom'.$dinfo['ext'];
		$wfunc = 'imagecreatefrom'.$winfo['ext'];
		if(!function_exists($dfunc)||!function_exists($wfunc)){
			return false;
		}
		//动态加载函数来创建画布
		$dim = $dfunc($dst);
		$wim = $wfunc($water);
		
		//根据水印位置  计算粘贴坐标
		switch ($pos) {
			case 0:
				$posx = 0;
				$posy = 0;
				break;
			case 1:
				$posx = $dinfo['width'] - $winfo['width'];
				$posy = 0;
				break;
			case 3:
				$posx = 0;
				$posy = $dinfo['height'] - $winfo['height'];
				break;	
			
			default: 
				$posx = $dinfo['width'] - $winfo['width'];
				$posy = $dinfo['height'] - $winfo['height'];
				break;
		}

		/*加水印  bool imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )*/
		imagecopymerge($dim, $wim, $posx, $posy, 0, 0, $winfo['width'], $winfo['height'], $alpah);

		//保存
		if(!$save){
			$save = $dst;
			unlink($dst);
		}
		$createfunc = 'image'. $dinfo['ext'];
		$createfunc($dim,$save);

		//销毁资源
		imagedestroy($dim);
		imagedestroy($wim);

		return true;

	}

	public static function thumb($dst,$save,$width=200,$height=200){
		//首先判断待处理的图片不存在
		$dinfo = self::imageInfo($dst);
		if($dinfo==false){
			return false;
		}

		//计算缩放比例
		$calc = min($width/$dinfo['width'],$height/$dinfo['height']);

		//创建原图画布
		$dfunc = 'imagecreatefrom'.$dinfo['ext'];
		$dim = $dfunc($dst);

		//创建小画布
		$tim = imagecreatetruecolor($width, $height);

		//白色填充小画布背景
		$white = imagecolorallocate($tim, 255, 255, 255);
		imagefill($tim, 0, 0, $white);

		/*复制并缩放
		bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		*/
		//压缩后的宽高；
		$dwidth = (int)$dinfo['width']*$calc;
		$dheight = (int)$dinfo['height']*$calc;

		$paddingx = (int)($width-$dwidth)/2;
		$paddingy = (int)($height-$dheight)/2;

		imagecopyresampled($tim, $dim, $paddingx, $paddingy, 0, 0, $dwidth, $dheight, $dinfo['width'], $dinfo['height']);

		//保存图片
		if(!$save){
			$save = $dst;
			unlink($dst);
		}
		$createfunc = 'image'. $dinfo['ext'];
		$createfunc($tim,$save);

		//销毁资源
		imagedestroy($tim);
		imagedestroy($dim);

		return true;
	} 

		//验证码
		public function capcahe($width=50,$height=25){
			//画布
			$im = imagecreatetruecolor($width,$height);
			
			//造颜色
			$gray = imagecolorallocate($im,220,220,220);

			//填充背景颜色
			imagefill($im, 0, 0, $gray);

			//造字体颜色
			$strcolor = imagecolorallocate($im, mt_rand(150,200), mt_rand(150,200), mt_rand(150,200));
			
			//造线条颜色
			$linecolor1 = imagecolorallocate($im, mt_rand(100,150), mt_rand(100,150), mt_rand(100,150));
			$linecolor2 = imagecolorallocate($im, mt_rand(100,150), mt_rand(100,150), mt_rand(100,150));
			$linecolor3 = imagecolorallocate($im, mt_rand(100,150), mt_rand(100,150), mt_rand(100,150));
			
			//画字体
			$str = 'qwertyuipasdfghjkzxcvbnm23456789QWERTYUPASDFGHJKZXCVBNM';
			$strfont = substr(str_shuffle($str),0,4);
			imagestring($im, 3, 8, 5, $strfont, $strcolor);


			//画线条
			
			imageline($im, mt_rand(0,50), 0, mt_rand(0,50), 25, $linecolor1);
			imageline($im, 0, mt_rand(0,50), 50, mt_rand(0,50), $linecolor2);
			imageline($im, mt_rand(0,50), 0, mt_rand(0,50), 25, $linecolor3);

			//保存图片
			header('content-type:image/png');
			imagepng($im);

			//销毁资源
			imagedestroy($im);

			return true;
		}
}










































 ?>
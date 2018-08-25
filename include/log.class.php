<?php 
defined('ACC')||exit('ACC Denied');
//思路：给定内容，写入文件（fopen,fwrite...）如果文件大于>1M,重新写一份（备份）
class Log{
	//创建一个常量代表日志文件的名称
	const LOGFILE = 'log';
    //写日志
	public static function write($cont)
	{   $cont = $cont."\r\n";
		$log = self::isBak(); //计算出日志文件的地址；
		$fh = fopen($log,'ab');
		fwrite($fh,$cont);
		fclose($fh);}
	//备份日志
	public static function Bak()
	{
		//把大于1m的文件改个名，在存起来；
		//改成年-月-日.bak这种形式；
		$log = ROOT.'./data/log/cur.log';
		$bak = ROOT.'./data/log/'.date('y-m-d').mt_rand(10000,99999).'.bak';
		return rename($log,$bak);
	}
	//读取并判断日志大小，返回大小
	public static function isBak()
	{
		$log = ROOT.'./data/log/cur.log';
		if(!file_exists($log))
		{
			touch($log);//touch在linux也有快速创建文件命令
			return $log;		
		}
		//如果文件存在，则判断大小；
		$size = filesize($log);
		if($size <= 1024*1024)
		{return $log;}
		//大于1M，进行备份，调用Bak();
		if(!self::Bak())
		{return $log;
		}else{
			touch($log);
			return $log;
		}
	}
}








































 ?>
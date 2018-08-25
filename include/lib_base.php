<?php 
defined('ACC')||exit('ACC Denied');
function _addslashes($arr)
{
	
	foreach($arr as $k=>$v)
	{
		if(is_string($v))        //如果是字符串，就转译
		{
			$arr[$k] = addslashes($v);
		}else if(is_array($v)){            //如果是数组，重新调用自身
			$arr[$k] = _addslashes($v);
		}
	}
	return $arr;

}







































 ?>
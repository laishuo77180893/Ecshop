<?php 
defined('ACC')||exit('ACC Denied');
//数据库类
//抽象类：里面既可以定义空的方法，也能定义有内容的方法；接口只能是空的方法，类去继承方法的内容；
abstract class db{
	public abstract function connect($h,$u,$p);//链接服务器 parms $h 服务器地址 $u 用户名 $p 密码 return bool
	
	public abstract function query($sql);//发送查询 $sql 发送的查询语句 return mixed bool/resource
	
	public abstract function getAll($sql);//查询多行数据 $sql select 语句  return bool/array
	
	public abstract function getRow($sql);//查询单行数据 $sql select 语句  return bool/array
	
	public abstract function getOne($sql);//查询单个数据 $sql select 语句  return bool/array
	
	public abstract function autoExecute($table,$data,$act='insert',$where='');
	//自动执行insert/update语句  $sql select型语句  return array/bool
	//autoExecute('user',array('username'=>'zhangsan','email'=>'zhangsan@163.com'),'insert');
	//将自动形成insert into user (username,email)values('zhangsan','zhangsan@163.com');
	







}
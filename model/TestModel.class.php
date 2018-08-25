<?php 
defined('ACC')||exit('ACC Denied');
class TestModel extends Model{

	protected $table = 'test';

	public function reg($data){
		return $this->db->autoExecute($this->table,$data,'insert');
	}
	public function select(){
		return $this->db->getAll('select * from '.$this->table);
	}

}







































 ?>
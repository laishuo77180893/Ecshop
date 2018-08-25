<?php 
defined('ACC')||exit('ACC Denied');

class CatModel extends Model{
	protected $table = 'category';

	public function add($data)
	{
		return $this->db->autoExecute($this->table,$data,'insert');
	}
	
	public function select(){
		$sql = 'select cat_id,cat_name,parent_id,intro from '.$this->table;
		return $this->db->getAll($sql);
	}
	
	//栏目子孙树
	public function getCatTree($arr,$id=0,$lev=0){
		$tree = array();
		foreach($arr as $v){
			if($v['parent_id']==$id){
				$v['lev']=$lev;
				$tree[] = $v;
				$tree = array_merge($tree,$this->getCatTree($arr,$v['cat_id'],$lev+1));
			}
		}
		return $tree;
	}

	// //栏目家谱树
	// public function getTree($id = 0){
	// 	$tree = array()
	// 	$cats = $this->select();//把整张表数据查出

	// 	while($id>0){
	// 		foreach($cats as $v){
	// 			if($v['cat_id']==$id){
	// 				$tree[] = $v;

	// 				$id = $v['parent_id'];
	// 				break;
	// 			}
	// 		}
	// 	}return array_reverse($tree);
	// }
	
	public function delete($cat_id){
		$sql = 'delete from '.$this->table.' where cat_id = '.$cat_id;
		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	public function find($cat_id){
		$sql = 'select * from '.$this->table.' where cat_id = '.$cat_id;
		return $this->db->getRow($sql);
	}

	public function update($data,$cat_id=0){
		$this->db->autoExecute($this->table,$data,'update',' where cat_id = '.$cat_id);
		return $this->db->affected_rows();
	}
}

















 ?>
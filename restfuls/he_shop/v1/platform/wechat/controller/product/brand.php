<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 取query
		$query = $this->createQuery([]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Brand, $query);
	
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取单条
	 */
	public function get_id(){
		
		// 取query
		$query = $this->createQueryId($this->createId(get('id')));
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Brand, $query);
		
		// 返回
		return $this->Data($result);
	}
}

?>
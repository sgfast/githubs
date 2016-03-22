<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_style(){
		
		// 取启用的number
		$query_number = $this->createQuery(["_id"=>0]);
		$result_number = &Mongo::query(DB::$main, COL::$Ad_Style, $query_number);
		$number = $result_number[0]->number;
		
		// 取query
		$query = $this->createQuery(["_id"=>$number]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Style, $query);
		
		// 返回
		return $this->Data($result);
	}
}

?>
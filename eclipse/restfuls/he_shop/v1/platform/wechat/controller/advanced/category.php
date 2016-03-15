<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 取query
		$query = $this->createQuery([], ['sort'=>array('sort'=>1), 'projection'=>['_id'=>0]]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Category, $query);
		
		// 返回
		return $this->Data($result);
	}
}

?>
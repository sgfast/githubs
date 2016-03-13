<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_items(){
		
		// 取query
		$query = $this->createQuery(['oid'=>get('oid')]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Sp_Item, $query);

		// 返回
		return $this->Data($result);
	}

}

?>
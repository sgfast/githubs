<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_uid(){
		
		// 取query
		$query = $this->createQuery(['uid'=>get('uid')], ['limit'=>get('limit'), 'skip'=>get('limit')*get('skip')]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Er_Coupon, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Er_Coupon, $query);
		
		// 返回
		return $this->Data($result);
	}
}

?>
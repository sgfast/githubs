<?php

class MyController extends Controller{
	
	/**
	 * 获取某一商品的所有评论
	 */
	public function get_pid(){
	
		// 取query
		$query = $this->createQuery(['pid'=>get('pid')], ['sort'=>array('time'=>-1)]);
	
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Comment, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取单条
	 */
	public function get_id(){
		
		// 取query
		$query = $this->createQuery($this->createId(get('id')));
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Comment, $query);
		
		// 返回
		return $this->Data($result);
	}
}

?>
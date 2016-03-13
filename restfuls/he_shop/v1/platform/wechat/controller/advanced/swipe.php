<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_imgs(){
		
		// 取query
		$query = $this->createQuery(["area"=>get("aid")], ['sort'=>array('sort'=>1), 'projection'=>['aid'=>1, 'link'=>1, 'img'=>1]]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Swipe, $query);
		
		// 返回
		return $this->Data($result);
	}
}

?>
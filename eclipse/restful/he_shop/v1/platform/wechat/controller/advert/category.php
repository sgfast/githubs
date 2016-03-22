<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 组织options
		$options = new Options();
		$options->sort = array('sort'=>1);
		
		// 取query
		$query = $this->createQuery([], $options->create());
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Category, $query);
		
		// 返回
		return $this->Data($result);
	}
}

?>
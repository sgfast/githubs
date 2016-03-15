<?php

class MyController extends Controller{

	/**
	 * 该集合只有一条数据,获取图片
	 */
	public function get_img(){
		
		// 取query
		$query = $this->createQuery([], ['projection'=>['img'=>1]]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Category, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 该集合只有一条数据,获取内容
	 */
	public function get_content(){
	
		// 取query
		$query = $this->createQuery([], ['projection'=>['content'=>1]]);
	
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Category, $query);
	
		// 返回
		return $this->Data($result);
	}
}

?>
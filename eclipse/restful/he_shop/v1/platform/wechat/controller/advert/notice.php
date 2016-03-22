<?php

class MyController extends Controller{

	/**
	 * 该集合只有一条数据,获取图片
	 */
	public function get_img(){
		
		// 组织options
		$options = new Options();
		$options->projection = 'img';
		
		// 取query
		$query = $this->createQuery([], $options->create());
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Notice, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 该集合只有一条数据,获取内容
	 */
	public function get_content(){
		
		// 组织options
		$options = new Options();
		$options->projection = 'content';
	
		// 取query
		$query = $this->createQuery([],$options->create());
	
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Notice, $query);
	
		// 返回
		return $this->Data($result);
	}
}

?>
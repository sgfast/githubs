<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_imgs(){
		
		// 组织options
		$options = new Options();
		$options->sort = array('sort'=>1);
		$options->projection = 'aid, link, img';
		
		// 取query
		$query = $this->createQuery(["aid"=>get("aid")], $options->create());
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Ad_Swipe, $query);
		
		// 返回
		return $this->Data($result);
	}
}

?>
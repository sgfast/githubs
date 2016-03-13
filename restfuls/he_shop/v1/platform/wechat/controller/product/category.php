<?php

class MyController extends Controller{
	
	/**
	 * 获取全部大类小类
	 */
	public function get_all(){
	
		// 定义最终返回数组
		$result = array();
		
		// 取大类
		$query_big = $this->createQuery(['fid'=>''], ['sort'=>array('sort'=>1)]);
		$result_big = &Mongo::query(DB::$main, COL::$Pt_Category, $query_big);
		
		$result = $result_big;
		
		// 取对应大类下的小类
		foreach ($result_big as $k=>$v)
		{
			// 取小类
			$query_small = $this->createQuery(['fid'=>$v->_id], ['sort'=>array('sort'=>1)]);
			$result_small = &Mongo::query(DB::$main, COL::$Pt_Category, $query_small);
			
			$result[$k]->small = $result_small;
		}
	
		// 返回
		return $this->Data($result);
	}
}

?>
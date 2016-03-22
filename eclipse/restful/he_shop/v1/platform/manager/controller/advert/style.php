<?php

class MyController extends Controller{

	/**
	 * 获取所有样式数据
	 */
	public function get_all(){
		
		// 取query
		$query = $this->createQuery(["_id"=>['$gte'=>1]]);
		$result = &Mongo::query(DB::$main, COL::$Ad_Style, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取单样式数据
	 */
	public function get_id(){
	
		// 取query
		$query = $this->createQueryId(get('id'));
		$result = &Mongo::query(DB::$main, COL::$Ad_Style, $query);
	
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 编辑一条样式数据
	 */
	public function post_id(){
	
		// 取post数据
		$update = array();
		$update['img_1'] = post('img_1');
		$update['link_1'] = post('link_1');
		$update['img_2'] = post('img_2');
		$update['link_2'] = post('link_2');
		$update['img_3'] = post('img_3');
		$update['link_3'] = post('link_3');
		$update['img_4'] = post('img_4');
		$update['link_4'] = post('link_4');
		$update['img_5'] = post('img_5');
		$update['link_5'] = post('link_5');
		$update['img_6'] = post('img_6');
		$update['link_6'] = post('link_6');
		$update['img_7'] = post('img_7');
		$update['link_7'] = post('link_7');
	
		// 注册bulk
		$this->createUpdate(['_id'=>get('id')], ['$set'=>$update], $bulk);
		
		// 如果启用词条样式,则更改_id=0 的数据的number字段
		if (post('use') == 1)
			$this->createUpdate(['_id'=>0], ['$set'=>['number'=>get('id')]], $bulk);
		
		Mongo::write(DB::$main, COL::$Ad_Style, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
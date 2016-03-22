<?php

class MyController extends Controller{

	/**
	 * 活动公告只有一条数据,获取图片
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
	 * 活动公告只有一条数据,获取内容
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
	
	/**
	 * 活动公告只有一条数据,编辑活动公告
	 */
	public function post_notice(){
	
		// 取post数据
		$update = array();
		$update['content'] = post('content');
		$update['img'] = post('img');
	
		// 注册bulk
		$this->createUpdate([], ['$set'=>$update], $bulk);
	
		// 插入
		Mongo::write(DB::$main, COL::$Ad_Notice, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
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
		$query = $this->createQueryId($this->createId(get('id')));
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Comment, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 插入
	 */
	public function post_add(){
	
		// 取object
		$this->injection(MOD::$Pt_Comment);
		$obj = json_decode(Pt_Comment_Main);
	
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->pid = get('pid');
		$obj->uid = get('uid');
		$obj->star = 5;
		$obj->ip = '192.168.1.36';
		$obj->time = time();
		$obj->content = '测试商品评论一';
	
		// 注册bulk
		$bulk = $this->createInsert($obj);
	
		// 插入
		Mongo::write(DB::$main, COL::$Pt_Comment, $bulk);
	
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 删除
	 */
	public function delete_id(){
	
		// 注册bulk
		$bulk = $this->createDelete(['_id'=>$this->createId(get('id'))]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Pt_Comment, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
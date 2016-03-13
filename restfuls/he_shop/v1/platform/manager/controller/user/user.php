<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 取query
		$query = $this->createQuery([], ['limit'=>get('limit'), 'skip'=>get('limit')*get('skip')]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Er_User, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Er_User, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 编辑
	 */
	public function put_id(){
	
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('id'))], ['$set'=>['feedback.'.post("index").'.content'=>post('content')]]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Er_User, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
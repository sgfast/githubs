<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_receives(){
	
		// 取query
		$query = $this->createQuery(['uid'=>get('uid')]);
	
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Er_Receive, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Er_Receive, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 插入
	 */
	public function post_add(){
		
		// 取object
		$this->injection(MOD::$Er_Receive);
		$obj = json_decode(Er_Receive_Main);
		
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->uid = '56d66166434d6708a8006151';
		$obj->aid = 0;
		$obj->default = false;
		$obj->name = '张福乐三';
		$obj->mobile = '13013013130';
		$obj->address = '张福乐家三';
		
		// 注册bulk
		$bulk = $this->createInsert($obj);
		
		// 插入
		Mongo::write(DB::$main, COL::$Er_Receive, $bulk);
		
		// 返回
		return $this->Ok();
	}


	/**
	 * 编辑
	 */
	public function put_id(){
		
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('id'))], ['$set'=>['name'=>post('name')]]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Er_Receive, $bulk);
	
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
		Mongo::write(DB::$main, COL::$Er_Receive, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
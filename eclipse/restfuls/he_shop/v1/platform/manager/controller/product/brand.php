<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 取query
		// ['a'=>'abc', 'b'=>123, 'c.d'=>'a']
		/*
		 * 
		 * */
		$query = $this->createQuery([]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Brand, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Pt_Brand, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 插入
	 */
	public function post_add(){
		
		// 取object
		$this->injection(MOD::$Pt_Brand);
		$obj = json_decode(Pt_Brand_Main);
		
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->name = 'PEAK';
		$obj->sort = 1;

		// 注册bulk
		$bulk = $this->createInsert($obj);
		
		// 插入
		Mongo::write(DB::$main, COL::$Pt_Brand, $bulk);
		
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 编辑
	 */
	public function put_id(){
	
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('id'))], ['$set'=>['sort'=>5]]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Pt_Brand, $bulk);
	
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
		Mongo::write(DB::$main, COL::$Pt_Brand, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_items(){
		
		// 取query
		$query = $this->createQuery(['oid'=>get('oid')]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Sp_Item, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Sp_Item, $query);
		
		// 返回
		return $this->Data($result);
	}

	/**
	 * 插入
	 */
	public function post_add(){
	
		// 取object
		$this->injection(MOD::$Sp_Item);
		$obj = json_decode(Sp_Item_Main);
	
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->oid = '';
		$obj->cbid = '';
		$obj->csid = '';
		$obj->bid = '';
		$obj->pid = '';
		$obj->name = '';
		$obj->img = '';
		$obj->price = 0;
		$obj->count = 0;
		$obj->amount = 0;
	
		// 注册bulk
		$bulk = $this->createInsert($obj);
	
		// 插入
		Mongo::write(DB::$main, COL::$Sp_Item, $bulk);
	
		// 返回
		return $this->Ok();
	}

}

?>
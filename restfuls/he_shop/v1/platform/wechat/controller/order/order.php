<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 取query
		$query = $this->createQuery([], ['limit'=>get('limit'), 'skip'=>get('limit')*get('skip')]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Sp_Order, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Sp_Order, $query);
		
		// 返回
		return $this->Data($result);
	}

	/**
	 * 插入
	 */
	public function post_add(){
	
		// 取object
		$this->injection(MOD::$Sp_Order);
		$obj = json_decode(Sp_Order_Main);
	
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->aid = 0;
		$obj->uid = '';
		$obj->cid = '';
		$obj->oid = '';
		$obj->eid = '';
		$obj->status = 0;
		$obj->point = 0;
		$obj->product = '';
		$obj->img = '';
		$obj->printed = false;
		
		$obj->receive->express = true;
		$obj->receive->name = '';
		$obj->receive->mobile = '';
		$obj->receive->address = '';
		$obj->receive->mark = '';
		
		$obj->time->create = 0;
		$obj->time->take = 0;
		$obj->time->complete = 0;
		
		$obj->pay->type = 0;
		$obj->pay->complete = false;
		$obj->pay->amount = 0;
		$obj->pay->cash = 0;
		$obj->pay->coupon = 0;
		$obj->pay->online = 0;
		$obj->pay->payable = 0;
	
		// 注册bulk
		$bulk = $this->createInsert($obj);
	
		// 插入
		Mongo::write(DB::$main, COL::$Sp_Order, $bulk);
	
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 编辑
	 */
	public function put_id(){
	
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('id'))], ['$set'=>['status'=>1]]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Sp_Order, $bulk);
	
		// 返回
		return $this->Ok();
	}

}

?>
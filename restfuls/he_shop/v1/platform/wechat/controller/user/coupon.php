<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_uid(){
		
		// 取query
		$query = $this->createQuery(['uid'=>get('uid'), 'limit.time'=>['$lte'=>time()], 'time.use'=>['$not'=>0]]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Er_Coupon, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Er_Coupon, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 插入
	 */
	public function post_add(){
		
		// 取object
		$this->injection(MOD::$Er_Coupon);
		$obj = json_decode(Er_Coupon_Main);
		
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->cid = '';
		$obj->bid = '';
		$obj->uid = '';
		$obj->value = 1;
		$obj->limit->value = 10;
		$obj->limit->time = 0;
		$obj->time->get = time();
		$obj->time->use = 0;
		
		// 注册bulk
		$bulk = $this->createInsert($obj);
		
		// 插入
		Mongo::write(DB::$main, COL::$Er_Coupon, $bulk);
		
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 编辑
	 */
	public function put_id(){
	
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('id'))], ['$set'=>['time.use'=>time()]]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Er_Coupon, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
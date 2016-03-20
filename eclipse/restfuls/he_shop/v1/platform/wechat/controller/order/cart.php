<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		//去购物车商品id
		$query_cart = $this->createQuery(['aid'=>get('aid'), 'uid'=>get('uid')]);
		
		// 取query
		$query = $this->createQuery([]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Sp_Cart, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Sp_Cart, $query);
		
		// 返回
		return $this->Data($result);
	}

	/**
	 * 插入
	 */
	public function post_add(){
	
		// 取object
		$this->injection(MOD::$Sp_Cart);
		$obj = json_decode(Sp_Cart_Main);
	
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->aid = 0;
		$obj->uid = '';
		$obj->pid = '';
		$obj->count = 0;
		$obj->time = 0;
	
		// 注册bulk
		$this->createInsert($obj, $bulk);
	
		// 插入
		Mongo::write(DB::$main, COL::$Sp_Cart, $bulk);
	
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
		Mongo::write(DB::$main, COL::$Sp_Cart, $bulk);
	
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
		Mongo::write(DB::$main, COL::$Sp_Cart, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
<?php

class MyController extends Controller{
	
	/**
	 * 获取用户中心home页信息
	 */
	public function get_home(){
		
		// 定义最终返回数组
		$result = array();
		
		// 取用户基本信息
		$query_main = $this->createQueryId($this->createId(get('uid')));
		$result_main = &Mongo::query(DB::$main, COL::$Er_User, $query_main);
		
		$result['cash'] = $result_main[0]->cash;
		$result['point'] = $result_main[0]->point;
		$result['gift']	= count($result_main[0]->gift);
		
		// 取收货地址
		$query_receive = $this->createQuery(['uid'=>get('uid'), 'aid'=>get('aid')]);
		$result_receive = &Mongo::query(DB::$main, COL::$Er_Receive, $query_receive);
		$result['receive'] = count($result_receive);
		
		// 取代金券
		$query_coupon = $this->createQuery(['uid'=>get('uid'), 'time.use'=>0, 'limit.time'=>['$gt'=>time()]]);
		$result_coupon = &Mongo::query(DB::$main, COL::$Er_Coupon, $query_receive);
		$result['coupon'] = count($result_coupon);
		
		// 取站内消息
		$query_message = $this->createQuery(['uid'=>get('uid'), 'read'=>false, 'time.end'=>['$gt'=>time()]]);
		$result_message = &Mongo::query(DB::$main, COL::$Er_Message, $query_message);
		$result['message'] = count($result_message);
		
		// 取一个月内订单数量
		$query_order = $this->createQuery(['uid'=>get('uid'), 'aid'=>get('aid'), 'time.create'=>['$gt'=>strtotime('-1 month')]]);
		$result_order = &Mongo::query(DB::$main, COL::$Sp_Order, $query_order);
		$result['order'] = count($result_order);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 插入
	 */
	public function post_add(){
		
		// 取object
		$this->injection(MOD::$Er_User);
		$obj = json_decode(Er_User_Main);
		
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->aid = 0;
		$obj->openid = 'oLUK4s-lm9NQYPbLyqP7Q19WrCSU';
		$obj->nickname = '张福乐';
		$obj->mobile = '13013013130';
		$obj->email = '163@163.com';
		$obj->thumb = '';
		$obj->point = 0;
		$obj->cash = 0;
		$obj->register = time();
		
		// 注册bulk
		$bulk = $this->createInsert($obj);
		
		// 插入
		Mongo::write(DB::$main, COL::$Er_User, $bulk);
		
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 编辑
	 */
	public function put_id(){
	
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('id'))], ['$inc'=>['cash'=>-5]]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Er_User, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
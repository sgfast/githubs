<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_feedbacks(){
	
		// 取query
		$query = $this->createQuery(['_id'=>$this->createId(get('uid'))], ['projection'=>['nickname'=>1, 'feedback'=>1]]);
	
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Er_User, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取单条
	 */
	public function get_index(){
	
		// 取query
		$query = $this->createQuery(['_id'=>$this->createId(get('uid'))], ['projection'=>['nickname'=>1, 'feedback'=>['$slice'=>[get('index'), 1]]]]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Er_User, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 插入
	 */
	public function post_add(){
		
		// 取object
		$this->injection(MOD::$Er_User);
		$obj = json_decode(Er_User_Feedback);
		
		// 为obj赋值
		$obj->content = '测试反馈意见四';
		$obj->time->submit = time();
		$obj->time->answer = 0;
		
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('uid'))], ['$push'=>['feedback'=>$obj]]);
		
		// 插入
		Mongo::write(DB::$main, COL::$Er_User, $bulk);
		
		// 返回
		return $this->Ok();
	}

}

?>
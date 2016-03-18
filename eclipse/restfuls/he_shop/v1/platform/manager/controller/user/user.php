<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 组织filter
		$filter = array();
		if (get('openid') != '')
			$filter['openid'] = get('openid');
		if (get('nickname') != '')
			$filter['nickname'] = get('nickname');
		if (get('mobile') != '')
			$filter['mobile'] = get('mobile');
		
		// 组织options
		$options = new Options();
		$options->limit = get('limit');
		$options->skip = get('limit')*(get('page')-1);
		
		// 取query
		$query = $this->createQuery($filter, $options->create());
		
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
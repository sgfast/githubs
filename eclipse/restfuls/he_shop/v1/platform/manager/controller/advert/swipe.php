<?php

class MyController extends Controller{

	/**
	 * 获取所有轮转图数据
	 */
	public function get_all(){
		
		// 组织filter
		$filter = array();
		if (get('aid') != '')
			$filter['aid'] = get('aid');
	
		// 组织options
		$options = new Options();
		$options->limit = get('limit');
		$options->skip = get('limit')*(get('page')-1);
		
		// 取query
		$query = $this->createQuery($filter, $options->create());
		$result = &Mongo::query(DB::$main, COL::$Ad_Swipe, $query);
	
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取单条轮转图数据
	 */
	public function get_id(){
	
		// 取query
		$query = $this->createQueryId($this->createId(get('id')));
		$result = &Mongo::query(DB::$main, COL::$Ad_Swipe, $query);
	
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 添加一张轮转图
	 */
	public function post_add(){
		
		// 取object
		$this->injection(MOD::$Ad_Swipe);
		$obj = json_decode(Ad_Swipe_Main);
		
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->aid = floatval(post('aid'));
		$obj->name = post('name');
		$obj->img = post('img');
		$obj->link = post('link');
		$obj->sort = post('sort');
		$obj->show = post('show')==1 ? true : false;
		
		// 注册bulk
		$this->createInsert($obj, $bulk);
		
		// 插入
		Mongo::write(DB::$main, COL::$Ad_Swipe, $bulk);
		
		// 返回
		return $this->Ok();
	}

	/**
	 * 编辑一张轮转图
	 */
	public function post_id(){
		
		// 取post数据
		$update = array();
		$update['aid'] = floatval(post('aid'));
		$update['name'] = post('name');
		$update['img'] = post('img');
		$update['link'] = post('link');
		$update['sort'] = post('sort');
		$update['show'] = post('show')==1 ? true : false;
	
		// 注册bulk
		$this->createUpdate(['_id'=>$this->createId(get('id'))], ['$set'=>$update], $bulk);
	
		// 插入
		Mongo::write(DB::$main, COL::$Ad_Swipe, $bulk);
	
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 删除一张轮转图
	 */
	public function delete_id(){
	
		// 注册bulk
		$this->createDelete(['_id'=>$this->createId(get('id'))], $bulk);
	
		// 插入
		Mongo::write(DB::$main, COL::$Ad_Swipe, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
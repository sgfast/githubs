<?php

class MyController extends Controller{
	
	/**
	 * 获取所有广告类别数据
	 */
	public function get_all(){
	
		// 组织options
		$options = new Options();
		$options->sort = array('sort'=>1);

		// 取query
		$query = $this->createQuery([], $options->create());
		$result = &Mongo::query(DB::$main, COL::$Ad_Category, $query);

		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取单条广告类别数据
	 */
	public function get_id(){
	
		// 取query
		$query = $this->createQueryId($this->createId(get('id')));
		$result = &Mongo::query(DB::$main, COL::$Ad_Category, $query);
	
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 添加一种广告类别
	 */
	public function post_add(){
	
		// 取object
		$this->injection(MOD::$Ad_Category);
		$obj = json_decode(Ad_Category_Main);
	
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->name = post('name');
		$obj->title_1 = post('title_1');
		$obj->title_2 = post('title_2');
		$obj->img = post('img');
		$obj->link = post('link');
		$obj->sort = post('sort');
	
		// 注册bulk
		$this->createInsert($obj, $bulk);
	
		// 插入
		Mongo::write(DB::$main, COL::$Ad_Category, $bulk);
	
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 编辑一种广告类别
	 */
	public function post_id(){
	
		// 取post数据
		$update = array();
		$update['name'] = post('name');
		$update['title_1'] = post('title_1');
		$update['title_2'] = post('title_2');
		$update['img'] = post('img');
		$update['link'] = post('link');
		$update['sort'] = post('sort');
	
		// 注册bulk
		$this->createUpdate(['_id'=>$this->createId(get('id'))], ['$set'=>$update], $bulk);
	
		// 插入
		Mongo::write(DB::$main, COL::$Ad_Category, $bulk);
	
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 删除一种广告类别
	 */
	public function delete_id(){
	
		// 注册bulk
		$this->createDelete(['_id'=>$this->createId(get('id'))], $bulk);
	
		// 插入
		Mongo::write(DB::$main, COL::$Ad_Category, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
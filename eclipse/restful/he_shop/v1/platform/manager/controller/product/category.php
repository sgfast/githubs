<?php

class MyController extends Controller{

	/**
	 * 获取全部大类
	 */
	public function get_bid(){
		
		// 取query
		$query = $this->createQuery(['fid'=>'']);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Category, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取某一大类下的全部小类
	 */
	public function get_sid(){
	
		// 取query
		$query = $this->createQuery(['fid'=>get('fid')]);
	
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Category, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Pt_Category, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 插入
	 */
	public function post_add(){
		
		// 取object
		$this->injection(MOD::$Pt_Category);
		$obj = json_decode(Pt_Category_Main);
		
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->fid = '56ce78cf434d670ea0003c37';
		$obj->name = '测试小类';
		$obj->sort = 2;
		
		// 注册bulk
		$bulk = $this->createInsert($obj);
		
		// 插入
		Mongo::write(DB::$main, COL::$Pt_Category, $bulk);
		
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 编辑
	 */
	public function put_id(){
	
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('id'))], ['$set'=>['sort'=>get('sort')]]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Pt_Category, $bulk);
	
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
		Mongo::write(DB::$main, COL::$Pt_Category, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 取query
		$query = $this->createQuery([], ['limit'=>get('limit'), 'skip'=>get('limit')*get('skip')]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Product, $query);
		
		foreach ($result as $k=>$v){
			$v->set[0]->tag->hidden = false;
		}
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取单条
	 */
	public function get_id(){
		
		// 取query
		$query = $this->createQuery(['_id'=>$this->createId(get('id'))], ['projection'=>['_id'=>1, 'cbid'=>1, 'csid'=>1, 'bid'=>1, 'name'=>1, 'img'=>1, 'stand'=>1, 'paycash'=>1, 'content'=>1, 'set'=>['$slice'=>[get('aid'), 1]], 'comment'=>1]]);
		$result = &Mongo::query(DB::$main, COL::$Pt_Product, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取首页显示的活动商品	:	特价促销/精品推荐/最新商品
	 */
	public function get_special(){
	
		// 定义最终返回数组
		$result = array();
		
		// 组织特价促销部分
		$query_sales = $this->createQuery(
				['set.'.get("aid").'tag.hidden'=>false, 'set.'.get("aid").'tag.sales'=>true],
				['limit'=>8, 'projection'=>['_id'=>1, 'name'=>1, 'img'=>1, 'set'=>['$slice'=>[get('aid'), 1]]]]
				);
		$result_sales = &Mongo::query(DB::$main, COL::$Pt_Product, $query_sales);
		
		// 组织精品推荐部分
		$query_recommend = $this->createQuery(
				['set.'.get("aid").'tag.hidden'=>false, 'set.'.get("aid").'tag.recom'=>true],
				['limit'=>8, 'projection'=>['_id'=>1, 'name'=>1, 'img'=>1, 'set'=>['$slice'=>[get('aid'), 1]]]]
				);
		$result_recommend = &Mongo::query(DB::$main, COL::$Pt_Product, $query_recommend);
		
		// 组织最新商品部分
		$query_new = $this->createQuery(
				['set.'.get("aid").'tag.hidden'=>false, 'set.'.get("aid").'tag.new'=>true],
				['limit'=>8, 'projection'=>['_id'=>1, 'name'=>1, 'img'=>1, 'set'=>['$slice'=>[get('aid'), 1]]]]
				);
		$result_new = &Mongo::query(DB::$main, COL::$Pt_Product, $query_new);
		
		// 组合数组
		$result['sales'] = $result_sales;
		$result['recommend'] = $result_recommend;
		$result['new'] = $result_new;
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取商品列表
	 */
	public function get_list(){
	
		// 取参数
		$aid = get('aid');
		$bid = get('bid');
		$sid = get('sid');
		$type = get('type');
		$keyword = get('keyword');
		
		// 定义query 的条件
		$params = array();
		$params['set.'.$aid.'.tag.hidden'] = false;
		
		if ($type != '')
		{
			switch ($type)
			{
				case 'sales':
					$params['set.'.$aid.'.tag.sales'] = true;
					break;
				case 'recom':
					$params['set.'.$aid.'.tag.recom'] = true;
					break;
				case 'new':
					$params['set.'.$aid.'.tag.new'] = true;
					break;
				default:
					break;
			}
			
		}
		else if ($keyword != '')
		{
			$params['name'] = '/'.$keyword.'/';
		}
		else 
		{
			$params['cbid'] = true;
			if ($sid != '')
				$params['csid'] = $sid;
		}
		// 组织特价促销部分
		$query = $this->createQuery($params,
				['projection'=>['_id'=>1, 'name'=>1, 'img'=>1, 'stand'=>1, 'set'=>['$slice'=>[get('aid'), 1]], 'comment'=>1]]);
		$result = &Mongo::query(DB::$main, COL::$Pt_Product, $query);
	
		// 返回
		return $this->Data($result);
	}
}

?>
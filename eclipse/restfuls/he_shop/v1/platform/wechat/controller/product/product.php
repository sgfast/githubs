<?php

class MyController extends Controller{

	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 组织options
		$options = new Options();
		$options->limit = get('limit');
		$options->skip = get('limit')*get('page');
		
		// 取query
		$query = $this->createQuery([], $options->create());
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Product, $query);
		
		// 为商品加上活动标签的字段,默认为空
		foreach ($result as $k=>$v){
			$v->set[0]->tag->topicNmae = "";
			$v->set[0]->tag->buyNmae = "";
		}
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 获取单条
	 */
	public function get_id(){
		
		// 组织options
		$options = new Options();
		$options->addSlice('set', get('aid'), 1);
		$options->projection = '_id,cbid,csid,bid,name,img,stand,paycash,content,comment';
		
		// 取query
		$query = $this->createQuery(['_id'=>$this->createId(get('id'))], $options->create());
		$result = &Mongo::query(DB::$main, COL::$Pt_Product, $query);
		
		// 为商品加上活动标签的字段,默认为空
		$result[0]->set[0]->tag->topicNmae = "";
		$result[0]->set[0]->tag->buyNmae = "";
		
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
		// 组织options
		$options = new Options();
		$options->limit = 8;
		$options->addSlice('set', get('aid'), 1);
		$options->projection = '_id,name,img';
		
		$query_sales = $this->createQuery(['set.'.get("aid").'tag.hidden'=>false, 'set.'.get("aid").'tag.sales'=>true], $options->create());
		$result_sales = &Mongo::query(DB::$main, COL::$Pt_Product, $query_sales);
		
		// 组织精品推荐部分
		// 组织options
		$options = new Options();
		$options->addSlice('set', get('aid'), 1);
		$options->projection = '_id,name,img';
		
		$query_recommend = $this->createQuery(['set.'.get("aid").'tag.hidden'=>false, 'set.'.get("aid").'tag.recom'=>true], $options->create());
		$result_recommend = &Mongo::query(DB::$main, COL::$Pt_Product, $query_recommend);
		
		// 组织最新商品部分
		// 组织options
		$options = new Options();
		$options->addSlice('set', get('aid'), 1);
		$options->projection = '_id,name,img';
		
		$query_new = $this->createQuery(['set.'.get("aid").'tag.hidden'=>false, 'set.'.get("aid").'tag.new'=>true], $options->create());
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
		// 组织options
		$options = new Options();
		$options->addSlice('set', get('aid'), 1);
		$options->projection = '_id,name,img,stand,comment';
		
		$query = $this->createQuery($params,$options->create());
		$result = &Mongo::query(DB::$main, COL::$Pt_Product, $query);
	
		// 返回
		return $this->Data($result);
	}
}

?>
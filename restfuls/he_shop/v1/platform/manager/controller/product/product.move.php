<?php include "../../../../../../../core/core.php";

class MyController extends Controller{

	/**
	 * 商品表迁移
	 */
	public function post_move(){
		
		// 定义$bulk_category 和 $bulk_product
		$bulk_category = null;
		$bulk_product = null;
		
		// 取数据, 分析合并数据，将大类下设小类数组，小类下设商品数组
		$bc = array();
		$原大类 = 读数据(Db::$Mr, "select * from 商品_类别 where 父类号=0")->fetchAll();
		foreach ($原大类 as $k=>$v)
		{
			$cbid = $this->createId();
			$bc[$k]->_id = $cbid;
			$bc[$k]->fid = '';
			$bc[$k]->name = $v['名称'];
			$bc[$k]->img = $v['图片'];
			$bc[$k]->sort = $v['排序'];
			
			$this->createInsert($bc[$k], $bulk_category);
			
			$原小类 = 读数据(Db::$Mr, "select * from 商品_类别 where 父类号=?", array($v['序号']))->fetchAll();
			$sc = array();
			foreach ($原小类 as $kk=>$vv)
			{
				$csid = $this->createId();
				$sc[$kk]->_id = $csid;
				$sc[$kk]->fid = $cbid;
				$sc[$kk]->name = $vv['名称'];
				$sc[$kk]->img = $vv['图片'];
				$sc[$kk]->sort = $vv['排序'];
				
				$this->createInsert($sc[$kk], $bulk_category);
				
				$原商品 = 读数据(Db::$Mr, "select * from 商品_主表 where 大类号=? and 小类号=?", array($v['序号'], $vv['序号']))->fetchAll();
				$product = array();
				foreach ($原商品 as $kkk=>$vvv)
				{
					$product[$kkk]->cbid = $cbid;
					$product[$kkk]->csid = $csid;
					$product[$kkk]->bid = '规格id';
					$product[$kkk]->name = $vvv['名称'];
					$product[$kkk]->img = [$vvv['图片']];
					$product[$kkk]->stand = '规格名称';
					$product[$kkk]->paycash = true;
					$product[$kkk]->content = $vvv['详细说明'];
					
					$set = array();
					for ($i=0; $i<5; $i++)
					{
						$原设置 = 读数据(Db::$Mr, "select * from 商品_设置 where 商品号=? and 区域号=?", array($vvv['序号'], $i))->fetch();
						$set[$i]->aid = $i;
						$set[$i]->price = 10.00;
						$set[$i]->point = 10;
						$set[$i]->stock = 5;
						$set[$i]->code = '123132132132132';
						$set[$i]->sort = 0;
						$set[$i]->visited = 20;
						$set[$i]->tag->hidden = $原设置['是隐藏']=='是' ? true : false;
						$set[$i]->tag->sales = $原设置['是促销']=='是' ? true : false;
						$set[$i]->tag->recom = $原设置['是推荐']=='是' ? true : false;
						$set[$i]->tag->new = $原设置['是新品']=='是' ? true : false;
						$set[$i]->sales->total = 20;
						$set[$i]->sales->month = 10;
						$set[$i]->sales->preset = 2;
					}
					$product[$kkk]->set = $set;
					
					$product[$kkk]->comment->star = 5;
					$product[$kkk]->comment->count = 8;
					
					$this->createInsert($product[$kkk], $bulk_product);
				}
				
				$sc[$kk]->product = $product;
			}
			
			$bc[$k]->product = $sc;
		}
		
		Mongo::write(DB::$main, COL::$Pt_Category, $bulk_category);
		Mongo::write(DB::$main, COL::$Pt_Product, $bulk_product);
		
		// 返回
		return $this->Ok();
		
		
		
		
		
		/* 1，取数据，存放在各自的数组中
		   2，分析合并数据，将大类下设小类数组，小类下设商品数组
		   bc = [
				{
					id: '',
					name: '',
					sc: [{
							id: '',
							bid: '',	
							name:'',
							product:
								[{
									BID: '',
									SID: '',			
									name: '',
									set: [{
										
									}]
								}]
						}]
				},
				
				{
					id: '',
					name: '',
					sc: [ {
							id: '',
							bid: '',
							name:'',
							product:
								[{
									BID: '',
									SID: '',		
									name: '',
									set: [{
										
									}]
								}]
						}]
				},
		   ]
		   3，
		   
		   foch (bc as $bidKey=>$bidValue){
		   
		   		$bid = $bidValue->id = this->createId();
		   	
		   		foreach ($bidValue->sc as $sidKey=>$sidValue){
		   		
		   			$bidValue->sc[$sidKey]->bid = $bid;
		   			
		   			$sid = $sidValue->id = this->createId();
		   		
		   			foreach ($sidValue->product as $pKey=>$pValue){
		   				$pValue->bid = $bid;
		   				$pValue->sid = $sid;
		   				$pValue->id = this->createId();
		   			}
		   		
		   		}
		   		
		   }
		   
		   
		   4, 赋值
		   
		   
		   
		   5, 插入
		   
		   
		   $bulk = null;
		   foreach (){
		   		
		   		$this->createInsert({}, $bulk);
		   		
		   }
		   
		   $bulk = $this->createInsert({});
		   $this->createInsert({}, $bulk);
		   
		   
		   
		   
		*/
		
	}
	
	
	/**
	 * 获取全部
	 */
	public function get_all(){
		
		// 取query
		$query = $this->createQuery([], ['limit'=>get('skip'), 'skip'=>get('page')*get('skip')]);
		
		// 取值
		$result = &Mongo::query(DB::$main, COL::$Pt_Product, $query);
		
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
		$result = &Mongo::query(DB::$main, COL::$Pt_Product, $query);
		
		// 返回
		return $this->Data($result);
	}
	
	/**
	 * 插入
	 */
	public function post_add(){
		
		// 取object
		$this->injection(MOD::$Pt_Product);
		$obj = json_decode(Pt_Product_Main);
		
		// 为obj赋值
		$obj->_id = $this->createId();
		$obj->cbid = '56ce78cf434d670ea0003c37';
		$obj->csid = '56cfa422434d670b38006bd2';
		$obj->bid = '56ce9ff2434d670ea0003c5e';
		$obj->name = 'banana';
		$obj->img = [];
		$obj->stand = '10kg';
		$obj->paycash = true;
		$obj->sales->total = 0;
		$obj->sales->month = 0;
		$obj->sales->preset = 0;
		$obj->comment->star = 5;
		$obj->comment->count = 0;
		for ($i=0; $i<5; $i++)
		{
			// 声明
			$obj_set = json_decode(Pt_Product_Set);
			
			// 为obj_set赋值
			$obj_set->aid = $i;
			$obj_set->price = 10;
			$obj_set->point = 10;
			$obj_set->stock = 10;
			$obj_set->code = 0;
			$obj_set->sort = 0;
			$obj_set->visited = 1;
			$obj_set->tag->hidden = false;
			$obj_set->tag->sales = false;
			$obj_set->tag->recom = false;
			$obj_set->tag->new = true;
			
			$obj->set[] = $obj_set;
		}
		
		// 注册bulk
		$bulk = $this->createInsert($obj);
		
		// 插入
		Mongo::write(DB::$main, COL::$Pt_Product, $bulk);
		
		// 返回
		return $this->Ok();
	}
	
	/**
	 * 编辑
	 */
	public function put_id(){
	
		// 注册bulk
		$bulk = $this->createUpdate(['_id'=>$this->createId(get('id')), 'set.aid'=>get('aid')], ['$set'=>['set.$.price'=>get('price')]]);
	
		// 插入
		Mongo::write(DB::$main, COL::$Pt_Product, $bulk);
	
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
		Mongo::write(DB::$main, COL::$Pt_Product, $bulk);
	
		// 返回
		return $this->Ok();
	}
}

?>
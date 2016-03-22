<?php 

class MyController extends Controller{

	/**
	 * 商品表迁移
	 */
	public function post_move(){
		
		// 注入常量
		$this->injection(MOD::$Pt_Category);
		$this->injection(MOD::$Pt_Product);
		
		// 定义$bulk_category 和 $bulk_product
		$bulk_category = null;
		$bulk_product = null;
		
		//取所有数据
		$data = unserialize(stripslashes(file_get_contents('D:/fresh/product.txt')));
		$类别 = $data[0];
		$商品 = $data[1];
		$设置 = $data[2];
		
		$bc = array();
		foreach ($类别 as $k=>$v)
		{
			//组织大类数组
			if ($v['父类号'] == 0)
			{
				$bc[$k] = json_decode(Pt_Category_Main);
				$cbid = $this->createId();
				$bc[$k]->_id = $cbid;
				$bc[$k]->fid = '';
				$bc[$k]->name = $v['名称'];
				$bc[$k]->img = [$v['图片']];
				$bc[$k]->sort = $v['排序'];
				
				$this->createInsert($bc[$k], $bulk_category);
				
				//组织小类数组并加入大类中
				$sc = array();
				foreach ($类别 as $kk=>$vv)
				{
					if ($vv['父类号'] == $v['序号'])
					{
						$sc[$kk] = json_decode(Pt_Category_Main);
						
						$csid = $this->createId();
						$sc[$kk]->_id = $csid;
						$sc[$kk]->fid = $cbid;
						$sc[$kk]->name = $vv['名称'];
						$sc[$kk]->img = [$vv['图片']];
						$sc[$kk]->sort = $vv['排序'];
						
						$this->createInsert($sc[$kk], $bulk_category);
						
						//组织商品数组并加入小类中
						$product = array();
						foreach ($商品 as $kkk=>$vvv)
						{
							if ($vvv['小类号'] == $vv['序号'])
							{
								$product[$kkk] = json_decode(Pt_Product_Main);
								
								$productid = $this->createId();
								$product[$kkk]->_id = $productid;
								$product[$kkk]->cbid = $cbid;
								$product[$kkk]->csid = $csid;
								$product[$kkk]->bid = '规格id';
								$product[$kkk]->name = $vvv['名称'];
								$product[$kkk]->img = [$vvv['大图'], $vvv['小图']];
								$product[$kkk]->stand = '规格名称';
								$product[$kkk]->paycash = true;
								$product[$kkk]->content = $vvv['详细说明'];
									
								//组织商品设置数组并加入商品中
								$set = array();
								foreach ($设置 as $kkkk=>$vvvv)
								{
									for ($i=0; $i<5; $i++)
									{
										if ($vvvv['商品号'] == $vvv['序号'] && $vvvv['区域号'] == $i)
										{
											$set[$i] = json_decode(Pt_Product_Set);
											
											$set[$i]->aid = $i;
											$set[$i]->price = 10.00;
											$set[$i]->point = 10;
											$set[$i]->stock = 5;
											$set[$i]->code = '123132132132132';
											$set[$i]->sort = 0;
											$set[$i]->visited = 20;
											$set[$i]->tag->hidden = $vvvv['是隐藏']=='是' ? true : false;
											$set[$i]->tag->sales = $vvvv['是促销']=='是' ? true : false;
											$set[$i]->tag->recom = $vvvv['是推荐']=='是' ? true : false;
											$set[$i]->tag->new = $vvvv['是新品']=='是' ? true : false;
											$set[$i]->sales->total = 20;
											$set[$i]->sales->month = 10;
											$set[$i]->sales->preset = 2;
										}
									}
								}
								$product[$kkk]->set = $set;
								$product[$kkk]->comment->star = 5;
								$product[$kkk]->comment->count = 8;
								
								$this->createInsert($product[$kkk], $bulk_product);
							}
						}
						$sc[$kk]->product = $product;
					}
				}
				$bc[$k]->sc = $sc;
			}
		}
		
		
		
		Mongo::write(DB::$main, COL::$Pt_Category, $bulk_category);
		Mongo::write(DB::$main, COL::$Pt_Product, $bulk_product);
		
		// 返回
		//return $this->Data($bulk_product);
		return $this->Ok();
	}
}
?>
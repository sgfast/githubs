<?php 

class MyController extends Controller{

	/**
	 * 广告表迁移
	 */
	public function post_move(){
		
		// 注入常量
		$this->injection(MOD::$Ad_Category);
		$this->injection(MOD::$Ad_Notice);
		$this->injection(MOD::$Ad_Style);
		$this->injection(MOD::$Ad_Swipe);
		
		// 定义$bulk_category $bulk_notice $bulk_style $bulk_swipe
		$bulk_category = null;
		$bulk_notice = null;
		$bulk_style = null;
		$bulk_swipe = null;
		
		//取所有数据
		$data = unserialize(stripslashes(file_get_contents('D:/fresh/advert.txt')));
		$类别 = $data[0];
		$活动 = $data[1];
		$主表 = $data[2];
		$轮转图 = $data[3];
		
		foreach ($类别 as $k=>$v)
		{
			$obj = json_decode(Ad_Category_Main);
			$id = $this->createId();
			$obj->_id = $id;
			$obj->name = $v['名称'];
			$obj->title_1 = $v['标题1'];
			$obj->title_2 = $v['标题2'];
			$obj->img = $v['图片'];
			$obj->link = $v['链接'];
			$obj->sort = floatval($v['排序']);
			
			$this->createInsert($obj, $bulk_category);
		}
		
		foreach ($活动 as $k=>$v)
		{
			$obj = json_decode(Ad_Notice_Main);
			$id = $this->createId();
			$obj->_id = $id;
			$obj->content = $v['内容'];
			$obj->img = $v['图片'];
				
			$this->createInsert($obj, $bulk_notice);
		}
		
		foreach ($主表 as $k=>$v)
		{
			$obj = json_decode(Ad_Style_Main);
			$obj->_id = $k;
			$obj->number = floatval($v['编号']);
			$obj->img_1 = $v['图片1'];
			$obj->link_1 = $v['链接1'];
			$obj->img_2 = $v['图片2'];
			$obj->link_2 = $v['链接2'];
			$obj->img_3 = $v['图片3'];
			$obj->link_3 = $v['链接3'];
			$obj->img_4 = $v['图片4'];
			$obj->link_4 = $v['链接4'];
			$obj->img_5 = $v['图片5'];
			$obj->link_5 = $v['链接5'];
			$obj->img_6 = $v['图片6'];
			$obj->link_6 = $v['链接6'];
			$obj->img_7 = $v['图片7'];
			$obj->link_7 = $v['链接7'];
				
			$this->createInsert($obj, $bulk_style);
		}
		
		foreach ($轮转图 as $k=>$v)
		{
			$obj = json_decode(Ad_Swipe_Main);
			$id = $this->createId();
			$obj->_id = $id;
			$obj->aid = floatval($v['区域号']);
			$obj->name = $v['名称'];
			$obj->img = $v['图片'];
			$obj->link = $v['链接'];
			$obj->sort = floatval($v['排序']);
			$obj->show = $v['是显示']=='是' ? true : false;
				
			$this->createInsert($obj, $bulk_swipe);
		}
		
		Mongo::write(DB::$main, COL::$Ad_Category, $bulk_category);
		Mongo::write(DB::$main, COL::$Ad_Notice, $bulk_notice);
		Mongo::write(DB::$main, COL::$Ad_Style, $bulk_style);
		Mongo::write(DB::$main, COL::$Ad_Swipe, $bulk_swipe);
		
		// 返回
		return $this->Ok();
	}
}
?>
<?php 

class MyController extends Controller{
	
	/**
	 * 用户表迁移
	 */
	public function post_move(){
		
		$page = post('page');
		$limit = 500;
		
		// 注入常量
		$this->injection(MOD::$Er_User);
		$this->injection(MOD::$Er_Receive);
		$this->injection(MOD::$Er_Coupon);
		
		// 定义$bulk_category $bulk_notice $bulk_style $bulk_swipe
		$bulk_user = null;
		$bulk_receive = null;
		$bulk_coupon = null;
		
		//取所有数据
		$data = unserialize(stripslashes(file_get_contents('D:/fresh/user.txt')));
		$用户 = $data[0];
		$地址 = $data[1];
		$代金券 = $data[2];
		
		// 将用户id改为objectId并存入数组
		$userid = array();
		foreach ($用户 as $k=>$v)
			$userid[$v['序号']] = $this->createId();
		
		foreach ($用户 as $k=>$v)
		{			
			if ($v['序号'] > $limit*($page-1) && $v['序号'] <= $limit*$page)
			{
				$obj_user = json_decode(Er_User_Main);
				$obj_user->_id = $userid[$v['序号']];
				$obj_user->aid = floatval($v['区域号']);
				$obj_user->openid = $v['微信号'];
				$obj_user->nickname = $v['昵称'];
				$obj_user->mobile = $v['电话'];
				$obj_user->email = $v['邮箱'];
				$obj_user->point = $v['积分'];
				$obj_user->cash = $v['余额'];
				$obj_user->register = $v['首次来访'];
					
				$obj_user->login->first = $v['首次来访'];
				$obj_user->login->last = $v['最后来访'];
				$obj_user->login->count = $v['来访次数'];
					
				$obj_user->buy->count = $v['购买次数'];
				$obj_user->buy->item = $v['购买条数'];
				$obj_user->buy->amount = $v['购买额数'];
					
				$obj_user->fans->did = $v['分销员号']==0 ? '' : $userid[$v['分销员号']];
				$obj_user->fans->start = $v['分销时间'];
				$obj_user->fans->end = 0;
					
				//求粉丝数量
				$n = 0;
				foreach ($用户 as $info)
				{
					if ($info['分销员号'] = $v['序号'])
						$n++;
				}
					
				$obj_user->distr->fans = $n;
				$obj_user->distr->level = $v['分销级别'];
				$obj_user->distr->code = $v['分销二维码'];
				$obj_user->distr->realname = $v['分销真实姓名'];
				$obj_user->distr->idcard = $v['分销身份证号'];
				$obj_user->distr->paytype = "";
				$obj_user->distr->account = 0;
				$obj_user->distr->bank = $v['分销银行'];
				$obj_user->distr->bankid = $v['分销卡号'];
					
				foreach ($地址 as $kk=>$vv)
				{
					if ($v['序号'] == $vv['用户号'])
					{
						$obj_receive = json_decode(Er_Receive_Main);
						$receiveid = $this->createId();
							
						$obj_receive->_id = $receiveid;
						$obj_receive->uid = $userid[$v['序号']];
						$obj_receive->aid = floatval($vv['区域号']);
						$obj_receive->default = $vv['是默认']=='是' ? true : false;
						$obj_receive->name = $vv['收货人'];
						$obj_receive->mobile = $vv['电话'];
						$obj_receive->address = $vv['地址'];
							
						$this->createInsert($obj_receive, $bulk_receive);
					}
				}
				$this->createInsert($obj_user, $bulk_user);
			}
		}
		
		Mongo::write(DB::$main, COL::$Er_User, $bulk_user);
		Mongo::write(DB::$main, COL::$Er_Receive, $bulk_receive);
		
		// 返回
		return $this->Ok();
		//return $this->Data(array('page'=>$page, 'limit'=>$limit));
	}
}
?>
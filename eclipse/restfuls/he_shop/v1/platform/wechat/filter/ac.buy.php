<?php

class AcBuy extends Operate{

	public function working(&$data){
		
		// 取 购买 的活动数据
		$query_topic = $this->createQuery(['enable'=>true, 'time.start'=>['$lte'=>time()], 'time.end'=>['$gte'=>time()]]);
		$result_topic = &Mongo::query(DB::$main, COL::$Ac_Topic, $query_topic);
		
		// 取 专题 活动数据
		$query_buy = $this->createQuery(['enable'=>true, 'time.start'=>['$lte'=>time()], 'time.end'=>['$gte'=>time()]]);
		$result_buy = &Mongo::query(DB::$main, COL::$Ac_Buy, $query_buy);
		
		foreach ($data as $k=>$v)
		{
			//为商品数据填加 专题 活动的标签
			foreach ($result_topic as $topicKey=>$topicVal)
			{
				// 从ac.topic 表中提取出来活动的商品id数组
				$pid = array();
				foreach ($topicVal->product as $pVal)
					$pid[] = $pVal->pid;
				
				if (in_array($v->_id, $pid))
					$v->set[0]->tag->topicNmae = $topicVal->name;
			}
			
			//为商品数据填加 购买 活动的标签
			foreach ($result_buy as $buyKey=>$buyVal)
			{
				if (in_array($v->_id, $buyVal->pid))
					$v->set[0]->tag->buyNmae = $buyVal->name;
			}
		}
	}
}


?>
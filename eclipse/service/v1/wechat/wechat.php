<?php

/**
 * 微信功能类
 */
class Wechat extends MongoOperate {
	
	/**
	 * 微信配置
	 */
	private $option;
	
	/**
	 * 本平台取回的token
	 * 此token依赖于定时任务，千万注意，此类只读不写。token的写入完全合适定时任务完成
	 * 特别注意，有时候token并不是必须的，且取token需要读数据库，所以尽量不取
	 */
	private $token;
	
	/**
	 * 初始化
	 */
	public function __construct($option) {
		
		// 验证option为空值或不是对象
		if (is_null ( $option ) || is_object ( $option )) {
			w_err ( 'Wechat: option为null或不是对象!' );
		}
		
		// 验证成功，为属性赋值
		$this->option = $option;
	}
	
	
	public function qrcodeFollowCount(){
		
	}

}

?>
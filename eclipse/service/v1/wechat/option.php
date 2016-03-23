<?php

/**
 * 微信设置类
 * collection名称部分可以在初始化后重新赋新值(一般情况下不要修改)
 */
class WechatOption {

	// 本平台的appid编码
	public $appid;

	// 本平台的secret编码
	public $secret;

	/**
	 * 初始化，主要是传入参数，并写入collection全名称
	 */
	public function __construct($appid, $secret){

		// 验证appid, secret, database正确性
		if (empty($appid) || empty($secret)){
			w_erro('WechatOption: appid或secret为空串');
		}
		
		// 为基础属性赋值
		$this->appid = $appid;
		$this->secret = $secret;
	}
}
?>
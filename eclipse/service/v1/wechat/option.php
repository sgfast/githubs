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

	// 存放token的数据库名称
	public $database;

	// token collection名称(与database组合后的全名称)
	public $colToken;

	// token receive.text名称(与database组合后的全名称)
	public $colReceiveText;

	// token receive.news名称(与database组合后的全名称)
	public $colReceiveNews;

	// token template名称(与database组合后的全名称)
	public $colTemplate;

	// token qrcode名称(与database组合后的全名称)
	public $colQrcode;

	// token mainmenu名称(与database组合后的全名称)
	public $colMainMenu;

	/**
	 * 初始化，主要是传入参数，并写入collection全名称
	 */
	public function __construct($appid, $secret, $database){

		// 验证appid, secret, database正确性
		if (empty($appid) || empty($secret) || empty($database)){
			w_erro('WechatOption: appid、secret或database为空串');
		}
		
		// 为基础属性赋值
		$this->appid = $appid;
		$this->secret = $secret;
		$this->database = $database;
		
		// 为collection赋值
		$this->colToken = $this->database . '.token';
		$this->colReceiveText = $this->database . '.receive.text';
		$this->colReceiveNews = $this->database . '.receive.news';
		$this->colTemplate = $this->database . '.template';
		$this->colQrcode = $this->database . '.qrcode';
		$this->colMainMenu = $this->database . '.mainmenu';
	}
}
?>
<?php

/**
 * 此类服务于timer、cgi
 */
class WechatToken{
	
	// WechatOption对象
	private $option;
	
	// http对象
	private $http;
	
	/**
	 * 初始化
	 * @$option WechatOption对象
	 */
	public function __construct($option){
		$this->option = $option;
		$this->http = new Http();
	}
	
	/**
	 * 初始化token
	 * 此方法仅为从微信服务器取回token，后续操作，请在controller或其它终端进行
	 */
	private function getToken() {
		
		// 取appid, secret
		$appid = $this->option->appid;
		$secret = $this->option->secret;
		
		// 声明访问url
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
		
		// 取回str，并解析为对象
		$str = $this->http->request($url);
		$obj = json_decode($str);
		
		// 返回取回的token
		return $obj->access_token;
	}
}

?>
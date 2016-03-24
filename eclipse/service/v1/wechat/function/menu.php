<?php

/**
 * 菜单的创建与删除
 */
class WechatMenu{

	// token
	private $token;
	
	// http对象
	private $http;

	/**
	 * 初始化
	 * @$option WechatOption对象
	 */
	public function __construct($token){
		$this->token = $token;
		$this->http = new Http();
	}
	
	/**
	 * 创建微信菜单
	 * @$json 菜单的json数据
	 */
	public function createMenu($json){
		$url =  'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->token;
		return $this->http->request($url, $json);
	}
	
	/**
	 * 删除微信菜单
	 */
	public function deleteMenu(){
		$url =  'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $this->token;
		return $this->http->request($url);
	}
}

?>
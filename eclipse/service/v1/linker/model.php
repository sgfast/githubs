<?php

/**
 * 所有LinkerModel的基类
 */
class LinkerModel{
	public $valid;
	public function __construct(){
		$this->valid = LINKER::$valid;
	}
}

/**
 * WeauthLinkerModel
 */
class WeauthLinkerModel extends LinkerModel{
	public $appid;
	public $secret;
	public $url;
	public function __construct($appid, $secret, $url){
		parent::__construct();
		$this->appid = $appid;
		$this->secret = $secret;
		$this->url = $url;
	}
}
?>
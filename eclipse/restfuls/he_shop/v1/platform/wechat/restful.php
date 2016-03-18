<?php

/**
 * 设置可访问域名，包含类文件
 */
header ( "Access-Control-Allow-Origin: *" );
include '../../common/config.php';

/**
 * 权限部分
 */
class Auth extends Authorize {
	
	/**
	 * 需要验证的项
	 */
	protected function validates() {
		
		// 必须存在openid
		return [!is_null($_SESSION['openid'])];
	}
	
	/**
	 * 验证失败后的操作
	 */
	protected function callback() {
		echo '没有openid';
		exit;
	}
}

//new Auth ();

/**
 * Restful部分
 */
class Rest extends Restful {
	
	/**
	 * 构造方法
	 */
	public function __construct() {
		
		// 调用父类构造方法
		parent::__construct ();
		
		// 添加filter
		// $this->addFilter ( 'product/product/get_all', 'ac.buy.php', 'AcBuy' );
	}
}

new Rest ();

?>
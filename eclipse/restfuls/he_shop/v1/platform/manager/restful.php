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
	protected function validates() {
	}
	protected function callback() {
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
	}
	
	/**
	 * 初始化Filters
	 */
	public function initFilters() {
		
		// 添加filter
// 		$productFilters = [ 
// 				'product/product/all',
// 				'product/product/category',
// 				'product/product/brand' 
// 		];
// 		$this->addFilter ( $productFilters, 'activity.product.php', 'activityProduct' );
	}
}

new Rest ();

?>
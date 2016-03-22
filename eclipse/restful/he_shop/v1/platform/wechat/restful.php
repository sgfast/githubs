<?php

/**
 * 设置可访问域名，包含类文件
 */
header ( "Access-Control-Allow-Origin: *" );

/**
 * 定义需要导入的库文件，且导入common/config，再由common/config导入其它库
 */
$includeFiles = [
		'/config/config.php',
		'/utility/encrypt.php',
		'/utility/stroage.php',
		'/linker/linker.php',
		'/linker/subject/weauth.php',
		'/linker/subject/qiantai.php',
		'/linker/subject/feiyin.php',
		'/restful/authorize.php',
		'/restful/restful.php',
		'/restful/operate.php'
];
include '../../common/config.php';

/**
 * 权限部分
 */
class Auth extends Authorize {
	
	/**
	 * 需要验证的项
	 */
	protected function validates() {
		
		// 根据开发版和发布版进行验证
		if (os() == 'LINUX'){
			
			// linux版必须存在openid
			return [!is_null($_SESSION['openid'])];
		}else{
			
			// windows版设置一个虚假的openid并直接返回true
			$_SESSION['openid'] = 'vopenid';
			return [true];
		}
	}
	
	/**
	 * 验证失败后的操作
	 */
	protected function callback() {
		echo '没有openid';
		exit;
	}
}

/**
 * Restful部分
 */
class Rest extends Restful {
	
	/**
	 * 构造方法
	 * 注意：addFilter方法一定要放到parent::__construct()之前调用，否则无效
	 */
	public function __construct() {
		
		// 添加filter
		$this->addFollow ( 'order/cart/get_all', 'test.php', 'Test', ['doUser', 'doLog'] );
		
		// 调用父类构造方法
		parent::__construct ();
	}
}

/**
 * 执行Auth和Rest
 */
new Auth ();
new Rest ();

?>
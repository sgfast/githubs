<?php

/**
 * DIR配置
 */
class DIR{
	public static $debug = 'd:/restful/unity/service/';
	public static $production = '/server/webroot/unity/service/';
}

/**
 * INC配置
 */
class INC{

	// includes
	public static $includes = [
			'v1/config/config.php',
			'v1/utility/global.php',
			'v1/utility/stroage.php',
			'v1/restful/authorize.php',
			'v1/restful/restful.php',
			'v1/restful/controller.php',
	];
}

// 为包含数组赋值
$includeDir = DIR::$debug;
if (ENV !== 'DEBUG'){
	$includeDir = DIR::$production;
}

// 导入包含数组
foreach ( INC::$includes as $inc ) {
	include $includeDir . $inc;
}



/**
 * 基础配置
 */


/**
 * MongoDB配置
 */
class DB{
	
	// 主库连接字符串
	public static $main = 'mongodb://192.168.1.44:27017';
	
	// mysql 数据库连接字符串
	public static $Mr = array('host'=>"mysql:host=127.0.0.1;dbname=shop_mysql", 'user'=>"root", 'pwd'=>"123456");
	public static $Mw = array('host'=>"mysql:host=127.0.0.1;dbname=shop_mysql", 'user'=>"root", 'pwd'=>"123456");
}

/**
 * Redis配置
 */
class RS{
	
}



?>
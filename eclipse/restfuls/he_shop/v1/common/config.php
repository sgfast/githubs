<?php

/**
 * 引入的service路径、版本号、文件数组
 */
const INCLUDE_WINDOWS = 'D:/githubs/eclipse/unitys/service/';
const INCLUDE_LINUX = '/server/webroot/unity/service/';
const INCLUDE_VERSION = 'v1';

/**
 * 计算导入文件路径，注意，这里是无法使用os()函数的，所以，需要使用PATH_SEPARATOR来判断系统类型
 */
$includePath = PATH_SEPARATOR == ':' ? INCLUDE_LINUX : INCLUDE_WINDOWS;
$includePath .= INCLUDE_VERSION;

/**
 * 导入service/config/config.php，并调用该文件中的方法，自动载入其它文件
 */
include $includePath . '/config/config.php';
autoLoad($includePath);

/**
 * 日志文件夹，请确保具有写操作
 */
const LOG_WINDOWS = 'D:/temps/logs';
const LOG_LINUX = '/server/logs/web/shop';
$_LOG = PATH_SEPARATOR == ':' ? LOG_LINUX : LOG_WINDOWS;

/**
 * 微信配置
 */


/**
 * MongoDB配置
 */
class DB {
	public static $main = 'mongodb://127.0.0.1:27017';
	public static $log  = 'mongodb://127.0.0.1:27017';
}

/**
 * Redis配置
 */
class RS {
}

?>
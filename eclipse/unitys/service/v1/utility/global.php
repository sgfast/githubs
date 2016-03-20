<?php

/**
 * 全局PUT变量，此处提升为与post一致的位置，此属性需要写在global最上方，最早引入
 */
$_PUT = null;

/**
 * 全局LOG变量，此处提升为与post一致的位置，用于存放各种log
 */
$_LOG = '';

/**
 * 获取一个get参数
 * @$strParam 参数名称
 * @$strDefault 默认值
 * @$bSafe 是否进行字符串检测，默认true
 */
function get($strParam, $strDefault = '', $bSafe = true) {
	$result = '';
	if ($bSafe) {
		$result = strip_tags ( $_GET[$strParam] ?? $strDefault);
	} else {
		$result = $_GET[$strParam] ?? $strDefault;
	}
	if (is_numeric ( $result )) {
		return floatval ( $result );
	} else {
		return $result;
	}
}

/**
 * 获取一个post参数
 * @$strParam 参数名称
 * @$strDefault 默认值
 * @$bSafe 是否进行字符串检测，默认true
 */
function post($strParam, $strDefault = '', $bSafe = true) {
	$result = '';
	if ($bSafe) {
		$result = strip_tags ( $_POST[$strParam] ?? $strDefault);
	} else {
		$result = $_POST[$strParam] ?? $strDefault;
	}
	if (is_numeric ( $result )) {
		return floatval ( $result );
	} else {
		return $result;
	}
}

/**
 * 获取一个put参数
 * @$strParam 参数名称
 * @$strDefault 默认值
 * @$bSafe 是否进行字符串检测，默认true
 */
function put($strParam, $strDefault = '', $bSafe = true) {
	$result = '';
	if ($bSafe) {
		$result = strip_tags ( $_PUT[$strParam] ?? $strDefault);
	} else {
		$result = $_PUT[$strParam] ?? $strDefault;
	}
	if (is_numeric ( $result )) {
		return floatval ( $result );
	} else {
		return $result;
	}
}

/**
 * 获取操作系统类型
 * 如果在linux上返回"LINUX"，否则返回"WINDOWS"，在win上统一为开发版，在linux上统一为发布版。
 * 此函数主要用来判断加载路径
 */
function os() {
	if (PATH_SEPARATOR == ':') return 'LINUX';
	else return 'WINDOWS';
}

/**
 * 输出一个信息到页面
 * @$strContent
 */
function w_info($strContent) {
	echo $strContent;
	exit ();
}

/**
 * 输出一个错误到页面
 * @$strContent
 */
function w_err($strContent) {
	echo '错误信息：' , $strContent , '!';
	exit ();
}

/**
 * 写admin日志
 * $strContent 日志内容
 */
function w_log_admin($strContent) {
	$file = $_LOG . '/admin_' . date ( 'Ymd' ) . '.log';
	error_log ( date ( 'H:i:s' ) . ' ' . $strContent . "\r\n", 3, $file );
}

/**
 * 写data错误日志
 * $strContent 日志内容
 */
function w_log_data($strContent) {
	$file = $_LOG . '/data_' . date ( 'Ymd' ) . '.log';
	error_log ( date ( 'H:i:s' ) . ' ' . $strContent . "\r\n", 3, $file );
}

/**
 * 写pay日志
 * $strContent 日志内容
 */
function w_log_pay($strContent) {
	$file = $_LOG . '/pay_' . date ( 'Ymd' ) . '.log';
	error_log ( date ( 'H:i:s' ) . ' ' . $strContent . "\r\n", 3, $file );
}

/**
 * 取当前页面的php文件名(无扩展名)
 */
function self() {
	$name = substr ( $_SERVER['PHP_SELF'], strrpos ( $_SERVER['PHP_SELF'], '/' ) + 1 );
	return substr ( $name, 0, strrpos ( $name, '.php' ) );
}

/**
 * 取时间13位
 */
function time13() {
	return round ( microtime ( true ), 3 ) * 1000;
}

?>
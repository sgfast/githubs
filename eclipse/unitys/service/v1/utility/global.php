<?php

/*====================================================================================*/
/* 参数方法
 /*====================================================================================*/

/*------------------------------------------------------------------------------------*/
/* 获取一个get参数，如果此参数不存在，则返回默认值
 * $param：get参数的名称
 * $default：get参数的默认值，默认为空串
 * $safe：是否使用安全html过滤, 默认关闭
 * 返回: 参数存在则返回参数值，参数不存在则返回默认值
 /*------------------------------------------------------------------------------------*/
function get($param, $default = '', $safe = false)
{
	if (isset($_GET[$param]))
	{
		if ($safe)
			return strip_tags($_GET[$param]);
			else
				return $_GET[$param];
	}
	else
		return $default;
}
/*------------------------------------------------------------------------------------*/
/* 获取一个post参数，如果此参数不存在，则返回默认值
 * $param：post参数的名称
 * $default：post参数的默认值，默认为空串
 * $safe：是否使用安全html过滤, 默认关闭
 * 返回: 参数存在则返回参数值，参数不存在则返回默认值
 /*------------------------------------------------------------------------------------*/
function post($param, $default = '', $safe = false)
{
	if (isset($_POST[$param]))
	{
		if ($safe)
			return strip_tags($_POST[$param]);
			else
				return $_POST[$param];
	}
	else
		return $default;
}
/*------------------------------------------------------------------------------------*/
/* 获取或设置一个session，如果值为空串, 则为取session, 否则为设session
 * $param：session参数的名称
 * $value：值, 为空时返回此参数session的值
 * 返回: 设session时无返回, 取session时返回$_SESSION[$param]的值
 /*------------------------------------------------------------------------------------*/
function session($param, $value = '')
{
	if ($value != '')
		$_SESSION[$param] = $value;
		else
			return isset($_SESSION[$param]) ? $_SESSION[$param] : 0;
}
/*------------------------------------------------------------------------------------*/
/* 获取或设置一个cookie，如果值为空串, 则为取session, 否则为设session
 * $param：cookie参数的名称
 * $value：值, 为空时返回此参数cookie的值
 * 返回: 设cookie时无返回, 取cookie时返回$_COOKIE[$param]的值
 /*------------------------------------------------------------------------------------*/
function cookie($param, $value = '')
{
	if ($value != '')
		setcookie($param, $value);
		else
			return $_COOKIE[$param];
}


/** 
 * 获取一个get参数
 * @$strParam 参数名称
 * @$strDefault 默认值
 * @$bSafe 是否进行字符串检测，默认true 
 */
/* function get($strParam, $strDefault = '', $bSafe = true) {
	$result = '';
	if ($bSafe) {
		$result = strip_tags ( $_GET [$strParam] ?? $strDefault);
	} else {
		$result = $_GET [$strParam] ?? $strDefault;
	}
	if (is_numeric($result))
		return floatval($result);
	else 
		return $result;
	
} */

/**
 * 获取一个post参数
 * @$strParam 参数名称
 * @$strDefault 默认值
 * @$bSafe 是否进行字符串检测，默认true
 */
/* function post($strParam, $strDefault = '', $bSafe = true) {
	$result = '';
	if ($bSafe) {
		$result = strip_tags ( $_POST [$strParam] ?? $strDefault);
	} else {
		$result = $_POST [$strParam] ?? $strDefault;
	}
	if (is_numeric($result))
		return floatval($result);
	else
		return $result;
} */

/**
 * 返回一个仅包含成功消息的数组
 */
function ok() {
	return array (
			'status' => 'OK' 
	);
}

/**
 * 返回一个仅包含失败消息的数组
 */
function error($strMessage) {
	return array (
			'status' => $strMessage 
	);
}

/**
 * 将输出设置为成功，并写入数据
 * @arrData 需要写入输出流的数组
 */
function data($arrData) {
	return array (
			'status' => 'OK',
			'data' => $arrData 
	);
}

/**
 * 写入日志
 * $strDir 目录名
 * $strType 日志类型
 * $strContent 日志内容
 */
function wlog($strDir, $strType, $strContent) {
	$file = $strDir . '/' . $strType . '_' . date ( 'Ymd' ) . '.log';
	error_log ( date ( 'H:i:s' ) . ' ' . $strContent . "\r\n", 3, $file );
}

/**
 * 输出一个错误到页面, 注意, 非Debug模式下不输出
 *
 * @param string $strContent        	
 */
function werr($strContent) {
	echo $strContent;
	exit ();
}

/**
 * 取当前页面的php文件名(无扩展名)
 */
function self() {
	$name = substr ( $_SERVER ['PHP_SELF'], strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1 );
	return substr ( $name, 0, strrpos ( $name, '.php' ) );
}

/**
 * 取时间13位
 */
function time13() {
	return round ( microtime ( true ), 3 ) * 1000;
}

/**
 * 快速返回一个以Ymd编码的时间
 * @$time 传入的时间戳，默认0
 */
/* function Ymd(int $time = 0) {
	$t = $time > 0 ? $time : time ();
	return date ( 'Y-m-d', $t );
}
 */
/**
 * 快速返回一个以YmdHi编码的时间
 * @$time 传入的时间戳，默认0
 */
/* function YmdHi(int $time = 0) {
	$t = $time > 0 ? $time : time ();
	return date ( 'Y-m-d H:i', $t );
} */


?>
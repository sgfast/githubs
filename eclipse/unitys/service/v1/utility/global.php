<?php

/**
 * 全局PUT变量，此处提升为与post一致的位置，此属性需要写在global最上方，最早引入
 */
$_PUT = null;

/** 
 * 获取一个get参数
 * @$strParam 参数名称
 * @$strDefault 默认值
 * @$bSafe 是否进行字符串检测，默认true 
 */
function get($strParam, $strDefault = '', $bSafe = true) {
	$result = '';
	if ($bSafe) {
		$result = strip_tags ( $_GET [$strParam] ?? $strDefault);
	} else {
		$result = $_GET [$strParam] ?? $strDefault;
	}
	if (is_numeric($result)){
		return floatval($result);
	}else{ 
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
		$result = strip_tags ( $_POST [$strParam] ?? $strDefault);
	} else {
		$result = $_POST [$strParam] ?? $strDefault;
	}
	if (is_numeric($result)){
		return floatval($result);
	}else{
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
		$result = strip_tags ( $_PUT [$strParam] ?? $strDefault);
	} else {
		$result = $_PUT [$strParam] ?? $strDefault;
	}
	if (is_numeric($result)){
		return floatval($result);
	}else{
		return $result;
	}
}

/**
 * 获取操作系统类型
 * 如果在linux上返回"LINUX"，否则返回"WINDOWS"，在win上统一为开发版，在linux上统一为发布版。
 * 此函数主要用来判断加载路径
 */
function os(){
	if(PATH_SEPARATOR == ':') echo 'LINUX';
	else echo 'WINDOWS';
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
	exit;
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
function Ymd(int $time = 0) {
	$t = $time > 0 ? $time : time ();
	return date ( 'Y-m-d', $t );
}

/**
 * 快速返回一个以YmdHi编码的时间
 * @$time 传入的时间戳，默认0
 */
function YmdHi(int $time = 0) {
	$t = $time > 0 ? $time : time ();
	return date ( 'Y-m-d H:i', $t );
}

/**
 * http(https)访问
 * @param string $url 需要访问的url
 * @param array $data 如果为post访问，则带此数据，默认为null
 */
function http($url, $data=null)
{
	// 设置curl
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	if (!empty($data))
	{
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	// 取值并关闭curl
	$output = curl_exec($curl);
	curl_close($curl);
	
	// 返回内容
	return $output;
}

/**
 * http(https)下载
 * @param string $url 需要访问的url
 * @param string $fileName 文件保存全路径
 * @see 注意，此函数并没有
 */
function download($url, $fileName)
{
	// 设置curl
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_NOBODY, 0);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	// 取值
	$output = curl_exec($curl);
	$info = curl_getinfo($curl);
	curl_close($curl);

	// 获取httpFile的头部消息和实际内容（header为冗余，以后备用）
	$httpFile = array_merge(['body'=>$output], ['header'=>$info]);
	
	// 打开本地文件句柄，并试图写入文件
	$localFile = fopen($fileName, 'w');
	if (false != $localFile){
		
		// 如果写入成功，关闭本地文件，并返回true
		if (false !== fwrite($localFile, $httpFile['body'])){
			fclose($localFile);
			return true;
		}
	}
	
	// 下载失败返回false
	return false;
}


?>
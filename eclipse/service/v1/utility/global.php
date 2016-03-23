<?php

// 全局PUT变量，此处提升为与post一致的位置，此属性需要写在global最上方，最早引入
$_PUT = [ ];

// 全局LOG变量，此处提升为与post一致的位置，用于存放各种log
$_LOG = '';

/**
 * 获取一个get参数
 * @$strParam 参数名称
 * @$strDefault 默认值
 * @$bSafe 是否进行字符串检测，默认true
 */
function get($strParam, $strDefault = '', $bSafe = true) {
	$result = $_GET[$strParam] ?? $strDefault;
	return param ( $result, $bSafe );
}

/**
 * 获取一个post参数，参数同上
 */
function post($strParam, $strDefault = '', $bSafe = true) {
	$result = $_POST[$strParam] ?? $strDefault;
	return param ( $result, $bSafe );
}

/**
 * 获取一个put参数，参数同上
 */
function put($strParam, $strDefault = '', $bSafe = true) {
	$result = $_PUT[$strParam] ?? $strDefault;
	return param ( $result, $bSafe );
}

/**
 * 获取参数的后半部执行方法，用于过滤危险字符，并检查是否为数字类型，如果是数字类型则返回float类型数据
 * @$result 已经通过$_XXX变量取回的值
 * @$bSafe 是否过滤危险字符串
 */
function param($result, $bSafe) {
	if ($bSafe) {
		$result = strip_tags ( $result );
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
	echo '错误信息：', $strContent, '!';
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
 * 取本次访问的方法，小写
 */
function method() {
	return strtolower ( $_SERVER['REQUEST_METHOD'] );
}

/**
 * 取本次访问的router: master/controller形式，无php扩展名
 */
function router() {
	
	// 分解get参数
	$master = get ( 'master' );
	$controller = get ( 'controller' );
	
	// 注意此处有router的检查，如果$master或$controller===''，则说明没有按标准带参数。则要报错退出
	if (empty ( $master ) || empty ( controller )) {
		w_err ( 'master或controller为空串' );
	}
	
	// 组合router，注意此值不带.php文件扩展名
	return "${master}/${controller}";
}

/**
 * 取本次访问的action
 */
function action() {
	
	// 获取action参数
	$action = get ( 'action' );
	
	// 此处注意action的检查，如果$action===''，则说明没有按标准带参数。则要报错退出
	if (empty ( $action )) {
		w_err ( 'action为空串' );
	}
	
	// 返回action
	return $action;
}

/**
 * 根据object或array组装一个使用特殊符号连接的字符串
 */
function assemble($char, $objOrArr) {
	
	// 验证objOrArr
	if (!isset($objOrArr) || is_null ( $objOrArr )) {
		w_err ( 'assemble: objOrArr未定义或为null' );
		if (! (is_object ( $objOrArr ) || is_array ( $objOrArr ))) {
			w_err ( 'assemble: objOrArr不是对象或数组' );
		}
	}
	
	// 组装并返回
	$result = '';
	foreach ( $objOrArr as $v ) {
		if ($result !== '') {
			$result .= $char;
		}
		$result .= $v;
	}
	return $result;
}

/**
 * 载入文件
 */
function autoLoad($includeDir, $includeFiles) {
	
	// 验证dir是否有效
	if (empty ( $includeDir ) || is_null ( $includeFiles ) || ! is_array ( $includeFiles )) {
		w_err ( 'global.php: autoLoad: dir为空串或includeFiles不是数组' );
	}
	
	// 遍历导入文件，注意这里的INCLUDE_FILES，是第3层config(平台层)中定义的
	foreach ( $includeFiles as $file ) {
		include $includeDir . $file;
	}
}

/**
 * 取时间13位
 */
function time13() {
	return round ( microtime ( true ), 3 ) * 1000;
}

?>
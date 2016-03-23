<?php

// 设置编码格式为utf-8
header ( "Content-type: text/html; charset=utf-8" );

// 打开session
session_start ();

// 设置断言
assert_options ( ASSERT_ACTIVE, true );
assert_options ( ASSERT_BAIL, true );
assert_options ( ASSERT_WARNING, false );

/**
 * 安全配置（加解密key,iv,valid），注意此类一但确定下来，就尽量不要更改
 */
class LINKER{
	public static $key = 'I*3$!5jS6a@-)!#I';
	public static $iv = '*iA9B7%*';
	public static $valid = 'V%7&3(#@2a!@sHV';
}

/**
 * ComponentURL配置
 */
class COMPONENT_URL {

	public static $weauth = 'http://weauth.comp.hesq.com.cn';
	public static $wepay = 'http://wepay.comp.hesq.com.cn';
	public static $alipay = 'http://alipay.comp.hesq.com.cn';
	public static $qiantai = 'http://qiantai.comp.hesq.com.cn';
	public static $feiyin = 'http://feiyin.comp.hesq.com.cn';
	public static $upload = 'http://upload.comp.hesq.com.cn';
}



?>
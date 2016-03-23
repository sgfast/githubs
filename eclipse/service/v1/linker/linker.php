<?php

/**
 * 使用此类前必须载入全局config，即service下的config/config.php
 * encode: 会执行打包过程，即将linker分解为字符串并使用'|'连接，并进行加密、urlencode，返回打包过后的params
 * decode: 会执行解包过程，即将params进行urldecode、解密，然后分解为一个实际的linkerModel子类返回
 * 		   decode方法的最后，会进行valid的验证，只要不是本系统通过new LinkerModel子类打包的linker，均不能通过验证
 * 		       在各个实际使用此类的代码环境中，均不需要再验证valid。注意此验证不显示具体错误消息，只显示“拒绝服务”
 */
abstract class Linker {
	
	// 加解密对象
	protected $encrypt;
	
	/**
	 * 构造，实例化encrypt
	 */
	public function __construct() {
		$this->encrypt = new Encrypt ( LINKER::$key, LINKER::$iv );
	}
	
	/**
	 * 传入linker，返回此linker编码出的params字符串（注意：urlencode最终取回的结果）
	 * @$linker 已经配置好的linkerModel对象
	 */
	public function encode($linker) {
		
		// 打包并返回
		$assemble = assemble ( '|', $linker );
		return urlencode ( $this->encrypt->encrypt ( $assemble ) );
	}
	
	/**
	 * 传入params，返回此params解码出的linker对象（注意：urldecode(params)）
	 * 此方法会调用一个"子类"的实际的解析方法，并返回一个LinkerModel子类对象
	 * @$params 已经编码过的get参数
	 */
	public function decode($params) {
		
		// 解包
		$params = urldecode ( $this->encrypt->decrypt ( $params ) );
		$linker = $this->paramsToLinker ( $params );
		
		// 验证linker的valid参数是否正确
		if (! isset ( $linker->valid ) || is_null ( $linker->valid )) {
			w_err ( '拒绝服务' );
			if ($linker->valid !== LINKER::$valid) {
				w_err ( '拒绝服务' );
			}
		}
		
		// 返回linker
		return $linker;
	}
	protected abstract function paramsToLinker($params);
}

/**
 * WeauthLinker
 */
class WeauthLinker extends Linker {
	public function __construct() {
		parent::__construct ();
	}
	protected function paramsToLinker($params) {
		
		// 将encode分解为数组
		$arr = explode ( '|', $params );
		
		// 将解密后的值放入Model
		$linker = new WeauthLinkerModel ();
		$linker->appid = $arr[0];
		$linker->screct = $arr[1];
		$linker->url = $arr[2];
		
		// 返回
		return $linker;
	}
}

?>
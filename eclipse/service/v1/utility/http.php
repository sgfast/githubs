<?php

/**
 * http工具类
 */
class Http{
	
	/**
	 * http(https)访问
	 * @param string $url 需要访问的url
	 * @param array $data 如果为post访问，则带此数据，默认为null */
	public function request($url, $data = null) {
	
		// 设置curl
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
		if (! empty ( $data )) {
			curl_setopt ( $curl, CURLOPT_POST, 1 );
			curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data );
		}
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
	
		// 取值并关闭curl
		$output = curl_exec ( $curl );
		curl_close ( $curl );
	
		// 返回内容
		return $output;
	}
	
	/**
	 * http(https)下载
	 * @param string $url 需要访问的url
	 * @param string $fileName 文件保存全路径
	 * @see 注意，此函数并没有 */
	public function download($url, $fileName) {
	
		// 设置curl
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_HEADER, 0 );
		curl_setopt ( $curl, CURLOPT_NOBODY, 0 );
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
	
		// 取值
		$output = curl_exec ( $curl );
		$info = curl_getinfo ( $curl );
		curl_close ( $curl );
	
		// 获取httpFile的头部消息和实际内容（header为冗余，以后备用）
		$httpFile = array_merge ( [
				'body'=> $output ], [
						'header'=> $info ] );
	
		// 打开本地文件句柄，并试图写入文件
		$localFile = fopen ( $fileName, 'w' );
		if (false != $localFile) {
				
			// 如果写入成功，关闭本地文件，并返回true
			if (false !== fwrite ( $localFile, $httpFile['body'] )) {
				fclose ( $localFile );
				return true;
			}
		}
	
		// 下载失败返回false
		return false;
	}
}
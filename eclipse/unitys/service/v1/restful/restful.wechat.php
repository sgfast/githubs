<?php

/**
 * RestfulWechat类
 * 专门用于包含微信功能的restful类，主要用于统计微信二维码带来的用户统计
 * 因为这些数据结构都保存在service/wechat/wechat.php文件中，所以，由Wechat类独立完成这些操作
 * 本类继承Restful以后，另行放置一个Wechat对象，用于记录这些操作
 * 
 * 注意：此功能要与前台angularjs的ajax功能配合使用，因为调用是通过ajax进行的
 *  */
class RestfulWechat extends Restful {
	
	/**
	 * wechat对象
	 */
	private $wechat;
	
	/**
	 * 构造方法
	 */
	public function __construct($wechatOption){
		
		// 调用父类构造方法
		parent::__construct();
		
		// 检查wechatOption的正确性
		if (is_null($wechatOption) || !is_object($wechatOption)){
			w_err('RestfulWechat: wechatOption为null或不是对象');
		}
		
		// 初始化wechat
		$this->wechat = new Wechat($wechatOption);
		
		// 使用wechat进行带有参数访问的
		$this->wechat->qrcodeVisitedCount();
	}
}

?>

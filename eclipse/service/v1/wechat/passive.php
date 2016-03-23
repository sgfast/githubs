<?php

/**
 * 微信被动回复功能，用户输入模型类
 */
class WechatPassiveInput{

	// 消息类型
	public $type;

	// 消息内容
	public $content;

	// 来自于某个用户的openid
	public $from;

	// 到达某个用户的openid(公众号的openid)
	public $to;

	/**
	 * 构造方法
	 */
	public function __construct(){

		// 获取post文本内容
		$post = file_get_contents('php://input');

		// 非空验证
		if (!empty($post)){

			// 解析xml，并放入obj对象中
			libxml_disable_entity_loader(true);
			$obj = simplexml_load_string($post, 'SimpleXMLElement', LIBXML_NOCDATA);

			// 为属性赋值
			$this->type = $obj->MsgType;
			$this->content = $obj->Content;
			$this->from = $obj->FromUserName;
			$this->to = $obj->ToUserName;
		}
	}
}

/**
 * 所有WechatReceiveText, WechatReceiveImage, WechatReceiveNews, WechatReceiveNewsItem的基类，拥有output属性
 */
class WechatPassiveOutput {
	
	// 最终的字符串
	private $result;
	
	/**
	 * 构造方法
	 */
	public function __construct($result) {
		$this->result = $result;
	}
	
	/**
	 * 输出
	 */
	public function output() {
		echo $this->result;
	}
}

/**
 * 微信被动回复功能，文本模型类
 */
class WechatPassiveText extends WechatPassiveOutput {
	public function __construct($to, $from, $content) {
		
		// 定义模板
		$template = '
		<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[%s]]></Content>
		</xml>';
		
		// 父类构造
		parent::__construct ( sprintf ( $template, $to, $from, time (), $content ) );
	}
}

/**
 * 微信被动回复功能，图片模型类
 */
class WechatPassiveImage extends WechatPassiveOutput {
	public function __construct($to, $from, $media_id) {
		
		// 定义模板
		$template = '
		<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[image]]></MsgType>
			<Image>
				<MediaId><![CDATA[$s]]></MediaId>
			</Image>
		</xml>';
		
		// 父类构造
		parent::__construct ( sprintf ( $template, $to, $from, time (), $media_id ) );
	}
}

/**
 * 微信被动回复功能，新闻模型类
 */
class WechatPassiveNews extends WechatPassiveOutput {
	public function __construct($to, $from, $items) {
		
		// 验证items是否为null，且必须为数组
		if (is_null ( $items ) || ! is_array ( $items )) {
			$receive = new WechatReceiveText ( $to, $from, '错误消息：items必须不能为null，且必须为数组!' );
			w_err ( $receive->output );
		}
		
		// 定义模板，注意，news的template较为复杂，需要引入items数组
		$template = '
		<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>%s</ArticleCount>
			<Articles>';
		
		// 为template追加items数组内容
		foreach ( $items as $item ) {
			$template .= $item->output;
		}
		
		// 为template追加结尾
		$template .= '</Articles></xml>';
		
		// 父类构造
		parent::__construct ( sprintf ( $template, $to, $from, time (), count ( $items ) ) );
	}
}

/**
 * 微信被动回复功能，新闻条目模型类
 */
class WechatPassiveNewsItem extends WechatPassiveOutput {
	public function __construct($title, $description, $picurl, $url) {
		
		// 定义模板
		$template = '
		<item>
			<Title><![CDATA[$s]]></Title>
			<Description><![CDATA[$s]]></Description>
			<PicUrl><![CDATA[$s]]></PicUrl>
			<Url><![CDATA[$s]]></Url>
		</item>';
		
		// 父类构造
		parent::__construct ( sprintf ( $title, $description, $picurl, $url ) );
	}
}
?>
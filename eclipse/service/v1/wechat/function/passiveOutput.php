<?php


/**
 * 所有WechatReceiveText, WechatReceiveImage, WechatReceiveNews, WechatReceiveNewsItem的基类，拥有output属性
 */
class WechatPassiveOutputBase {

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
class WechatPassiveOutputText extends WechatPassiveOutputBase {
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
class WechatPassiveOutputImage extends WechatPassiveOutputBase {
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
class WechatPassiveOutputNews extends WechatPassiveOutputBase {
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
class WechatPassiveOutputNewsItem extends WechatPassiveOutputBase {
	public function __construct($title, $description, $picurl, $url) {

		// 定义模板
		$template = '
		<item>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
		</item>';

		// 父类构造
		parent::__construct ( sprintf ( $title, $description, $picurl, $url ) );
	}
}

?>
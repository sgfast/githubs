<?php

/**
 * 专用于微信cgi的基类，不包含任何数据操作，只有功能代码
 * 此类包装了WechatPassiveInput, WechatPassiveOutput，并转为private方法，使其在本类中更易使用
 * 加入了cgi特有的验证环节（只需传入cgiToken， 自动验证）
 * 
 * cgiToken：请置于restful/common/config.php中
 * 
 * 数据结构如下:
 * we.passive = {
 * 		"_id": "",									// id
 * 		"name": "",									// 本次活动的名称，列表容易显示。且被动回复不仅在用户输入后回复，还可能用在自定义菜单等地方，所以，要有一个名称进行统一设置
 * 		"trigger": "",								// 触发类型，可选项为：'input', 'click', 'subscribe', 'qrcode'
 *		"type": "",									// 发送消息类型，可选项为：'text', 'image', 'news', 'special'
 *		"key": "",									// input触发时：|分隔的字符串，在操作中，会被分解为数组；menu触发时：为menu的key值；qrcode触发时：为qrcode的key值；当type为special时：key用于调用方法时的选择
 *		"text": "",									// 当type为text时，回复给用户的消息内容
 *		"image": "",								// 当type为image时，回复给用户的图片media_id（微信公众号资源id）
 *		"news": [									// 当type为news时，回复给用户的news内容
 *			"ident": 0,
 *			"title": "",							// 新闻标题
 *			"description": "",						// 新闻说明
 *			"picurl": "",							// 新闻使用的图片url
 *			"url": ""								// 新闻点击后链接到的url
 *		]
 *	}
 */
abstract class Wecgi extends MongoOperate {
	
	// cgiToken: 此token是订死的，由子类传入
	private $cgiToken;
	
	// wechatToken: 从次从数据库中读取，使用本类模板方法获取
	private $token;
	
	// data，注意，每个cgi面对的数据结构是一致的，请参考数据库设计
	private $data;
	
	// 被动回复input
	protected $input;
	
	/**
	 * 初始化
	 * @param unknown $cgiToken
	 */
	public function __construct($cgiToken) {
		
		// 初始化时即接收cgiToken，并验证此cgi是否正确（其实只验证一次，为了方便，每次调用）
		$this->cgiToken = $cgiToken;
		$this->cgiValid ();
		
		// 实例化WechatPassiveInput，在继承类中容易使用
		$this->input = new WechatPassiveInput ();
		
		// 取本次操作的wechatToken和data
		$this->token = $this->getToken ();
		$this->data = $this->getData ();
		
		// 开始工作，回复用户消息
		$this->passiveOutput ();
	}
	
	/**
	 * 从数据库中取出wechatToken（必须重写）
	 */
	protected abstract function getToken() {}
	
	/**
	 * 从数据库中取出passive调用的数据（必须重写）
	 */
	protected abstract function getData() {}
	
	/**
	 * 回复用户消息的主调接口（可重写）
	 */
	protected function passiveOutput() {
		
		// 根据消息类型，转到不同的参数
		if ($this->input->type === 'text' || $this->input->type === 'image'){
			$this->passiveInput();
		}else{
			
			// event类型的事件，要查看$this->input->event
			if ($this->input->event === 'click'){
				
			}
		}
	}
	
	/**
	 * 回复input被动消息（可重写）
	 */
	protected function passiveInput() {}
	
	/**
	 * 回复menu被动消息（可重写）
	 */
	protected function passiveClick() {}
	
	/**
	 * 回复Subscribe被动消息（可重写）
	 */
	protected function pasiveSubscribe() {}
	
	/**
	 * 回复二维码扫描被动消息（可重写）
	 */
	protected function passiveScan() {}
	
	/**
	 * 回复特别指定的消息，通常是复杂消息需要通过编程方式实现（可重写）
	 */
	protected function passiveSpecial($key) {}
	
	/**
	 * 输出文本消息
	 */
	protected function outputText($content) {
		$text = new WechatPassiveOutputText ( $this->from, $this->to, $content );
		$text->output ();
	}
	
	/**
	 * 输出图片消息
	 */
	protected function outputImage($media_id) {
		$image = new WechatPassiveOutputImage ( $this->from, $this->to, $media_id );
		$image->output ();
	}
	
	/**
	 * 输出图文消息
	 */
	protected function outputNews($arr) {
		
		// 取items
		$items = [ ];
		foreach ( $arr as $v ) {
			$item = new WechatPassiveOutputNewsItem ( $v[0], $v[1], $v[2], $v[3] );
			$items[] = $item->result;
		}
		
		// 取news并输出
		$news = new WechatPassiveOutputNews ( $this->from, $this->to, $items );
		$news->output ();
	}
	
	/**
	 * cgi验证
	 */
	private function cgiValid() {
		
		// 取echoStr，并验证是否需要valid
		$echoStr = get ( 'echostr' );
		if (empty ( $echoStr )) {return;}
		
		// 验证token是否存在
		if (! $this->cgiToken) {
			w_err ( 'TOKEN is not defined!' );
		}
		
		// 取参数
		$signature = get ( 'signature' );
		$timestamp = get ( 'timestamp' );
		$nonce = get ( 'nonce' );
		
		// 验证
		$tmpArr = array (
				$this->token,
				$timestamp,
				$nonce );
		sort ( $tmpArr, SORT_STRING );
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		
		// 如果验证正确，则输出echoStr（即表示接入正确）
		if ($tmpStr == $signature) {
			w_info ( $echoStr );
		}
	}
}

?>
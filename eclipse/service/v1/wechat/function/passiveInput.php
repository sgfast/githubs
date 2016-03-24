<?php

/**
 * 微信被动回复功能，用户输入模型类
 * 使用模式为“包含”，即WechatPassiveInput中包含一个用户输入的对象，此对象是使用$this->type进行筛选出来的不同类对象
 * 利用的是php的动态处理能力，在php中，不一定非要具备继承关系才能进行类对象的相互替换
 */
class WechatPassiveInput{

	// 输入对象
	public $input;
	
	// 输入类型
	public $type;
	
	// 当输入类型为event时，事件的名称
	public $event;
	
	// 私有的从post取回的xml转换而来的php object
	private $obj;

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
			$this->obj = simplexml_load_string($post, 'SimpleXMLElement', LIBXML_NOCDATA);
			
			// 调用initInput
			$this->initInput();
		}
	}
	
	private function initInput(){
		
		// 取出type
		$this->type = strtolower($this->obj->MsgType);
			
		// 根据type为input初始化为不同的类对象
		switch ($type){
			case 'text':
				$this->input = new WechatPassiveInputText($this->obj);
				break;
			case 'image':
				$this->input = new WechatPassiveInputImage($this->obj);
				break;
			case 'event':
				$this->initInputEvent();
				break;
			default:
				break;
		}
	}
	
	/**
	 * 初始化event类型输入对象
	 */
	private function initInputEvent(){
		
		// 如果为event类型，则要记录$this->event的值，并根据event进行处理
		$this->event = strtolower($this->obj->Event);
		if ($this->event === 'click'){
			$this->input = new WechatPassiveInputClick();
		}else {
		
			// 如果不是click事件，则为关注、取消关注、扫描二维码事件
			// 此类事件比较复杂，看是否带有EventKey，如果带有EventKey，则其实是二维码扫描事件
			// 如果仅是关注与取消关注事件，则初始化为WechatPassiveInputBase即可，因为此时只需要取回from属性即可
			if (isset($this->obj->EventKey)){
				$this->input = new WechatPassiveInputScan($this->obj);
			}else{
				$this->input = new WechatPassiveInputBase($this->obj);
			}
		}
	}
}

/**
 * 所有输入对象类的基类，拥有from, to属性
 */
class WechatPassiveInputBase{
	public $from;
	public $to;
	public function __construct($obj){
		$this->from = $obj->FromUserName;
		$this->to = $obj->ToUserName;
	}
}

/**
 * 用户输入模型类
 */
class WechatPassiveInputText extends WechatPassiveInputBase{
	public $content;
	public function __construct($obj){
		parent::__construct($obj);
		$this->content = $obj->Content;
	}
}

/**
 * 用户发送图片输入类型
 */
class WechatPassiveInputImage extends WechatPassiveInputBase{
	public $picurl;
	public $media_id;
	public function __construct($obj){
		parent::__construct($obj);
		$this->picurl = $obj->PicUrl;
		$this->media_id = $obj->MediaId;
	}
}

/**
 * 点击菜单事件输入类型
 */
class WechatPassiveInputClick extends WechatPassiveInputBase{
	public $key;
	public function __construct($obj){
		parent::__construct($obj);
		$this->key = $obj->EventKey;
	}
}

/**
 * 扫描事件输入类型
 */
class WechatPassiveInputScan extends WechatPassiveInputBase{
	public $key;
	public $ticket;
	public function __construct($obj){
		parent::__construct($obj);
		$this->key = $obj->EventKey;
		$this->ticket = $obj->Ticket;
	}
}

?>
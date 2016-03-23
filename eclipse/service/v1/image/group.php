<?php

/**
 * 所有GroupChild子类型的基类，存在x,y两个属性
 */
class ImageGroupChild{
	public $x;					// x坐标
	public $y;					// y坐标
}

/**
 * 文字child模型类
 */
class ImageGroupChildText extends GroupImageChild{
	public $size;				// 字体大小
	public $family;				// 字体
	public $text;				// 输出的文字
}

/**
 * 图片child模型类
 */
class ImageGroupChildImage extends GroupImageChild{
	public $width;				// 宽
	public $height;				// 高
	public $image;				// 输出的图片
}

/**
 * 组图片操作类
 */
class ImageGroup{
	
	private $width;				// 生成图片的宽
	private $height;			// 生成图片的高
	private $background;		// 图片的背景
	private $childTexts;		// 子文字对象数组
	private $childImages;		// 子图片对象数组 
	
	/**
	 * 构造方法
	 */
	public function __construct($width, $height, $background){
		$this->width = $width;
		$this->height = $height;
		$this->background = $background;
	}
	
	/**
	 * 添加一个文字子显示对象
	 * @$childText 子显示文字对象
	 */
	public function addText($childText){
		if (is_null($this->childTexts)){
			$this->childTexts = [];
		}
		$this->childTexts[] = $childText;
	}
	
	/**
	 * 添加一个图片子显示对象
	 * @$childImage 子显示图片对象
	 */
	public function addImage($childImage){
		if (is_null($this->childImages)){
			$this->childImages = [];
		}
		$this->childImages[] = $childImage;
	}
	
	/**
	 * 最终组合，并保存为一个地址返回
	 */
	public function grouping(){
		
		
		
		return '';
	}
}


?>
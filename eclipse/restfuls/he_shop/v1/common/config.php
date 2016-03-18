<?php

/**
 * 引入的service路径、版本号、文件数组
 */
const INCLUDE_WINDOWS = 'D:/githubs/eclipse/unitys/service/';
const INCLUDE_LINUX = '/server/webroot/unity/service/';
const INCLUDE_VERSION = 'v1';
const INCLUDE_FILES = [ 
		'/config/config.php',
		'/linker/linker.php',
		'/linker/subject/weauth.php',
		'/linker/subject/qiantai.php',
		'/linker/subject/feiyin.php',
		'/restful/authorize.php',
		'/restful/restful.php',
		'/restful/operate.php',
		'/utility/efficiency.php',
		'/utility/encrypt.php',
		'/utility/global.php',
		'/utility/stroage.php'		
];

// 计算导入文件路径，导入包含数组
$includePath = os() == 'LINUX' ? INCLUDE_LINUX : INCLUDE_WINDOWS;
$includePath .= INCLUDE_VERSION;
foreach ( INCLUDE_FILES as $file ) {
	include $includePath . $file;
}

/**
 * MongoDB配置
 */
class DB {
	public static $main = 'mongodb://127.0.0.1:27017';
	public static $log  = 'mongodb://127.0.0.1:27017';
}

/**
 * Redis配置
 */
class RS {
}

/**
 * 数据集合静态类 
 */
class COL{

	/**
	 * 广告部分 */
	public static $Ad_Swipe =		'shop.ad.swipe';
	public static $Ad_Style =		'shop.ad.style';
	public static $Ad_Category =	'shop.ad.category';
	public static $Ad_Notice =		'shop.ad.notice';

	/**
	 * 商品部分 */
	public static $Pt_Category =    'shop.pt.category';
	public static $Pt_Brand =       'shop.pt.brand';
	public static $Pt_Product =     'shop.pt.product';
	public static $Pt_Comment =     'shop.pt.comment';

	/**
	 * 用户部分 */
	public static $Er_User =        'shop.er.user';
	public static $Er_Message =     'shop.er.message';
	public static $Er_Coupon =      'shop.er.coupon';
	public static $Er_Receive =     'shop.er.receive';

	/**
	 * 订单部分 */
	public static $Sp_Cart =        'shop.sp.cart';
	public static $Sp_Order =       'shop.sp.order';
	public static $Sp_Item =        'shop.sp.item';
	public static $Sp_Express =     'shop.sp.express';

	/**
	 * 活动部分 */
	public static $Ac_Order =    	'shop.ac.order';
	public static $Ac_Buy =			'shop.ac.buy';
	public static $Ac_Topic =     	'shop.ac.topic';
}

/**
 * 文件集合静态类 
 */
class MOD{

	/**
	 * 广告部分 */
	public static $Ad_Swipe =		'advert/swipe.php';
	public static $Ad_Style =		'advert/style.php';
	public static $Ad_Category =	'advert/category.php';
	public static $Ad_Notice =		'advert/notice.php';

	/**
	 * 商品部分 */
	public static $Pt_Category =    'product/category.php';
	public static $Pt_Brand =       'product/brand.php';
	public static $Pt_Product =     'product/product.php';
	public static $Pt_Comment =     'product/comment.php';

	/**
	 * 用户部分 */
	public static $Er_User =        'user/user.php';
	public static $Er_Message =     'user/message.php';
	public static $Er_Coupon =      'user/coupon.php';
	public static $Er_Receive =     'user/receive.php';

	/**
	 * 订单部分 */
	public static $Sp_Cart =        'order/cart.php';
	public static $Sp_Order =       'order/order.php';
	public static $Sp_Item =        'order/item.php';
	public static $Sp_Express =     'order/express.php';
}

?>
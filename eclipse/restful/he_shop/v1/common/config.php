<?php

// 计算导入文件路径，注意，这里是无法使用os()函数的，所以，需要使用PATH_SEPARATOR来判断系统类型
// 导入utility/globla.php，并调用该文件中的方法，自动载入其它文件
// 注：includeFiles变量，由各个组件文件编写
$includePath = PATH_SEPARATOR == ':' ? '/server/webroot/unity/service/' : 'D:/githubs/eclipse/unitys/service/';
$includePath .= 'v1';
include $includePath . '/utility/globla.php';
autoLoad($includePath, $includeFiles);

// 定义日志目录，请确保具有写操作
$_LOG = os() == 'LINUX' ? '/server/logs/web/shop' : 'D:/temps/logs';


// 微信配置


// MongoDB配置
class DB {
	public static $main = 'mongodb://127.0.0.1:27017';
	public static $log  = 'mongodb://127.0.0.1:27017';
}

// Redis配置
class RS {
}

?>
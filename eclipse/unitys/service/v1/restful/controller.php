<?php

/**
 * 继承自Operate，用于Controller的特殊实现
 * 这里使用的$_PUT，写在global.php的最上方
 */
class Controller extends MongoOperate{
	
	/**
	 * 初始化，解决put问题
	 */
	public function __construct(){
		
		// 检测此次的访问行为是否为put，如果为put，则为$_PUT赋值
		if (strtolower ( $_SERVER ['REQUEST_METHOD'] ) === 'put'){
			global $_PUT;
			parse_str(file_get_contents('php://input'), $_PUT);
		}
	}
	
	/**
	 * 注入一个model文件
	 */
	protected function injection($fileName) {
	
		// 注入model实例
		include '../../common/model/' . $fileName;
	}
	
	/**
	 * 返回一条成功
	 */
	protected function ok(){
		return json_decode('{"return":"OK"}');
	}
	
	/**
	 * 返回一条失败
	 * @param string $msg 失败的消息
	 */
	protected function error($msg){
		if ($msg === "OK"){
			return json_decode('{"return":"Controller::Error方法出错：msg参数不能为OK"}');
		}
		return '{"return":"' . $msg . '"}';
	}
	
	/**
	 * 返回一条带有数据返回值的成功消息
	 * @param object $data 被当作data属性的object
	 */
	protected function data($result){
		$data = json_decode('{"return":"OK"}');
		$data->data = $result;
		return $data;
	}
}

?>

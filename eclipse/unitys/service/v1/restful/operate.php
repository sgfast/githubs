<?php

/**
 * Query参数类
 */
class Option{
	
	public $skip;
	public $limit;
	public $sort;
	public $slice;
	public $projection;
	public $choose = true;

	/**
	 * 添加一个数组的Slice设置
	 */
	public function addSlice($colName, $startIndex, $count){

		if (is_null($this->slice)){
			$this->slice = [];
		}

		$slice = ['colName'=>$colName, 'startIndex'=>$startIndex, 'count'=>$count];
		$this->slice[] = $slice;
	}

	/**
	 * 创建参数对象
	 */
	public function create(){

		// 如果都为空，则返回null
		if (is_null($this->skip) && is_null($this->limit) && is_null($this->sort) && is_null($this->slice) && is_null($this->projection)){
			return null;
		}

		// 创建对象
		$option = [];

		// 如果存在skip
		if (!is_null($this->skip)){
			$option['skip'] = intval($this->skip);
		}

		// 如果存在limit
		if (!is_null($this->limit)){
			$option['limit'] = intval($this->limit);
		}

		// 如果存在sort
		if (!is_null($this->sort)){
			$option['sort'] = $this->sort;
		}

		// 如果存在projection
		if (!is_null($this->projection) || !is_null($this->slice)){

			// 声明projection
			$projection = [];

			// 添加this->projection到projection临时变量
			if (!is_null($this->projection)){
				$tmp = explode(',', $this->projection);
				foreach ($tmp as $v){
					$projection[trim($v)] = $this->choose ? 1 : 0;
				}
			}

			// 添加this->slice到projection临时变量
			if (!is_null($this->slice)){
				foreach ($this->slice as $v){
					$projection[$v['colName']] = ['slice'=>[$v['startIndex'], $v['count']]];
				}
			}

			// 将临时变量projection添加到option中
			$option['projection'] = $projection;
		}

		// 返回
		return $option;
	}
}

/**
 * Operate基类，通常由Filter和Controller继承使用
 */
class Operate {

	/**
	 * 生成一个新的id对象
	 * @param string $_id
	 * @return \MongoDB\BSON\ObjectID 如果参数不为空，则根据参数返回id对象，否则生成一个随机新对象
	 */
	protected function createId($_id=''){
		if (empty($_id)){
			return new MongoDB\BSON\ObjectID();
		}else{
			return new MongoDB\BSON\ObjectID($_id);
		}
	}
	
	/**
	 * 创建一个Query对象
	 * @param array $arrFilter 过滤器数组
	 * @param array $arrOption 设置器数组
	 * @return MongoDB\Driver\Query的实例
	 */
	protected function createQuery($arrFilter, $arrOption=null){
		if (is_null($arrOption)){
			return new MongoDB\Driver\Query($arrFilter);
		}else{
			return new MongoDB\Driver\Query($arrFilter, $arrOption);
		}
	}

	/**
	 * 根据一个id返回一个Query对象
	 * @param MongoDB\BSON\ObjectID $_id 已经获取的objectId对象
	 */
	protected function createQueryId($_id){
		return $this->createQuery(['_id'=>$_id]);
	}
	
	/**
	 * 创建一个Insert对象
	 * @param array|object $document
	 * @param MongoDB\Driver\BulkWrite $bulk
	 */
	protected function createInsert($document, &$bulk){
		$bulk = $this->createBulk($bulk);
		$bulk->insert($document);
	}
	
	/**
	 * 创建一个Update对象
	 * @param array $arrFilter 过滤数组
	 * @param array $arrUpdate 更新数据数组
	 * @param MongoDB\Driver\BulkWrite $bulk 
	 */
	protected function createUpdate($arrFilter, $arrUpdate, &$bulk){
		$bulk = $this->createBulk($bulk);
		$bulk->update($arrFilter, $arrUpdate, ['multi'=>true, 'upsert'=>false]);
	}
	
	/**
	 * 创建一个Delete对象
	 * @param array $arrFilter 过滤数组
	 * @param MongoDB\Driver\BulkWrite $bulk 
	 */
	protected function createDelete($arrFilter, &$bulk){
		$bulk = $this->createBulk($bulk);
		$bulk->delete($arrFilter, ['limit'=>true]);
	}
	
	/**
	 * 检查$bulk参数是否为null，如果不为null，则直接返回，否则创建一个新的bulk并返回
	 */
	private function createBulk($bulk){
		if (is_null($bulk)){
			return new MongoDB\Driver\BulkWrite();
		}else{
			return $bulk;
		}
	}
}

/**
 * 继承自Operate，用于Controller的特殊实现
 * 这里使用的$_PUT，写在global.php的最上方
 */
class Controller extends Operate{
	
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

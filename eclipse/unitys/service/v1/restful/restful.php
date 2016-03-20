<?php

/**
 * Restful基础类 */
class Restful {
	
	// 判断此次访问是否需要获取响应时间，如果存在time参数，则在this->output中加入time属性
	// 此值默认=0，如果存在time参数，则此time属性值为初始时间
	// 以毫秒为单位，主要用于检查是否存在慢操作。
	private $time;
	
	// 访问使用的方法(GET, POST等)
	private $method;
	
	// 本次访问使用的路由
	private $router;
	
	// 本次访问使用的action
	private $action;
	
	// 本次访问的输出对象(数组)
	private $output;
	
	// 过滤器注册数组，三维数组。具有：fullActins[数组], fileName, funcName字段
	private $filters;
	
	/**
	 * 初始化
	 */
	public function __construct() {
		$this->initTime();
		$this->initMethod ();
		$this->initRouter ();
		$this->initAction ();
		$this->load ();
		$this->doAction ();
	}
	
	/**
	 * 添加一个过滤器
	 */
	protected function addFilter($fullActionName, $fileName, $className) {

		// 如果$this->filters为空，则初始化为[]
		if (! isset ( $this->filters )) {
			$this->filters = [ ];
		}
		
		// 将传入的filter添加到$this->filters上
		$filter = [ 
				'fullActionName' => $fullActionName,
				'fileName' => $fileName,
				'className' => $className 
		];
		$this->filters [] = $filter;
	}
	
	/**
	 * 初始化time，初始time为0，如果存在time参数，则将其设置为当前时间13位
	 */
	private function initTime(){
		$this->time = 0;
		if (!empty(get('time'))){
			$this->time = time13();
		}
	}
	
	/**
	 * 初始化Method
	 */
	private function initMethod() {
		$this->method = strtolower ( $_SERVER ['REQUEST_METHOD'] );
	}
	
	/**
	 * 初始化Router
	 */
	private function initRouter() {
		
		// 分解get参数
		$master = get ( 'master' );
		$controller = get ( 'controller' );
		
		// 注意此处有router的检查，如果$master或$controller===''，则说明没有按标准带参数。则要报错退出
		if ($master === '' || $controller === '') {
			w_err ( 'master或controller为空串！' );
		}
		
		// 组合router，注意此值不带.php文件扩展名，因为this->router不仅要用于导入controller文件
		// 还会在filter导入处与action组合使用
		$this->router = "${master}/${controller}";
	}
	
	/**
	 * 初始化Action
	 */
	private function initAction() {
		
		// 获取action参数
		$action = get ( 'action' );
		
		// 此处注意action的检查，如果$action===''，则说明没有按标准带参数。则要报错退出
		if ($action === '') {
			w_err ( 'action为空串！' );
		}
		
		// 组合action
		$this->action = $this->method . '_' . $action;
	}
	
	/**
	 * 载入controller文件
	 */
	private function load() {
		include 'controller/' . $this->router . '.php';
	}
	
	/**
	 * 执行action
	 */
	private function doAction() {
		
		// 调用action，注意使用try语句
		try {
			$controller = new MyController ();
			$action = new ReflectionMethod ( $controller, $this->action );
			$this->output = $action->invoke ( $controller );
		} catch ( ReflectionException $e ) {
			
			// 如果执行出错，报错退出
			w_err ( 'doAction：ccontroller或action不存在或执行出错！' );
		}
		
		// 如果this-output出错，则不应该往下执行
		if ($this->output->return !== 'OK') {
			w_info ( json_encode ( $this->output ) );
		}
		
		// 调用filter
		$this->doFilter ();
		
		// 判断$this->time是否为0，如果不为0，则说明需要输出时间，在$this->output中添加time属性
		if ($this->time > 0){
			$this->output->time = time13() - $this->time;
		}
		
		// 最终输出
		w_info ( json_encode ( $this->output ) );
	}
	
	/**
	 * 执行filter
	 */
	private function doFilter() {
		
		// 判断是否为get访问，且filter被声明为数组。否则直接返回
		if ($this->method !== 'get' || !isset ( $this->filters ) || !is_array ( $this->filters )) {
			return;
		}

		// 遍历过滤器
		foreach ( $this->filters as $filter ) {
		
			// 判断本filter是否是针对此action的，如果是，则调用
			if ($filter ['fullActionName'] === $this->router . '/' . $this->action) {
					
				// 载入文件
				include 'filter/' . $filter ['fileName'];
					
				// 反射类，并实例化
				$class = new ReflectionClass ( $filter ['className'] );
				$instance = $class->newInstanceWithoutConstructor ();

				// 执行filter
				$instance->working ( $this->output->data );
			}
		}
	}
}

?>

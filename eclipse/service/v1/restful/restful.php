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
	private $follows;
	
	/**
	 * 初始化
	 */
	public function __construct() {
		
		// 初始化时间参数
		$this->initTime ();
		
		// 获取方法、路由和动作
		$this->method = method ();
		$this->router = router ();
		$this->action = $this->method . '_' . action ();
		
		// 执行操作
		$this->doAction ();
	}
	
	/**
	 * 添加一个过滤器
	 */
	protected function addFollow($fullActionName, $fileName, $className, $methodNames) {
		
		// 如果$this->follows为空，则初始化为[]
		if (! isset ( $this->follows )) {
			$this->follows = [ ];
		}
		
		// 将传入的filter添加到$this->filters上
		$follow = [ 
				'fullActionName'=> $fullActionName,
				'fileName'=> $fileName,
				'className'=> $className,
				'methodNames'=> $methodNames ];
		$this->follows[] = $follow;
	}
	
	/**
	 * 初始化time，初始time为0，如果存在time参数，则将其设置为当前时间13位
	 */
	private function initTime() {
		$this->time = 0;
		if (! empty ( get ( 'time' ) )) {
			$this->time = time13 ();
		}
	}
	
	/**
	 * 执行action
	 */
	private function doAction() {
		
		// 调用action，注意使用try语句
		try {
			// 导入路由功能文件
			include 'controller/' . $this->router . '.php';
			
			// 反射类和方法
			$controller = new MyController ();
			$action = new ReflectionMethod ( $controller, $this->action );
			
			// 执行
			$this->output = $action->invoke ( $controller );
		} catch ( ReflectionException $e ) {
			
			// 如果执行出错，报错退出
			w_err ( 'doAction：controller或action不存在或执行出错。' . $e->getMessage () );
		}
		
		// 如果this-output出错，则不应该往下执行
		if ($this->output->return !== 'OK') {
			w_info ( json_encode ( $this->output ) );
		}
		
		// 调用follow
		$this->doFollows ();
		
		// 判断$this->time是否为0，如果不为0，则说明需要输出时间，在$this->output中添加time属性
		if ($this->time > 0) {
			$this->output->time = time13 () - $this->time;
		}
		
		// 最终输出
		w_info ( json_encode ( $this->output ) );
	}
	
	/**
	 * 执行filter
	 */
	private function doFollows() {
		
		// 判断follow被声明为数组。否则直接返回
		if (! isset ( $this->follows ) || ! is_array ( $this->follows )) {return;}
		
		// 遍历过滤器
		foreach ( $this->follows as $follow ) {
			
			// 判断本follow是否是针对此action的，如果是，则调用
			if ($follow['fullActionName'] === $this->router . '/' . $this->action) {
				
				// 注意使用try语句
				try {
					// 载入文件
					include 'follow/' . $follow['fileName'];
					
					// 反射类，并实例化
					$class = new ReflectionClass ( $follow['className'] );
					$instance = $class->newInstanceWithoutConstructor ();
					
					// 遍历方法名并反射、执行
					foreach ( $follow['methodNames'] as $methodName ) {
						
						// 反射方法
						$method = $class->getMethod ( $methodName );
						
						// 执行follow
						$method->invoke ( $instance, $this->output->data );
					}
				} catch ( ReflectionException $e ) {
					
					// 如果执行出错，报错退出
					w_err ( 'doFollows：follow不存在或执行出错。' . $e->getMessage () );
				}
			}
		}
	}
}

?>

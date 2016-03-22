<?php

class ABC{
	public function a($data){
		foreach ($data as $d){
			echo $d;
		}
	}
}

$d = [1,2,3,4];


// 反射类，并实例化
$class = new ReflectionClass ( 'ABC' );
$instance = $class->newInstanceWithoutConstructor ();
$method = $class->getMethod('a');
	
// 执行filter
$method->invoke($instance, $d);
//$instance->working ( $this->output->data );

?>
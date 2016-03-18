<?php

$_PUT = null;

class Ref{
	public function __construct(){
		global $_PUT;
		$_PUT = ['abc'];
	}
	public function abc(){
		echo 'abc';
	}
}

$r = new Ref();

print_r($_PUT);
// $class = new ReflectionClass('Ref');
// $instance = $class->newInstanceWithoutConstructor();
// $instance->abc();

?>
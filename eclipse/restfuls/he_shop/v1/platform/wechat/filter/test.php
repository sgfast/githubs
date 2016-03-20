<?php

class Test extends Operate{

	public function working(&$data){
		
		foreach ($data as $d){
			$d->aid = 1;
		}
	}
}


?>
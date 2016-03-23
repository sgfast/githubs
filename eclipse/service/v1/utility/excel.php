<?php

/**
 * 导出excel文件类，当前功能相对比较简单，日后可以进行扩展
 * 当前只能输出一个sheet，并且，不能合并单元格等操作
 */
class ExcelExport {
	private $filename; 		// 保存的文件名
	private $data; 			// 需要导出的数据
	
	/**
	 * 初始化，声明导出格式为excel
	 */
	public function __construct($filename, $data) {
		
		// 验证filename
		if (empty ( $filename )) {
			w_err ( 'ExcelExport: filename为空串' );
		}
		
		// 验证data
		if (! isset ( $data ) || is_null ( $data )) {
			w_err ( 'ExcelExport: data未声明或为null' );
			if (! is_array ( $data )) {
				w_err ( 'ExcelExport: data必须为数组' );
			}
		}
		
		// 赋值属性
		$this->filename = $filename;
		$this->data = $data;

		// 声明http response头
		header ( 'Content-type:application/vnd.ms-excel' );
		header ( 'Content-Disposition:attachment;filename=' . $this->filename . '.xls' );
	}
	
	/**
	 * 实际的工作方法
	 */
	public function working() {
		
		// arr为双维数组，遍历line, column进行输出
		foreach ( $this->data as $line ) {
			foreach ( $line as $column ) {
				$this->newColumn ( $column );
			}
			
			// 输出换行
			$this->newLine ();
		}
	}
	
	/**
	 * 输出一个表格，并输出换表格符
	 * @$column 表格中的内容
	 */
	private function newColumn($column) {
		echo $column . chr ( 9 );
	}
	
	/**
	 * 输出换行符
	 */
	private function newLine() {
		echo chr ( 13 );
	}
}

?>
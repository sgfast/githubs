<?php

/**
 * Mongo读取过滤器类
 */
class MongoFilter {

	// 需要跳过的条目数量
	public $skip;

	// 取出的条目数量限制
	public $limit;

	// 排序
	public $sort;

	// 取出数组时的条目选择
	public $slice;

	// 取出的条目选择
	public $projection;

	// 当取出条目时是选择还是反选, true为选择
	public $choose = true;

	/**
	 * 添加一个数组的Slice设置
	 */
	public function addSlice($colName, $startIndex, $count) {
		if (is_null ( $this->slice )) {
			$this->slice = [ ];
		}

		$slice = [
				'colName'=> $colName,
				'startIndex'=> $startIndex,
				'count'=> $count ];
		$this->slice[] = $slice;
	}

	/**
	 * 创建参数对象
	 */
	public function create() {

		// 如果都为空，则返回null
		if (is_null ( $this->skip ) && is_null ( $this->limit ) && is_null ( $this->sort ) && is_null ( $this->slice ) && is_null ( $this->projection )) {return null;}

		// 创建对象
		$option = [ ];

		// 如果存在skip
		if (! is_null ( $this->skip )) {
			$option['skip'] = intval ( $this->skip );
		}

		// 如果存在limit
		if (! is_null ( $this->limit )) {
			$option['limit'] = intval ( $this->limit );
		}

		// 如果存在sort
		if (! is_null ( $this->sort )) {
			$option['sort'] = $this->sort;
		}

		// 如果存在projection
		if (! is_null ( $this->projection ) || ! is_null ( $this->slice )) {
				
			// 声明projection
			$projection = [ ];
				
			// 添加this->projection到projection临时变量
			if (! is_null ( $this->projection )) {
				$tmp = explode ( ',', $this->projection );
				foreach ( $tmp as $v ) {
					$projection[trim ( $v )] = $this->choose ? 1 : 0;
				}
			}
				
			// 添加this->slice到projection临时变量
			if (! is_null ( $this->slice )) {
				foreach ( $this->slice as $v ) {
					$projection[$v['colName']] = [
							'slice'=> [
									$v['startIndex'],
									$v['count'] ] ];
				}
			}
				
			// 将临时变量projection添加到option中
			$option['projection'] = $projection;
		}

		// 返回
		return $option;
	}
}
?>
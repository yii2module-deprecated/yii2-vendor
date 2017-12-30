<?php

namespace yii2module\vendor\domain\filters;

use yii\base\BaseObject;
use yii2lab\misc\interfaces\FilterInterface;

class IsIgnoreFilter extends BaseObject implements FilterInterface {

	public $ignore;
	
	public function run($list) {
		$list = $this->filterIgnoreList($list);
		return $list;
	}
	
	private function filterIgnoreList($list) {
		if(empty($this->ignore)) {
			return $list;
		}
		$result = [];
		foreach($list as $k => $repo) {
			if(!in_array($repo['package'], $this->ignore)) {
				$result[] = $repo;
			}
		}
		return $result;
	}

}

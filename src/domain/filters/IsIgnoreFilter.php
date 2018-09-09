<?php

namespace yii2module\vendor\domain\filters;

use yii2lab\extension\scenario\base\BaseScenario;

class IsIgnoreFilter extends BaseScenario {

	public $ignore;
	
	public function run() {
		$list = $this->getData();
		$list = $this->filterIgnoreList($list);
		$this->setData($list);
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

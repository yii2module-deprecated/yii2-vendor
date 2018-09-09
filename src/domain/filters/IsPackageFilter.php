<?php

namespace yii2module\vendor\domain\filters;

use Yii;
use yii2lab\extension\scenario\base\BaseScenario;

class IsPackageFilter extends BaseScenario {
	
	public function run() {
		$list = $this->getData();
		$list = $this->filterList($list);
		$this->setData($list);
	}
	
	private function filterList($list) {
		$result = [];
		foreach($list as $k => $repo) {
			$dir = Yii::getAlias('@vendor/' . $repo['package']);
			if(is_dir($dir) && is_file($dir . DS . 'composer.json')) {
				$result[] = $repo;
			}
		}
		return $result;
	}

}

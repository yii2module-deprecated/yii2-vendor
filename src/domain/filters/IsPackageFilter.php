<?php

namespace yii2module\vendor\domain\filters;

use Yii;
use yii\base\BaseObject;
use yii2lab\designPattern\filter\interfaces\FilterInterface;

class IsPackageFilter extends BaseObject implements FilterInterface {

	public function run($list) {
		$list = $this->filterList($list);
		return $list;
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

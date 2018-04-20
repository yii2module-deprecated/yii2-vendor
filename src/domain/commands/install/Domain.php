<?php

namespace yii2module\vendor\domain\commands\install;

use yii2module\vendor\domain\commands\Base;

class Domain extends Base {

	public function run() {
		$moduleDir = $this->packageFile($this->data['owner'], $this->data['name'], 'src' . DS . 'domain');
		if(is_dir($moduleDir)) {
			$this->makeConfig();
		}
	}
	
	protected function makeConfig() {
		$value = "'{$this->data['namespace']}\\domain\Domain'";
		$file = '@common/config/services.php';
		$this->insertConfig($file, 'components', $this->data['name'], $value);
	}
	
}

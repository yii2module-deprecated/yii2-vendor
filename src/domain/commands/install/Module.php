<?php

namespace yii2module\vendor\domain\commands\install;

use yii2module\vendor\domain\commands\Base;

class Module extends Base {

	private $aliases = [
		'web' => 'frontend',
		'admin' => 'backend',
		'api' => 'api',
		'console' => 'console',
		'common' => 'common',
	];
	
	public function run() {
		foreach($this->aliases as $alias => $appName) {
			$moduleDir = $this->packageFile($this->data['owner'], $this->data['name'], 'src' . DS . $alias);
			if(is_dir($moduleDir)) {
				$this->makeConfig($alias);
			}
		}
	}
	
	protected function makeConfig($alias) {
		$value = "'{$this->data['namespace']}\\$alias\Module'";
		$file = '@'.$this->aliases[$alias].'/config/modules.php';
		$this->insertConfig($file, 'modules', $this->data['name'], $value);
	}
	
}

<?php

namespace yii2module\vendor\domain\install;

use yii2lab\misc\interfaces\CommandInterface;
use yii2module\vendor\domain\generators\Base;

class Module extends Base implements CommandInterface {

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
				$this->makeConfig($this->data, $alias);
			}
		}
	}
	
	protected function makeConfig($data, $alias) {
		$aliases = config('modules', []);
		if(isset($aliases[$data['name']])) {
			return;
		}
		$newLine = "\t\t'{$data['name']}' => '{$data['namespace']}\\$alias\Module',";
		$search = "'modules' => [";
		$this->insertLineConfig('@'.$this->aliases[$alias].'/config/modules.php', $search, $newLine);
	}
}

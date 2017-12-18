<?php

namespace yii2module\vendor\domain\install;

use yii2lab\misc\interfaces\CommandInterface;
use yii2module\vendor\domain\generators\Base;

class Module extends Base implements CommandInterface {

	public $type;
	
	public function run() {
		$this->makeConfig($this->data, $this->type);
	}
	
	protected function makeConfig($data, $type) {
		$aliases = config('modules', []);
		if(isset($aliases[$data['name']])) {
			return;
		}
		$newLine = "\t\t'{$data['name']}' => '{$data['namespace']}\\$type\Module',";
		$search = "'modules' => [";
		$arr = [
			'web' => 'frontend',
			'admin' => 'backend',
			'api' => 'api',
			'console' => 'console',
			'common' => 'common',
		];
		$this->insertLineConfig('@'.$arr[$type].'/config/modules.php', $search, $newLine);
	}
}

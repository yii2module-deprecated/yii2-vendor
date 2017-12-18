<?php

namespace yii2module\vendor\domain\commands\install;

use yii2lab\misc\interfaces\CommandInterface;
use yii2module\vendor\domain\commands\generators\Base;

class Domain extends Base implements CommandInterface {

	public function run() {
		$moduleDir = $this->packageFile($this->data['owner'], $this->data['name'], 'src' . DS . 'domain');
		if(is_dir($moduleDir)) {
			$this->makeConfig($this->data);
		}
	}
	
	protected function makeConfig($data) {
		$aliases = config('components', []);
		if(isset($aliases[$data['name']])) {
			return;
		}
		$newLine = "\t\t'{$data['name']}' => '{$data['namespace']}\\domain\Domain',";
		$search = "'components' => [";
		$this->insertLineConfig('@common/config/services.php', $search, $newLine);
	}
}

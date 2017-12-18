<?php

namespace yii2module\vendor\domain\install;

use yii2lab\misc\interfaces\CommandInterface;
use yii2module\vendor\domain\generators\Base;

class Domain extends Base implements CommandInterface {

	public function run() {
		$this->makeConfig($this->data);
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

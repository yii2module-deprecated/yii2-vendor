<?php

namespace yii2module\vendor\domain\install;

use yii2lab\misc\interfaces\CommandInterface;
use yii2module\vendor\domain\generators\Base;

class Package extends Base implements CommandInterface {

	public function run() {
		$this->makeAliasConfig($this->data);
	}
	
	protected function makeAliasConfig($data) {
		$aliases = config('aliases', []);
		if(isset($aliases[$data['alias']])) {
			return;
		}
		$newLine = "\t\t'{$data['alias']}' => '@vendor/{$data['full_name']}/src',";
		$search = "'aliases' => [";
		$this->insertLineConfig('@common/config/main.php', $search, $newLine);
	}
	
}

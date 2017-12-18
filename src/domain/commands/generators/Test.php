<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2lab\misc\interfaces\CommandInterface;

class Test extends Base implements CommandInterface {

	public function run() {
		$this->generateTest($this->data);
	}
	
	protected function generateTest($data) {
		$this->copyDir($data, 'tests');
		$this->copyFile($data, 'codeception.yml');
	}
}

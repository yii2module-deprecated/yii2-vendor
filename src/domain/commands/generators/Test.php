<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2lab\designPattern\command\interfaces\CommandInterface;
use yii2module\vendor\domain\commands\Base;

class Test extends Base implements CommandInterface {

	public function run() {
		$this->generateTest($this->data);
	}
	
	protected function generateTest($data) {
		$this->copyDir($data, 'tests');
		$this->copyFile($data, 'codeception.yml');
	}
}

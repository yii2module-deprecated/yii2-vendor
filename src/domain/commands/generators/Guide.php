<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2lab\designPattern\command\interfaces\CommandInterface;
use yii2module\vendor\domain\commands\Base;

class Guide extends Base implements CommandInterface {

	public function run() {
		$this->generateGuide($this->data);
	}
	
	protected function generateGuide($data) {
		$this->copyDir($data, 'guide');
	}
}

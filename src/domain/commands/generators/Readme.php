<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2lab\designPattern\command\interfaces\CommandInterface;
use yii2module\vendor\domain\commands\Base;

class Readme extends Base implements CommandInterface {

	public function run() {
		$this->generateReadme($this->data);
	}
	
	protected function generateReadme($data) {
		$this->copyFile($data, 'README.md');
	}
}

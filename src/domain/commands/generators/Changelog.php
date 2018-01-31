<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2lab\designPattern\command\interfaces\CommandInterface;
use yii2module\vendor\domain\commands\Base;

class Changelog extends Base implements CommandInterface {

	public function run() {
		$this->generate($this->data);
	}
	
	protected function generate($data) {
		$this->copyFile($data, 'CHANGELOG.md');
	}
}

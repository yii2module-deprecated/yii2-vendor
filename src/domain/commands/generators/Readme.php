<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2lab\misc\interfaces\CommandInterface;

class Readme extends Base implements CommandInterface {

	public function run() {
		$this->generateReadme($this->data);
	}
	
	protected function generateReadme($data) {
		$this->copyFile($data, 'README.md');
	}
}

<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2lab\misc\interfaces\CommandInterface;

class Guide extends Base implements CommandInterface {

	public function run() {
		$this->generateGuide($this->data);
	}
	
	protected function generateGuide($data) {
		$this->copyDir($data, 'guide');
	}
}

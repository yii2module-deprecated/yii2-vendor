<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2module\vendor\domain\commands\Base;

class Guide extends Base {

	public function run() {
		$this->generateGuide($this->data);
	}
	
	protected function generateGuide($data) {
		$this->copyDir($data, 'guide');
	}
}

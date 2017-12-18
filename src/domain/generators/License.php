<?php

namespace yii2module\vendor\domain\generators;

use yii2lab\misc\interfaces\CommandInterface;

class License extends Base implements CommandInterface {

	public function run() {
		$this->generateLicense($this->data);
	}
	
	protected function generateLicense($data) {
		$this->copyFile($data, 'LICENSE');
	}
}

<?php

namespace yii2module\vendor\domain\generators;

use yii2lab\helpers\generator\ClassGeneratorHelper;
use yii2lab\misc\interfaces\CommandInterface;

class Domain extends Base implements CommandInterface {

	public function run() {
		$this->generateDomain($this->data);
	}
	
	protected function generateDomain($data) {
		$config = [
			'className' => $this->getBaseAlias($data) . '/domain/Domain',
			'afterClassName' => 'extends \yii2lab\domain\Domain',
			'code' => $this->getCode(),
		];
		ClassGeneratorHelper::generateClass($config);
	}
	
	protected function getCode() {
		return <<<EOT
	public function config() {
		return [
			'repositories' => [
			
			],
			'services' => [
			
			],
		];
	}
EOT;
	}
}

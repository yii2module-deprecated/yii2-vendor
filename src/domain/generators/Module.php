<?php

namespace yii2module\vendor\domain\generators;

use yii2lab\helpers\generator\ClassGeneratorHelper;
use yii2lab\misc\interfaces\CommandInterface;

class Module extends Base implements CommandInterface {

	public $type;
	
	public function run() {
		$this->generateModule($this->data, $this->type);
	}
	
	protected function generateModule($data, $type) {
		$config = [
			'className' => $this->getBaseAlias($data) . '/' . $type . '/Module',
			'afterClassName' => 'extends \yii\base\Module',
			'code' => $this->getLangDir($data),
		];
		ClassGeneratorHelper::generateClass($config);
	}
	
	protected function getLangDir($data) {
		return TAB .'//public static $langDir = \''.$data['owner'].'/'.$data['name'].'/domain/messages\';';
	}
}

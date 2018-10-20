<?php

namespace yii2module\vendor\console;

use yii\base\Module as YiiModule;
use yii2lab\domain\helpers\DomainHelper;

/**
 * offline module definition class
 */
class Module extends YiiModule
{
	
	public function init() {
		DomainHelper::forgeDomains([
			'vendor' => 'yii2module\vendor\domain\Domain',
		]);
		parent::init();
	}
	
}

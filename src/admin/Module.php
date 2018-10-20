<?php

namespace yii2module\vendor\admin;

use yii\base\Module as YiiModule;
use yii2lab\domain\helpers\DomainHelper;

/**
 * welcome module definition class
 */
class Module extends YiiModule
{

	public static $langDir = '@yii2module/vendor/domain/messages';
	
	public function init() {
		DomainHelper::forgeDomains([
			'vendor' => 'yii2module\vendor\domain\Domain',
		]);
		parent::init();
	}
	
}

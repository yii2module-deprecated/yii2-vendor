<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii\helpers\ArrayHelper;
use yii2module\vendor\console\commands\Base;

class GenerateEntityCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(!$this->isHasType('entity')) {
			return;
		}
		if(empty($event->attributes)) {
			return;
		}
		\App::$domain->vendor->generator->generateEntity(ArrayHelper::toArray($event));
	}
	
}

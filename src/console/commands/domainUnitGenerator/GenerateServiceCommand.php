<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii\helpers\ArrayHelper;

class GenerateServiceCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(!$this->isHasType('service')) {
			return;
		}
		\App::$domain->vendor->generator->generateService(ArrayHelper::toArray($event));
	}
	
}

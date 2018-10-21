<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii\helpers\ArrayHelper;

class GenerateServiceCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(in_array('service', $event->types)) {
			\App::$domain->vendor->generator->generateService(ArrayHelper::toArray($event));
		}
	}
	
}

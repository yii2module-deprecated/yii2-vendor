<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii\helpers\ArrayHelper;

class GenerateRepositoryCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(!$this->isHasType('repository')) {
			return;
		}
		if(empty($event->drivers)) {
			return;
		}
		\App::$domain->vendor->generator->generateRepository(ArrayHelper::toArray($event));
	}
	
}

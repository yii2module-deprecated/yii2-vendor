<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii\helpers\ArrayHelper;
use yii2lab\domain\enums\Driver;
use yii2lab\extension\console\helpers\input\Select;

class GenerateRepositoryCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(in_array('repository', $event->types)) {
			if(empty($event->drivers)) {
				$allDrivers = Driver::values();
				$drivers = Select::display('Select repository driver', $allDrivers, true, true);
				$event->drivers = array_values($drivers);
			}
			\App::$domain->vendor->generator->generateRepository(ArrayHelper::toArray($event));
		}
	}
	
}

<?php

namespace yii2module\vendor\console\commands\domainUnitInput;

use yii2lab\domain\enums\Driver;
use yii2lab\extension\console\helpers\input\Select;
use yii2module\vendor\console\commands\Base;

class SelectRepositoryDriversCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(!$this->isHasType('repository')) {
			return;
		}
		if(!empty($event->drivers)) {
			return;
		}
		$allDrivers = Driver::values();
		$drivers = Select::display('Select repository driver', $allDrivers, true, true);
		$event->drivers = array_values($drivers);
	}
	
}

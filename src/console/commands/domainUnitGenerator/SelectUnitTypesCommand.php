<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii2lab\extension\console\helpers\input\Select;

class SelectUnitTypesCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(!empty($event->types)) {
			return;
		}
		$types = Select::display('Select types', ['service', 'repository', 'entity'], true);
		$event->types = array_values($types);
	}
	
}

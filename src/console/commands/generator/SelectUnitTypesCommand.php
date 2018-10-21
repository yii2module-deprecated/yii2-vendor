<?php

namespace yii2module\vendor\console\commands\generator;

use yii2lab\extension\console\helpers\input\Select;

class SelectUnitTypesCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(empty($event->types)) {
			$types = Select::display('Select types', ['service', 'repository', 'entity'], true);
			$event->types = array_values($types);
		}
	}
	
}

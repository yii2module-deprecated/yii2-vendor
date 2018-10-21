<?php

namespace yii2module\vendor\console\commands\domainUnitInput;

use yii2lab\extension\console\helpers\input\Enter;
use yii2module\vendor\console\commands\Base;

class InputEntityNameCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(!empty($event->name)) {
			return;
		}
		$event->name = Enter::display('Enter name');
	}
	
}

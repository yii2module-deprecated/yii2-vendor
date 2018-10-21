<?php

namespace yii2module\vendor\console\commands\generator;

use yii2lab\extension\console\helpers\input\Enter;

class InputEntityNameCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(empty($event->name)) {
			$event->name = Enter::display('Enter name');
		}
	}
	
}

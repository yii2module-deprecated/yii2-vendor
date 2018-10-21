<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii2lab\extension\console\helpers\input\Enter;

class InputEntityAttributesCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(!$this->isHasType('entity')) {
			return;
		}
		if(!empty($event->attributes)) {
			return;
		}
		$attributes = Enter::display('Enter entity attributes');
		$event->attributes = explode(',', $attributes);
	}
	
}

<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii\helpers\ArrayHelper;
use yii2lab\extension\console\helpers\input\Enter;

class GenerateEntityCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(in_array('entity', $event->types)) {
			if(empty($event->attributes)) {
				$attributes = Enter::display('Enter entity attributes');
				$event->attributes = explode(',', $attributes);
			}
			\App::$domain->vendor->generator->generateEntity(ArrayHelper::toArray($event));
		}
	}
	
}

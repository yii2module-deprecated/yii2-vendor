<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii2lab\extension\scenario\base\BaseScenario;
use yii2module\vendor\console\events\DomainEvent;

abstract class Base extends BaseScenario {
	
	protected function getEvent() : DomainEvent {
		/** @var DomainEvent $event */
		$event = $this->getData();
		return $event;
	}

}

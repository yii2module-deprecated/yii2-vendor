<?php

namespace yii2module\vendor\console\commands\domainUnitGenerator;

use yii2lab\extension\console\helpers\input\Select;
use yii2mod\helpers\ArrayHelper;

class SelectDomainCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(empty($event->namespace)) {
			$domainAliases = \App::$domain->vendor->pretty->all();
			$domainAlias = Select::display('Select domain', $domainAliases);
			$event->namespace = ArrayHelper::first($domainAlias);
		}
	}
	
}

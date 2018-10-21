<?php

namespace yii2module\vendor\console\commands\domainUnitInput;

use yii2lab\extension\console\helpers\input\Select;
use yii2mod\helpers\ArrayHelper;
use yii2module\vendor\console\commands\Base;

class SelectDomainCommand extends Base {
	
	public function run() {
		$event = $this->getEvent();
		if(!empty($event->namespace)) {
			return;
		}
		$collection = \App::$domain->vendor->pretty->all();
		$domainAliases = ArrayHelper::getColumn($collection, 'path');
		$domainAlias = Select::display('Select domain', $domainAliases);
		$event->namespace = ArrayHelper::first($domainAlias);
	}
	
}

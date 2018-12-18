<?php

namespace yii2module\vendor\console\bin;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\extension\console\helpers\input\Select;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\console\base\Controller;

class InstallController extends \yii\base\Component
{
	
	/**
	 * Install package
	 */
	public function actionIndex()
	{
		list($owner, $name) = $this->inputPackage();
		\App::$domain->vendor->generator->install($owner, $name);
		Output::block('Success installed');
	}
	
	private function inputPackage() {
		$ownerSelect = Select::display('Select owner', \App::$domain->vendor->generator->owners);
		$owner = Select::getFirstValue($ownerSelect);
		$names = \App::$domain->vendor->info->shortNamesByOwner($owner);
		$nameSelect = Select::display('Select package', $names);
		$name = Select::getFirstValue($nameSelect);
		
		return [$owner, $name];
	}
}

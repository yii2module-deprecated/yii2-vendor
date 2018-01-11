<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;

class InstallController extends Controller
{
	
	/**
	 * Install package
	 */
	public function actionIndex()
	{
		list($owner, $name) = $this->inputPackage();
		Yii::$app->vendor->generator->install($owner, $name);
		Output::block('Success installed');
	}
	
	private function inputPackage() {
		$ownerSelect = Select::display('Select owner', Yii::$app->vendor->generator->owners);
		$owner = Select::getFirstValue($ownerSelect);
		$names = Yii::$app->vendor->info->shortNamesByOwner($owner);
		$nameSelect = Select::display('Select package', $names);
		$name = Select::getFirstValue($nameSelect);
		
		return [$owner, $name];
	}
}

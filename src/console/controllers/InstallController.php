<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;

class InstallController extends Controller
{
	
	public function actionIndex()
	{
		list($owner, $name) = $this->inputPackage();
		Yii::$app->vendor->generator->install($owner, $name);
		Output::block('Success installed');
	}
	
	private function inputPackage() {
		$ownerSelect = Select::display('Select owner', Yii::$app->vendor->generator->owners);
		$owner = Select::getFirstValue($ownerSelect);
		
		$collection = Yii::$app->vendor->info->allByOwner($owner);
		$names = ArrayHelper::getColumn($collection, 'name');
		$nameSelect = Select::display('Select package', $names);
		$name = Select::getFirstValue($nameSelect);
		
		return [$owner, $name];
	}
}

<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\input\Enter;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;

class InstallController extends Controller
{
	
	public function actionIndex()
	{
		list($owner, $name) = $this->inputPackage();
		Yii::$app->vendor->generator->install($owner, $name);
		Output::block('Success generated');
	}
	
	private function inputPackage() {
		$ownerSelect = Select::display('Select owner', Yii::$app->vendor->generator->ownerList);
		$owner = Select::getFirstValue($ownerSelect);
		$name = Enter::display('Enter vendor name');
		return [$owner, $name];
	}
}

<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\input\Enter;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;
use yii2module\vendor\domain\enums\TypeEnum;

class GeneratorController extends Controller
{
	
	public function actionIndex()
	{
		list($owner, $name) = $this->inputPackage();
		$types = Select::display('Select for generate', TypeEnum::values(), 1);
		$types = array_values($types);
		Yii::$app->vendor->generator->generateAll($owner, $name, $types);
		Output::block('Success generated');
	}
	
	public function actionPackage()
	{
		list($owner, $name) = $this->inputPackage();
		Yii::$app->vendor->generator->generatePackage($owner, $name);
		Output::block('Package success generated');
	}
	
	public function actionLicense()
	{
		list($owner, $name) = $this->inputPackage();
		Yii::$app->vendor->generator->generateLicense($owner, $name);
		Output::block('License success generated');
	}
	
	public function actionGuide()
	{
		list($owner, $name) = $this->inputPackage();
		Yii::$app->vendor->generator->generateGuide($owner, $name);
		Output::block('Guide success generated');
	}
	
	public function actionReadme()
	{
		list($owner, $name) = $this->inputPackage();
		Yii::$app->vendor->generator->generateReadme($owner, $name);
		Output::block('Readme success generated');
	}
	
	public function actionTest()
	{
		list($owner, $name) = $this->inputPackage();
		Yii::$app->vendor->generator->generateTest($owner, $name);
		Output::block('Test success generated');
	}
	
	private function inputPackage() {
		return ['yii2woop', 'qwerty12345'];
		$ownerSelect = Select::display('Select owner', Yii::$app->vendor->generator->ownerList);
		$owner = Select::getFirstValue($ownerSelect);
		$name = Enter::display('Enter vendor name');
		return [$owner, $name];
	}
}

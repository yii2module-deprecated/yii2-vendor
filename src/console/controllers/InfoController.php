<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;
use yii2module\rest_client\helpers\ArrayHelper;

class InfoController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}
	
	public function actionAllChanged()
	{
		Output::line('Getting package info...');
		$collection = Yii::$app->vendor->info->allChanged();
		if(!empty($collection)) {
			$names = ArrayHelper::getColumn($collection, 'alias');
			Output::line();
			Output::arr($names, 'Changed repository list');
		} else {
			Output::block('All repository fixed!', 'Message');
		}
	}
	
	public function actionAllVersion()
	{
		Output::line('Getting package info...');
		$collection = Yii::$app->vendor->info->allVersion();
		if(!empty($collection)) {
			$flatCollection = ArrayHelper::map($collection, 'package', 'version');
			Output::line();
			Output::arr($flatCollection, 'Repository version list');
		} else {
			Output::block('Empty list!', 'Message');
		}
	}
	
	public function actionAllForRelease()
	{
		Output::line('Getting package info...');
		$collection = Yii::$app->vendor->info->allForRelease();
		if(!empty($collection)) {
			$flatCollection = ArrayHelper::map($collection, 'package', 'version');
			Output::line();
			Output::arr($flatCollection, 'Repository list for release');
		} else {
			Output::block('Empty list!', 'Message');
		}
	}
	
	public function actionPackageUses()
	{
		//list($owner, $name) = $this->inputPackage();
		$owner = 'yii2lab';
		$name = 'notify';
		Output::line('Find uses in package...');
		$uses = Yii::$app->vendor->info->usesById($owner . '-yii2-' . $name);
		Output::line();
		Output::items($uses, 'Package use list');
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

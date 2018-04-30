<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\input\Enter;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\base\Controller;
use yii2module\vendor\domain\enums\TypeEnum;

class GeneratorController extends Controller
{
	
	/**
	 * Generate package
	 */
	public function actionIndex()
	{
		list($owner, $name) = $this->inputPackage();
		$types = Select::display('Select for generate', TypeEnum::values(), 1);
		$types = array_values($types);
		Yii::$domain->vendor->generator->generateAll($owner, $name, $types);
		Output::block('Success generated');
	}
	
	/**
	 * Generate domain
	 */
	public function actionDomain()
	{
		//list($owner, $name) = $this->selectPackage();
		//Yii::$domain->vendor->generator->generateDomain($owner, $name);
		Yii::$domain->vendor->generator->generateDomain('yii2woop\history\domain');
		Output::block('Success generated');
	}
	
	private function selectPackage() {
		$ownerSelect = Select::display('Select owner', Yii::$domain->vendor->generator->owners);
		$owner = Select::getFirstValue($ownerSelect);
		$names = Yii::$domain->vendor->info->shortNamesByOwner($owner);
		$nameSelect = Select::display('Select package', $names);
		$name = Select::getFirstValue($nameSelect);
		return [$owner, $name];
	}
	
	private function inputPackage() {
		$ownerSelect = Select::display('Select owner', Yii::$domain->vendor->generator->owners);
		$owner = Select::getFirstValue($ownerSelect);
		$name = Enter::display('Enter vendor name');
		return [$owner, $name];
	}
}

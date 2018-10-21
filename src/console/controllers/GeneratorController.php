<?php

namespace yii2module\vendor\console\controllers;

use yii\console\Controller;
use yii2lab\extension\console\helpers\input\Enter;
use yii2lab\extension\console\helpers\input\Select;
use yii2lab\extension\console\helpers\Output;
use yii2mod\helpers\ArrayHelper;
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
		\App::$domain->vendor->generator->generateAll($owner, $name, $types);
		Output::block('Success generated');
	}
	
	/**
	 * Generate domain
	 */
	public function actionDomain()
	{
		$collection = \App::$domain->vendor->pretty->all();
		$domainAliases = ArrayHelper::getColumn($collection, 'path');
		$domainAlias = Select::display('Select domain', $domainAliases);
		$domainAlias = ArrayHelper::first($domainAlias);

		\App::$domain->vendor->generator->generateDomain($domainAlias);
		Output::block('Success generated');
	}
	
	private function inputPackage() {
		$owners = array_unique(\App::$domain->vendor->generator->owners);
		$ownerSelect = Select::display('Select owner', $owners);
		$owner = Select::getFirstValue($ownerSelect);
		$name = Enter::display('Enter vendor name');
		return [$owner, $name];
	}
}

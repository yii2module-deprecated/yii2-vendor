<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\input\Enter;
use yii2lab\console\helpers\input\Question;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\base\Controller;
use yii2lab\domain\enums\Driver;
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
		Yii::$domain->vendor->generator->generateAll($owner, $name, $types);
		Output::block('Success generated');
	}
	
	/**
	 * Generate domain
	 */
	public function actionDomain()
	{
		$domainAliases = Yii::$domain->vendor->pretty->all();
		$domainAlias = Select::display('Select domain', $domainAliases);
		$domainAlias = ArrayHelper::first($domainAlias);
		
		//$namespace = Enter::display('Enter namespace');
		//$namespace = 'yii2woop\history\domain';
		Yii::$domain->vendor->generator->generateDomain($domainAlias);
		Output::block('Success generated');
	}
	
	/**
	 * Generate all
	 */
	public function actionAll()
	{
		/*$domainAliases = Yii::$domain->vendor->pretty->all();
		$domainAlias = Select::display('Select domain', $domainAliases);
		$data['namespace'] = ArrayHelper::first($domainAlias);
		
		$types = Select::display('Select types', ['service', 'repository', 'entity'], true);
		$types = array_values($types);
		
		$data['name'] = Enter::display('Enter name');*/
		
		$data['namespace'] = 'yii2woop\history\domain';
		$data['name'] = 'qwe';
		$types = ['service', 'repository', 'entity'];
		
		if(in_array('service', $types) || in_array('repository', $types)) {
			$data['isActive'] = Question::display('Is active?');
		}
		
		if(in_array('service', $types)) {
			Yii::$domain->vendor->generator->generateService($data);
		}
		if(in_array('repository', $types)) {
			$allDrivers = Driver::values();
			$drivers = Select::display('Select repository driver', $allDrivers, true);
			$data['drivers'] = array_values($drivers);
			Yii::$domain->vendor->generator->generateRepository($data);
		}
		if(in_array('entity', $types)) {
			$attributes = Enter::display('Enter entity attributes');
			$data['attributes'] = explode(',', $attributes);
			Yii::$domain->vendor->generator->generateEntity($data);
		}
		Output::block('Success generated');
	}
	
	private function inputPackage() {
		$ownerSelect = Select::display('Select owner', Yii::$domain->vendor->generator->owners);
		$owner = Select::getFirstValue($ownerSelect);
		$name = Enter::display('Enter vendor name');
		return [$owner, $name];
	}
}

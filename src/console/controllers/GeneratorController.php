<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\ArgHelper;
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
	
	public $namespace = '';
	public $name = '';
	public $types = '';
	public $isActive = '';
	public $drivers = '';
	public $attributes = '';
	
	public function options($actionID) {
		//if($actionID == 'all') {
			return [
				'namespace',
				'name',
				'types',
				'isActive',
				'drivers',
				'attributes',
			];
		//}
		//return [];
	}
	
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
		$data['namespace'] = $this->namespace;
		$data['name'] = $this->name;
		$data['isActive'] = !empty($this->isActive) ? $this->isActive == 'y' : null;
		$data['drivers'] = !empty($this->drivers) ? explode(',', $this->drivers) : null;
		$data['attributes'] = !empty($this->attributes) ? explode(',', $this->attributes) : null;
		$types = !empty($this->types) ? explode(',', $this->types) : null;
		
		if(empty($data['namespace'])) {
			$domainAliases = Yii::$domain->vendor->pretty->all();
			$domainAlias = Select::display('Select domain', $domainAliases);
			$data['namespace'] = ArrayHelper::first($domainAlias);
		}
		
		if(empty($types)) {
			$types = Select::display('Select types', ['service', 'repository', 'entity'], true);
			$types = array_values($types);
		}
		
		if(empty($data['name'])) {
			$data['name'] = Enter::display('Enter name');
		}
		
		if($data['isActive'] === null && (in_array('service', $types) || in_array('repository', $types))) {
			$data['isActive'] = Question::display('Is active?');
		}
		
		if(in_array('service', $types)) {
			Yii::$domain->vendor->generator->generateService($data);
		}
		
		if(in_array('repository', $types)) {
			if(empty($data['drivers'])) {
				$allDrivers = Driver::values();
				$drivers = Select::display('Select repository driver', $allDrivers, true);
				$data['drivers'] = array_values($drivers);
			}
			Yii::$domain->vendor->generator->generateRepository($data);
		}
		
		if(in_array('entity', $types)) {
			if(empty($data['attributes'])) {
				$attributes = Enter::display('Enter entity attributes');
				$data['attributes'] = explode(',', $attributes);
			}
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

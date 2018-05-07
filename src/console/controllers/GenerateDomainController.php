<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii\console\Controller;
use yii2lab\console\helpers\input\Enter;
use yii2lab\console\helpers\input\Question;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\domain\enums\Driver;
use yii2mod\helpers\ArrayHelper;

class GenerateDomainController extends Controller
{
	
	/**
	 * @var string Namespace for domain
	 */
	public $namespace = null;
	
	/**
	 * @var string Name for units
	 */
	public $name = null;
	
	/**
	 * @var string Types: repository, service, entity
	 */
	public $types = null;
	
	/**
	 * @var string Is CRUD
	 */
	public $isActive = null;
	
	/**
	 * @var string Drivers for repository
	 */
	public $drivers = null;
	
	/**
	 * @var string Attributes for entity
	 */
	public $attributes = null;
	
	public function options($actionID) {
		if($actionID == 'all') {
			return array_merge(parent::options($actionID), [
				'namespace',
				'name',
				'types',
				'isActive',
				'drivers',
				'attributes',
			]);
		}
		return parent::options($actionID);
	}
	
	/**
	 * Generate domain units
	 *
	 * For example,
	 *
	 * ```
	 * php yii vendor/generate-domain/all --namespace=yii2woop\history\domain --types=service,repository,entity --name=articleCategory --is-active=y --drivers=tps,core --attributes=id,name,title,created_at
	 * ```
	 */
	public function actionAll()
	{
		Output::title("Domain units generator");
		
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
			$data['isActive'] = Question::confirm2('Is active?', false);
		}
		//prr($data,1);
		if(in_array('service', $types)) {
			Yii::$domain->vendor->generator->generateService($data);
		}
		
		if(in_array('repository', $types)) {
			if(empty($data['drivers'])) {
				$allDrivers = Driver::values();
				$drivers = Select::display('Select repository driver', $allDrivers, true, true);
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
	
}

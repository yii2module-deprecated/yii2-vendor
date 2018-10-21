<?php

namespace yii2module\vendor\console\controllers;

use yii\console\Controller;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\scenario\collections\ScenarioCollection;
use yii2module\vendor\console\commands\generator\GenerateRepositoryCommand;
use yii2module\vendor\console\commands\generator\GenerateServiceCommand;
use yii2module\vendor\console\commands\generator\InputEntityNameCommand;
use yii2module\vendor\console\commands\generator\QuestionIsActiveCommand;
use yii2module\vendor\console\commands\generator\SelectDomainCommand;
use yii2module\vendor\console\commands\generator\SelectUnitTypesCommand;
use yii2module\vendor\console\events\DomainEvent;

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
	 * php yii vendor/generate-domain/all --namespace=yii2module\example\domain --types=service,repository,entity --name=articleCategory --is-active=y --drivers=tps,core --attributes=id,name,title,created_at
	 * ```
	 */
	public function actionAll()
	{
		Output::title("Domain units generator");
		
		$event = new DomainEvent();
		$event->namespace = $this->namespace;
		$event->name = $this->name;
		$event->isActive = !empty($this->isActive) ? $this->isActive == 'y' : null;
		$event->drivers = !empty($this->drivers) ? explode(',', $this->drivers) : null;
		$event->attributes = !empty($this->attributes) ? explode(',', $this->attributes) : null;
		$event->types = !empty($this->types) ? explode(',', $this->types) : null;
		
		$filters = [
			SelectDomainCommand::class,
			SelectUnitTypesCommand::class,
			InputEntityNameCommand::class,
			QuestionIsActiveCommand::class,
			GenerateServiceCommand::class,
			GenerateRepositoryCommand::class,
			GenerateRepositoryCommand::class,
		];
		$filterCollection = new ScenarioCollection($filters);
		$filterCollection->runAll($event);
		
		Output::block('Success generated');
	}
	
}

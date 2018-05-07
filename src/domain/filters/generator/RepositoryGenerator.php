<?php

namespace yii2module\vendor\domain\filters\generator;

use yii\helpers\Inflector;
use yii2lab\extension\code\entities\ClassEntity;
use yii2lab\extension\code\entities\DocBlockEntity;
use yii2lab\extension\code\entities\DocBlockParameterEntity;
use yii2lab\extension\code\entities\InterfaceEntity;
use yii2lab\extension\code\enums\AccessEnum;
use yii2lab\extension\code\helpers\ClassHelper;

/**
 * Class RepositoryGenerator
 *
 * @package yii2module\vendor\domain\filters\generator
 *
 * @property boolean $isActive
 * @property array $drivers
 */
class RepositoryGenerator extends BaseGenerator {
	
	public $drivers;
	public $isActive = false;
	
	public function run() {
		$this->generateRepositoryInterface();
		if($this->isActive) {
			$this->generateRepositorySchema();
		}
		$this->generateRepository();
	}
	
	private function generateRepository() {
		$repositoryInterfaceClassName = $this->repositoryInterfaceClassName();
		foreach($this->drivers as $driver) {
			$classEntity = new ClassEntity();
			$classEntity->name = $this->namespace . '\\repositories\\'.$driver.'\\' . Inflector::camelize($this->name) . 'Repository';
			$classEntity->extends = 'BaseRepository';
			$classEntity->implements = basename($repositoryInterfaceClassName);
			if($this->isActive) {
				$classEntity->variables = [
					[
						'name' => 'schemaClass',
						'access' => AccessEnum::PROTECTED,
						'value' => 'true',
					],
				];
			}
			$classEntity->doc_block = new DocBlockEntity([
				'title' => 'Class' . SPC . $classEntity->name,
				'parameters' => $this->docComment(),
			]);
			$uses = [
				['name' => $repositoryInterfaceClassName],
				['name' => 'yii2lab\domain\repositories\BaseRepository'],
			];
			ClassHelper::generate($classEntity, $uses);
		}
	}
	
	private function generateRepositoryInterface() {
		$uses = [];
		$classEntity = new InterfaceEntity();
		$classEntity->name = $this->repositoryInterfaceClassName();
		if($this->isActive) {
			$classEntity->extends = 'CrudInterface';
			$uses = [
				['name' => 'yii2lab\domain\interfaces\repositories\CrudInterface'],
			];
		}
		$classEntity->doc_block = new DocBlockEntity([
			'title' => 'Interface' . SPC . $classEntity->name,
			'parameters' => $this->docComment(),
			
		]);
		ClassHelper::generate($classEntity, $uses);
	}
	
	private function generateRepositorySchema() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->namespace . '\\repositories\\schema\\' . Inflector::camelize($this->name) . 'Schema';
		$classEntity->extends = 'BaseSchema';
		$classEntity->doc_block = new DocBlockEntity([
			'title' => 'Class' . SPC . $classEntity->name,
		]);
		$uses = [
			['name' => 'yii2lab\domain\repositories\relations\BaseSchema'],
		];
		ClassHelper::generate($classEntity, $uses);
	}
	
	private function repositoryInterfaceClassName() {
		return $this->namespace . '\\interfaces\\repositories\\' . Inflector::camelize($this->name) . 'Interface';
	}
	
	private function docComment() {
		$repositoryDocBlock = [
			[
				'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
				'type' => '\\' . $this->namespace . '\\Domain',
				'value' => 'domain',
			],
		];
		return $repositoryDocBlock;
	}
	
}

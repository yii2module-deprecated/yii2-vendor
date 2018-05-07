<?php

namespace yii2module\vendor\domain\filters\generator;

use yii2lab\designPattern\scenario\base\BaseScenario;
use yii\helpers\Inflector;
use yii2lab\domain\generator\RepositoryInterfaceGenerator;
use yii2lab\domain\generator\RepositorySchemaGenerator;
use yii2lab\extension\code\entities\DocBlockParameterEntity;
use yii2module\vendor\domain\helpers\PrettyHelper;

class RepositoryGenerator extends BaseScenario {

	protected $namespace;
	protected $name;
	public $drivers;
	public $isActive = false;
	
	public function run() {
		$this->generateRepositoryInterface();
		if($this->isActive) {
			$this->generateRepositorySchema();
		}
		$this->generateRepository();
		PrettyHelper::refreshDomain($this->namespace);
	}
	
	public function getNamespace() {
		return $this->namespace;
	}
	
	public function setNamespace($namespace) {
		$this->namespace = str_replace(SL, BSL, $namespace);
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = Inflector::camelize($name);
		$this->name{0} = strtolower($this->name{0});
	}
	
	private function generateRepository() {
		$repositoryInterfaceClassName = $this->repositoryInterfaceClassName();
		foreach($this->drivers as $driver) {
			$generator = new \yii2lab\domain\generator\RepositoryGenerator();
			$generator->name = $this->namespace . '\\repositories\\'.$driver.'\\' . Inflector::camelize($this->name) . 'Repository';
			$generator->uses = [
				['name' => $repositoryInterfaceClassName],
			];
			//$generator->extends = $extends;
			$generator->implements = basename($repositoryInterfaceClassName);
			$generator->docBlockParameters = $this->docComment();
			$generator->run();
		}
	}
	
	private function generateRepositoryInterface() {
		$generator = new RepositoryInterfaceGenerator();
		$generator->name = $this->repositoryInterfaceClassName();
		if($this->isActive) {
			$generator->uses = [
				['name' => 'yii2lab\domain\interfaces\repositories\CrudInterface'],
			];
			$generator->extends = 'CrudInterface';
		}
		$generator->docBlockParameters = $this->docComment();
		$generator->run();
	}
	
	private function generateRepositorySchema() {
		$generator = new RepositorySchemaGenerator();
		$generator->name = $this->namespace . '\\repositories\\schema\\' . Inflector::camelize($this->name) . 'Schema';
		$generator->run();
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

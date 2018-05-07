<?php

namespace yii2module\vendor\domain\helpers\generator;

use yii\helpers\Inflector;
use yii2lab\domain\generator\RepositoryGenerator;
use yii2lab\domain\generator\RepositoryInterfaceGenerator;
use yii2lab\domain\generator\RepositorySchemaGenerator;
use yii2lab\extension\code\entities\DocBlockParameterEntity;

class RepositoryGeneratorHelper {
	
	public static function generateRepository($namespace, $name, $drivers) {
		$repositoryInterfaceClassName = self::repositoryInterfaceClassName($namespace, $name);
		foreach($drivers as $driver) {
			$generator = new RepositoryGenerator();
			$generator->name = $namespace . '\\repositories\\'.$driver.'\\' . Inflector::camelize($name) . 'Repository';
			$generator->uses = [
				['name' => $repositoryInterfaceClassName],
			];
			$generator->implements = basename($repositoryInterfaceClassName);
			$generator->docBlockParameters = self::docComment($namespace);
			$generator->run();
		}
	}
	
	public static function generateRepositoryInterface($namespace, $name) {
		$generator = new RepositoryInterfaceGenerator();
		$generator->name = self::repositoryInterfaceClassName($namespace, $name);
		$generator->docBlockParameters = self::docComment($namespace);
		$generator->run();
	}
	
	public static function generateRepositorySchema($namespace, $name) {
		$generator = new RepositorySchemaGenerator();
		$generator->name = $namespace . '\\repositories\\schema\\' . Inflector::camelize($name) . 'Schema';
		$generator->run();
	}
	
	private static function repositoryInterfaceClassName($namespace, $name) {
		return $namespace . '\\interfaces\\repositories\\' . Inflector::camelize($name) . 'Interface';
	}
	
	private static function docComment($namespace) {
		$repositoryDocBlock = [
			[
				'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
				'type' => '\\' . $namespace . '\\Domain',
				'value' => 'domain',
			],
		];
		return $repositoryDocBlock;
	}
}

<?php

namespace yii2module\vendor\domain\helpers;

use yii\helpers\Inflector;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\extension\code\entities\DocBlockParameterEntity;
use yii2lab\domain\generator\EntityGenerator;
use yii2lab\domain\generator\MessageGenerator;
use yii2lab\domain\generator\RepositoryGenerator;
use yii2lab\domain\generator\RepositoryInterfaceGenerator;
use yii2lab\domain\generator\RepositorySchemaGenerator;
use yii2lab\domain\generator\ServiceGenerator;
use yii2lab\domain\generator\ServiceInterfaceGenerator;

class GeneratorHelper {
	
	
	private static function getNames($definitions) {
		$nameList = [];
		foreach($definitions as $serviceName => $definition) {
			$nameList[] = is_integer($serviceName) ? $definition : $serviceName;
		}
		return $nameList;
	}
	
	public static function generateDomain($namespace) {
		$arr = self::getAllNames($namespace);
		foreach($arr as $n => $items) {
			self::generateName($namespace, $n, $items);
		}
	}
	
	private static function getAllNames($namespace) {
		$className = $namespace . '\\Domain';
		$domainConfig = DomainHelper::getConfigFromDomainClass($className);
		$arr = [];
		$repositories = self::getNames($domainConfig['repositories']);
		foreach($repositories as $name) {
			$arr[$name]['entity'] = $namespace . '\\entities\\' . Inflector::camelize($name) . 'Entity';
			$arr[$name]['repositoryInterface'] = $namespace . '\\interfaces\\repositories\\' . Inflector::camelize($name) . 'Interface';
			$arr[$name]['repository'] = $namespace . '\\repositories\\ar\\' . Inflector::camelize($name) . 'Repository';
			$arr[$name]['message'] = $namespace . '\\messages\\ru\\' . Inflector::underscore($name);
		}
		$services = self::getNames($domainConfig['services']);
		foreach($services as $name) {
			$arr[$name]['serviceInterface'] = $namespace . '\\interfaces\\services\\' . Inflector::camelize($name) . 'Interface';
			$arr[$name]['service'] = $namespace . '\\services\\' . Inflector::camelize($name) . 'Service';
			$arr[$name]['message'] = $namespace . '\\messages\\ru\\' . Inflector::underscore($name);
		}
		return $arr;
	}
	
	private static function generateName($namespace, $n, $items) {
		if(isset($items['entity'])) {
			$generator = new EntityGenerator;
			$generator->name = $items['entity'];
			$generator->run();
		}
		if(isset($items['repository'])) {
			$repositoryDocBlock = [
				[
					'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
					'type' => '\\' . $namespace . '\\Domain',
					'value' => 'domain',
				],
			];
			
			$generator = new RepositoryGenerator();
			$generator->name = $items['repository'];
			$generator->uses = [
				['name' => $items['repositoryInterface']],
			];
			$generator->implements = basename($items['repositoryInterface']);
			$generator->docBlockParameters = $repositoryDocBlock;
			$generator->run();
			
			$generator = new RepositoryInterfaceGenerator();
			$generator->name = $items['repositoryInterface'];
			$generator->docBlockParameters = $repositoryDocBlock;
			$generator->run();
			
			$generator = new RepositorySchemaGenerator();
			$generator->name = $namespace . '\\repositories\\schema\\' . Inflector::camelize($n) . 'Schema';
			$generator->run();
		}
		
		if(isset($items['service'])) {
			$serviceDocBlock = [
				[
					'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
					'type' => '\\' . $namespace . '\\Domain',
					'value' => 'domain',
				],
				[
					'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
					'type' => '\\' . $items['repositoryInterface'],
					'value' => 'repository',
				],
			];
			
			$generator = new ServiceGenerator();
			$generator->name = $items['service'];
			$generator->uses = [
				['name' => $items['serviceInterface']],
			];
			$generator->implements = basename($items['serviceInterface']);
			$generator->docBlockParameters = $serviceDocBlock;
			$generator->run();
			
			$generator = new ServiceInterfaceGenerator();
			$generator->name = $items['serviceInterface'];
			$generator->docBlockParameters = $serviceDocBlock;
			$generator->run();
		}
		
		if(isset($items['message'])) {
			$generator = new MessageGenerator();
			$generator->name = $items['message'];
			$generator->run();
		}
	}
	
}

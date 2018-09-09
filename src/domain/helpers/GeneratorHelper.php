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
use yii2lab\extension\code\helpers\parser\DocCommentHelper;
use yii2lab\extension\code\helpers\parser\TokenCollectionHelper;
use yii2lab\extension\code\helpers\parser\TokenHelper;
use yii2lab\extension\yii\helpers\FileHelper;

class GeneratorHelper {
	
	public static function generateDomain($namespace) {
		$arr = self::getAllNames($namespace);
		//$repos = $servs = [];
		foreach($arr as $n => $items) {
			self::generateName($namespace, $n, $items);
			/*if(isset($items['repository'])) {
				$repos[] = $n;
			}
			if(isset($items['service'])) {
				$servs[] = $n;
			}*/
		}
		/*if($repos) {
			self::generateVirtualRepositoryInterface($repos, $namespace);
		}
		
		self::updateDomainDocComment($namespace, $servs);*/
		
	}
	
	/*private static function generateVirtualRepositoryInterface($repos, $namespace) {
		$repositoryDocBlock = [];
		foreach($repos as $repo) {
			$repositoryDocBlock[] = [
				'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
				'type' => '\\' . $namespace . '\\interfaces\\repositories\\' . ucfirst($repo) . 'Interface',
				'value' => $repo,
			];
		}
		$generator = new RepositoryInterfaceGenerator();
		$generator->name = $namespace . '\\interfaces\\repositories\\RepositoriesInterface';
		$generator->docBlockParameters = $repositoryDocBlock;
		$generator->extends = [];
		$generator->defaultUses = [];
		$generator->run();
	}*/
	
	/*private static function updateDomainDocComment($namespace, $servs) {
		$fileName = FileHelper::getAlias('@' . $namespace . '\\Domain');
		$tokenCollection = TokenHelper::load($fileName . DOT . 'php');
		$docCommentIndexes = TokenCollectionHelper::getDocCommentIndexes($tokenCollection);
		$docComment = $tokenCollection[$docCommentIndexes[0]]->value;
		$entity = DocCommentHelper::parse($docComment);
		foreach($servs as $serv) {
			$entity = DocCommentHelper::addAttribute($entity, [
				'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
				'value' => [
					'\\'.$namespace.'\\interfaces\\services\\'.ucfirst($serv).'Interface',
					'$' . $serv,
				],
			]);
		}
		$entity = DocCommentHelper::addAttribute($entity, [
			'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
			'value' => [
				'\\' . $namespace . '\\interfaces\\repositories\\RepositoriesInterface',
				'$repositories',
			],
		]);
		$doc = DocCommentHelper::generate($entity);
		$tokenCollection[$docCommentIndexes[0]]->value = $doc;
		TokenHelper::save($fileName . DOT . 'php', $tokenCollection);
	}*/
	
	private static function getNames($definitions) {
		$nameList = [];
		foreach($definitions as $serviceName => $definition) {
			$nameList[] = is_integer($serviceName) ? $definition : $serviceName;
		}
		return $nameList;
	}
	
	private static function getAllNames($namespace) {
		$className = $namespace . '\\Domain';
		$domainConfig = DomainHelper::getConfigFromDomainClass($className);
		$arr = [];
		if(!empty($domainConfig['repositories'])) {
			$repositories = self::getNames($domainConfig['repositories']);
			foreach($repositories as $name) {
				$arr[$name]['entity'] = $namespace . '\\entities\\' . Inflector::camelize($name) . 'Entity';
				$arr[$name]['repositoryInterface'] = $namespace . '\\interfaces\\repositories\\' . Inflector::camelize($name) . 'Interface';
				$arr[$name]['repository'] = $namespace . '\\repositories\\ar\\' . Inflector::camelize($name) . 'Repository';
				$arr[$name]['message'] = $namespace . '\\messages\\ru\\' . Inflector::underscore($name);
			}
		}
		if(!empty($domainConfig['services'])) {
			$services = self::getNames($domainConfig['services']);
			foreach($services as $name) {
				$arr[$name]['serviceInterface'] = $namespace . '\\interfaces\\services\\' . Inflector::camelize($name) . 'Interface';
				$arr[$name]['service'] = $namespace . '\\services\\' . Inflector::camelize($name) . 'Service';
				$arr[$name]['message'] = $namespace . '\\messages\\ru\\' . Inflector::underscore($name);
			}
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
			];
			if(isset($items['repository'])) {
				$serviceDocBlock[] = [
					'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
					'type' => '\\' . $items['repositoryInterface'],
					'value' => 'repository',
				];
			}
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

<?php

namespace yii2module\vendor\domain\services;

use yii\helpers\Inflector;
use yii2lab\designPattern\scenario\helpers\ScenarioHelper;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\extension\code\entities\ClassConstantEntity;
use yii2lab\extension\code\entities\ClassEntity;
use yii2lab\extension\code\entities\ClassMethodEntity;
use yii2lab\extension\code\entities\ClassUseEntity;
use yii2lab\extension\code\entities\ClassVariableEntity;
use yii2lab\extension\code\entities\DocBlockEntity;
use yii2lab\domain\Domain;
use yii2lab\extension\code\helpers\ClassHelper;
use yii2lab\extension\code\scenarios\generator\EntityGenerator;
use yii2lab\extension\code\scenarios\generator\MessageGenerator;
use yii2lab\extension\code\scenarios\generator\RepositoryGenerator;
use yii2lab\extension\code\scenarios\generator\RepositoryInterfaceGenerator;
use yii2lab\extension\code\scenarios\generator\RepositorySchemaGenerator;
use yii2lab\extension\code\scenarios\generator\ServiceGenerator;
use yii2lab\extension\code\scenarios\generator\ServiceInterfaceGenerator;
use yii2lab\helpers\generator\ClassGeneratorHelper;
use yii2lab\helpers\yii\FileHelper;
use yii2module\vendor\domain\repositories\file\GeneratorRepository;

class GeneratorService extends ActiveBaseService {

	public $author;
	public $email;
	public $owners;
	public $install = [
		'commands' => ['Module', 'Domain', 'Package', 'Rbac'],
	];
	
	public function generateDomain($owner, $name, $version = null) {
		$data = $this->getData($owner, $name);
		
		/** @var Domain $domainInstance */
		//$domainInstance = ClassHelper::createObject();
		//$domainConfig = $domainInstance->config();
		
		$namespace = $owner . '\\' . $name . '\\domain';
		$className = $namespace . '\\Domain';
		$domainConfig = DomainHelper::getConfigFromDomainClass($className);
		
		$arr = [];
		
		//self::getCode();
		
		$repositories = $this->getNames($domainConfig['repositories']);
		foreach($repositories as $name) {
			$arr[$name]['entity'] = $namespace . '\\entities\\' . Inflector::camelize($name) . 'Entity';
			$arr[$name]['repositoryInterface'] = $namespace . '\\interfaces\\repositories\\' . Inflector::camelize($name) . 'Interface';
			$arr[$name]['repository'] = $namespace . '\\repositories\\ar\\' . Inflector::camelize($name) . 'Repository';
			$arr[$name]['message'] = $namespace . '\\messages\\ru\\' . Inflector::underscore($name);
		}
		
		$services = $this->getNames($domainConfig['services']);
		foreach($services as $name) {
			$arr[$name]['serviceInterface'] = $namespace . '\\interfaces\\services\\' . Inflector::camelize($name) . 'Interface';
			$arr[$name]['service'] = $namespace . '\\services\\' . Inflector::camelize($name) . 'Service';
			$arr[$name]['message'] = $namespace . '\\messages\\ru\\' . Inflector::underscore($name);
		}
		
		foreach($arr as $n => $items) {
			if(isset($items['entity'])) {
				$generator = new EntityGenerator;
				$generator->name = $items['entity'];
				$generator->run();
			}
			if(isset($items['repository'])) {
				$generator = new RepositoryGenerator();
				$generator->name = $items['repository'];
				$generator->run();
				
				$generator = new RepositoryInterfaceGenerator();
				$generator->name = $items['repositoryInterface'];
				$generator->run();
				
				$generator = new RepositorySchemaGenerator();
				$generator->name = $namespace . '\\repositories\\schema\\' . Inflector::camelize($n) . 'Schema';
				$generator->run();
			}
			
			if(isset($items['service'])) {
				$generator = new ServiceGenerator();
				$generator->name = $items['service'];
				$generator->run();
				
				$generator = new ServiceInterfaceGenerator();
				$generator->name = $items['serviceInterface'];
				$generator->run();
			}
			
			if(isset($items['message'])) {
				$generator = new MessageGenerator();
				$generator->name = $items['message'];
				$generator->run();
			}
			
		}
		
		prr($arr);
		prr($data);
		//prr($domainConfig);
		
	}
	
	private function getRepositoryNames($definitions) {
	
	}
	
	private function getServiceNames($definitions) {
	
	}
	
	private function getNames($definitions) {
		$nameList = [];
		foreach($definitions as $serviceName => $definition) {
			$nameList[] = is_integer($serviceName) ? $definition : $serviceName;
		}
		return $nameList;
	}
	
	public function generateAll($owner, $name, $types) {
		$data = $this->getData($owner, $name);
		$generatorConfig['data'] = $data;
		/** @var GeneratorRepository $repository */
		$repository = $this->repository;
		foreach($types as $type) {
			if(strpos($type, 'module') !== false) {
				list($moduleType, $moduleName) = explode(' ', $type);
				$generatorConfig['type'] = strtolower($moduleType);
				$repository->generate($generatorConfig, 'Module');
			} else {
				$repository->generate($generatorConfig, $type);
			}
		}
	}
	
	public function install($owner, $name) {
		$data = $this->getData($owner, $name);
		$generatorConfig['data'] = $data;
		/** @var GeneratorRepository $repository */
		$repository = $this->repository;
		foreach($this->install['commands'] as $name) {
			$repository->install($generatorConfig, $name);
		}
	}
	
	private function getData($owner, $name) {
		$nameAlias = Inflector::camelize($name);
		$nameAlias{0} = strtolower($nameAlias{0});
		return [
			'owner' => $owner,
			'name' => $name,
			'Name' => ucfirst($name),
			'nameAlias' => $nameAlias,
			//'entity' => ,
			//'Entity' => ucfirst(),
			'title' => ucfirst($name),
			'namespace' => $owner . BSL . $name,
			'alias' => '@' . $owner . SL . $name,
			'alias_name' => $owner . SL . $name,
			'full_name' => $owner . SL . 'yii2-' . $name,
			'full_path' => VENDOR_DIR . DS . $owner . DS . 'yii2-' . $name,
			'author' => $this->author,
			'email' => $this->email,
			'license' => 'MIT',
			'year' => date('Y'),
		];
	}
	
	private function getCode() {
		
		/*$define = [
			'name' => 'common\enums\rbac\PermissionEnum',
			'' => ,
			'' => ,
			'' => ,
			'' => ,
			'' => ,
			'' => ,
			'' => ,
		];*/
		
		
		$classEntity = new ClassEntity();
		$classEntity->name = 'yii2woop\history\domain\PermissionEnum';
		$classEntity->is_abstract = true;
		$classEntity->extends = 'BaseEnum';
		$classEntity->implements = 'EnumInterface';
		$classEntity->doc_block = new DocBlockEntity([
			'title' => 'Class ' . $classEntity->name,
		]);
		$classEntity->uses = [
			new ClassUseEntity([
				'name' => 'ArTrait',
			]),
		];
		$classEntity->constants = [
			[
				'name' => 'typeOfVar',
				'value' => 'var',
			],
		];
		$classEntity->variables = [
			[
				'name' => 'id',
				'value' => 'null',
			],
			[
				'name' => 'name',
				'value' => 'null',
			],
		];
		$classEntity->methods = [
			[
				'name' => 'one',
			],
			[
				'name' => 'all',
			],
		];
		$ccode = \yii2lab\extension\code\helpers\ClassHelper::generate($classEntity);
		
		prr($ccode,1,0);
	}
	
}

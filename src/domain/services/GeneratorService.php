<?php

namespace yii2module\vendor\domain\services;

use yii\helpers\Inflector;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\helpers\ClassHelper;
use yii2lab\domain\Domain;
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
		}
		
		foreach($arr as $n => $items) {
			if(isset($items['entity'])) {
				$config = [
					'className' => $items['entity'],
					'use' => ['yii2lab\domain\BaseEntity'],
					'afterClassName' => 'extends BaseEntity',
					//'code' => $this->getCode(),
				];
				ClassGeneratorHelper::generate($config);
			}
			if(isset($items['repository'])) {
				$config = [
					'className' => $items['repository'],
					'use' => ['yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository'],
					'afterClassName' => 'extends BaseActiveArRepository',
					//'code' => $this->getCode(),
				];
				ClassGeneratorHelper::generate($config);
				
				$config = [
					'className' => $items['repositoryInterface'],
					
					'use' => ['yii2lab\domain\interfaces\repositories\CrudInterface'],
					'afterClassName' => 'extends CrudInterface',
					//'code' => $this->getCode(),
				];
				ClassGeneratorHelper::generate($config);
				
				$config = [
					'className' => $namespace . '\\repositories\\schema\\' . Inflector::camelize($n) . 'Schema',
					'use' => ['yii2lab\domain\repositories\relations\BaseSchema'],
					'afterClassName' => 'extends BaseSchema',
					//'code' => $this->getCode(),
				];
				ClassGeneratorHelper::generate($config);
				
				FileHelper::save(ROOT_DIR . DS . $items['message'] . DOT . 'php', '<?php ');
				
				$config = [
					'className' => $items['service'],
					'use' => ['yii2lab\domain\services\base\BaseActiveService'],
					'afterClassName' => 'extends BaseActiveService',
					//'code' => $this->getCode(),
				];
				ClassGeneratorHelper::generate($config);
				
				$config = [
					'className' => $items['serviceInterface'],
					
					'use' => ['yii2lab\domain\interfaces\services\CrudInterface'],
					'afterClassName' => 'extends CrudInterface',
					//'code' => $this->getCode(),
				];
				ClassGeneratorHelper::generate($config);
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
 
}

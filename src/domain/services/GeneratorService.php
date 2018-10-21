<?php

namespace yii2module\vendor\domain\services;

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii2lab\domain\services\base\BaseService;
use yii2module\vendor\domain\filters\generator\EntityGenerator;
use yii2module\vendor\domain\filters\generator\RepositoryGenerator;
use yii2module\vendor\domain\filters\generator\ServiceGenerator;
use yii2module\vendor\domain\helpers\GeneratorHelper;
use yii2module\vendor\domain\helpers\PrettyHelper;
use yii2module\vendor\domain\repositories\file\GeneratorRepository;

/**
 * Class GeneratorService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property-read GeneratorRepository $repository
 * @property-read \yii2module\vendor\domain\Domain $domain
 */
class GeneratorService extends BaseService {

	public $author;
	public $email;
	public $owners;
	public $install = [
		'commands' => ['Module', 'Domain', 'Package', 'Rbac'],
	];
	
	public function generateDomain($namespace) {
		$namespace = str_replace(SL, BSL, $namespace);
		GeneratorHelper::generateDomain($namespace);
	}
	
	public function generateRepository($data) {
		$gen = new RepositoryGenerator();
		$gen->namespace = $data['namespace'];
		$gen->name = $data['name'];
		$gen->drivers = $data['drivers'];
		$gen->isActive = ArrayHelper::getValue($data, 'isActive', false);
		$gen->run();
		PrettyHelper::refreshDomain($data['namespace']);
	}
	
	public function generateService($data) {
		$gen = new ServiceGenerator;
		$gen->namespace = $data['namespace'];
		$gen->name = $data['name'];
		$gen->isActive = ArrayHelper::getValue($data, 'isActive', false);
		$gen->run();
		PrettyHelper::refreshDomain($data['namespace']);
	}
	
	public function generateEntity($data) {
		$gen = new EntityGenerator();
		$gen->namespace = $data['namespace'];
		$gen->name = $data['name'];
		$gen->attributes = ArrayHelper::getValue($data, 'attributes', []);
		$gen->run();
		PrettyHelper::refreshDomain($data['namespace']);
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

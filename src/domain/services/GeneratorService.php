<?php

namespace yii2module\vendor\domain\services;

use yii\helpers\Inflector;
use yii2lab\domain\services\ActiveBaseService;
use yii2module\vendor\domain\helpers\GeneratorHelper;
use yii2module\vendor\domain\repositories\file\GeneratorRepository;

class GeneratorService extends ActiveBaseService {

	public $author;
	public $email;
	public $owners;
	public $install = [
		'commands' => ['Module', 'Domain', 'Package', 'Rbac'],
	];
	
	protected function getDomainCofig($domainClass) {
	
	}
	
	public function generateDomain($namespace) {
		GeneratorHelper::generateDomain($namespace);
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

<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\services\ActiveBaseService;
use yii2module\vendor\domain\repositories\file\GeneratorRepository;

class GeneratorService extends ActiveBaseService {

	public $author;
	public $email;
	public $ownerList;
	
	public function generateAll($owner, $name, $types) {
		$data = $this->getData($owner, $name);
		$generatorConfig['data'] = $data;
		/** @var GeneratorRepository $repository */
		$repository = $this->repository;
		foreach($types as $type) {
			if(strpos($type, 'module') !== false) {
				list($moduleType, $moduleName) = explode(' ', $type);
				$generatorConfig['type'] = strtolower($moduleType);
				$repository->runGenerator($generatorConfig, 'Module');
			} else {
				$repository->runGenerator($generatorConfig, $type);
			}
		}
	}
	
	public function install($owner, $name, $types) {
		$data = $this->getData($owner, $name);
		$generatorConfig['data'] = $data;
		/** @var GeneratorRepository $repository */
		$repository = $this->repository;
		foreach($types as $type) {
			if(strpos($type, 'module') !== false) {
				list($moduleType, $moduleName) = explode(' ', $type);
				$generatorConfig['type'] = strtolower($moduleType);
				$repository->runInstall($generatorConfig, 'Module');
			} elseif($type == 'Domain') {
				$repository->runInstall($generatorConfig, 'Domain');
			}
		}
	}
	
	private function getData($owner, $name) {
		return [
			'owner' => $owner,
			'name' => $name,
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

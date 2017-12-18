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
	
	private function getData($owner, $name) {
		return [
			'owner' => $owner,
			'name' => $name,
			'author' => $this->author,
			'email' => $this->email,
			'license' => 'MIT',
			'year' => date('Y'),
		];
	}
 
}

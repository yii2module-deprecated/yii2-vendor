<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\services\ActiveBaseService;
use yii2module\vendor\domain\enums\TypeEnum;

class GeneratorService extends ActiveBaseService {

	public $author;
	public $email;
	public $ownerList;
	
	public function generatePackage($owner, $name) {
		$data = $this->getData($owner, $name);
		$this->repository->generateComposer($data);
		$this->repository->generateGitIgnore($data);
	}
	
	public function generateLicense($owner, $name) {
		$data = $this->getData($owner, $name);
		$this->repository->generateLicense($data);
	}
	
	public function generateGuide($owner, $name) {
		$data = $this->getData($owner, $name);
		$this->repository->generateGuide($data);
	}
	
	public function generateReadme($owner, $name) {
		$data = $this->getData($owner, $name);
		$this->repository->generateReadme($data);
	}
	
	public function generateTest($owner, $name) {
		$data = $this->getData($owner, $name);
		$this->repository->generateTest($data);
	}
	
	public function generateAll($owner, $name, $types) {
		if(in_array(TypeEnum::PACKAGE, $types)) {
			$this->generatePackage($owner, $name);
		}
		if(in_array(TypeEnum::LICENSE, $types)) {
			$this->generateLicense($owner, $name);
		}
		if(in_array(TypeEnum::GUIDE, $types)) {
			$this->generateGuide($owner, $name);
		}
		if(in_array(TypeEnum::README, $types)) {
			$this->generateReadme($owner, $name);
		}
		if(in_array(TypeEnum::TEST, $types)) {
			$this->generateTest($owner, $name);
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

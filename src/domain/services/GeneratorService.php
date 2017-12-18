<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\services\ActiveBaseService;
use yii2module\vendor\domain\enums\TypeEnum;

class GeneratorService extends ActiveBaseService {

	public $author;
	public $email;
	public $ownerList;
	
	public function generateAll($owner, $name, $types) {
		$data = $this->getData($owner, $name);
		if(in_array(TypeEnum::PACKAGE, $types)) {
			$this->repository->generateComposer($data);
			$this->repository->generateGitIgnore($data);
		}
		if(in_array(TypeEnum::LICENSE, $types)) {
			$this->repository->generateLicense($data);
		}
		if(in_array(TypeEnum::GUIDE, $types)) {
			$this->repository->generateGuide($data);
		}
		if(in_array(TypeEnum::README, $types)) {
			$this->repository->generateReadme($data);
		}
		if(in_array(TypeEnum::TEST, $types)) {
			$this->repository->generateTest($data);
		}
		if(in_array(TypeEnum::DOMAIN, $types)) {
			$this->repository->generateDomain($data);
		}
		if(in_array(TypeEnum::API_MODULE, $types)) {
			$this->repository->generateApiModule($data);
		}
		if(in_array(TypeEnum::ADMIN_MODULE, $types)) {
			$this->repository->generateAdminModule($data);
		}
		if(in_array(TypeEnum::WEB_MODULE, $types)) {
			$this->repository->generateWebModule($data);
		}
		if(in_array(TypeEnum::CONSOLE_MODULE, $types)) {
			$this->repository->generateConsoleModule($data);
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

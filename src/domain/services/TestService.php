<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\services\ActiveBaseService;
use yii2module\vendor\domain\repositories\file\GitRepository;

/**
 * Class GitService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property GitRepository $repository
 */
class TestService extends ActiveBaseService {
	
	public $ignore;
	
	public function run($entity) {
		return $this->repository->run($entity);
	}
	
}

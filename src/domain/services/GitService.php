<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\services\base\BaseActiveService;
use yii2module\vendor\domain\repositories\file\GitRepository;

/**
 * Class GitService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property-read GitRepository $repository
 * @property-read \yii2module\vendor\domain\Domain $domain
 */
class GitService extends BaseActiveService {
	
	public $ignore;
	
	public function pull($entity) {
		return $this->repository->pull($entity);
	}
	
	public function push($entity) {
		return $this->repository->push($entity);
	}
	
	public function checkout($entity, $branch) {
		return $this->repository->checkout($entity, $branch);
	}
	
}

<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\data\Query;
use yii2lab\domain\services\ActiveBaseService;
use yii2module\vendor\domain\repositories\file\GitRepository;

/**
 * Class GitService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property GitRepository $repository
 */
class GitService extends ActiveBaseService {
	
	public $ignore;
	
	public function pull($entity) {
		return $this->repository->pull($entity);
	}
	
}

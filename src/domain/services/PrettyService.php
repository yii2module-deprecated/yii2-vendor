<?php

namespace yii2module\vendor\domain\services;

use yii\helpers\ArrayHelper;
use yii2lab\domain\data\Query;
use yii2lab\domain\services\base\BaseActiveService;
use yii2module\vendor\domain\helpers\PrettyHelper;
use yii2module\vendor\domain\repositories\file\PrettyRepository;

/**
 * Class PrettyService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property-read PrettyRepository $repository
 */
class PrettyService extends BaseActiveService {

	public function updateById($id, $data) {
		PrettyHelper::refreshDomain($id);
	}
	
	public function oneById($id, Query $query = null) {
		return $this->repository->oneById($id, $query);
	}
	
	public function all(Query $query = null) {
		$packageDomains = $this->repository->allPackagesDomain();
		$projectDomains = $this->repository->allProjectDomain();
		return ArrayHelper::merge($packageDomains, $projectDomains);
	}
	
}

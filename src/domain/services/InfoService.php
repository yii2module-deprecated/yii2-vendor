<?php

namespace yii2module\vendor\domain\services;

use Yii;
use yii2lab\domain\services\BaseService;

class InfoService extends BaseService {
	
	public function allChanged() {
		return $this->repository->allChangedRepositoryByOwners(Yii::$app->vendor->generator->ownerList);
	}
	
	public function allByOwner($owner) {
		return $this->repository->allRepositoryByOwners([$owner]);
	}
	
	public function all() {
		return $this->repository->allRepositoryByOwners(Yii::$app->vendor->generator->ownerList);
	}
	
}

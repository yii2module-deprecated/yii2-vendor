<?php

namespace yii2module\vendor\domain\services;

use Yii;
use yii2lab\domain\services\BaseService;

class InfoService extends BaseService {
	
	public function allForUpVersion() {
		$collection = $this->repository->allForUpVersion(Yii::$app->vendor->generator->ownerList);
		$newCollection = [];
		foreach($collection as $entity) {
			if($entity->need_release) {
				$newCollection[] = $entity;
			}
		}
		return $newCollection;
	}
	
	public function allChanged() {
		return $this->repository->allChangedRepositoryByOwners(Yii::$app->vendor->generator->ownerList);
	}
	
	public function allVersion() {
		return $this->repository->allVersionRepositoryByOwners(Yii::$app->vendor->generator->ownerList);
	}
	
	public function allByOwner($owner) {
		return $this->repository->allRepositoryByOwners([$owner]);
	}
	
	public function all() {
		return $this->repository->allRepositoryByOwners(Yii::$app->vendor->generator->ownerList);
	}
	
}

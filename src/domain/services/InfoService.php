<?php

namespace yii2module\vendor\domain\services;

use Yii;
use yii2lab\domain\data\Query;
use yii2lab\domain\services\BaseService;
use yii2module\vendor\domain\repositories\file\InfoRepository;

/**
 * Class InfoService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property InfoRepository $repository
 */
class InfoService extends BaseService {
	
	public function allForUpVersion($query = null) {
		$collection = $this->repository->allForUpVersion(Yii::$app->vendor->generator->ownerList, $query);
		$newCollection = [];
		foreach($collection as $entity) {
			if($entity->need_release) {
				$newCollection[] = $entity;
			}
		}
		return $newCollection;
	}
	
	public function allChanged($query = null) {
		$query = Query::forge($query);
		$query->with('has_changes');
		return $this->repository->allChangedRepositoryByOwners(Yii::$app->vendor->generator->ownerList, $query);
	}
	
	public function allVersion($query = null) {
		return $this->repository->allVersionRepositoryByOwners(Yii::$app->vendor->generator->ownerList, $query);
	}
	
	public function allByOwner($owner, $query = null) {
		return $this->repository->allRepositoryByOwners([$owner], $query);
	}
	
	public function all($query = null) {
		return $this->repository->all(Yii::$app->vendor->generator->ownerList, $query);
	}
	
}

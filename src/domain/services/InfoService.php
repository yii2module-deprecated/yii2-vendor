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
	
	public function oneById($id, $query = null) {
		return $this->repository->oneById($id, $query);
	}
	
	public function all($query = null) {
		return $this->repository->all($query);
	}
	
	public function allForUpVersion($query = null) {
		$collection = $this->repository->allForUpVersion($query);
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
		return $this->repository->allChanged($query);
	}
	
	public function allVersion($query = null) {
		return $this->repository->allVersion($query);
	}
	
	public function allByOwner($owner, $query = null) {
		return $this->repository->allRepositoryByOwners([$owner]);
	}
	
}

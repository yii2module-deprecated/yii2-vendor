<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\services\ReadInterface;
use yii2lab\domain\services\BaseService;
use yii2module\vendor\domain\repositories\file\InfoRepository;

/**
 * Class InfoService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property InfoRepository $repository
 */
class InfoService extends BaseService implements ReadInterface {
	
	public function isExistsById($id) {
		return $this->repository->isExistsById($id);
	}
	
	public function isExists($condition) {
		return $this->repository->isExists($condition);
	}
	
	public function one(Query $query = null) {
		return $this->repository->one($query);
	}
	
	public function oneById($id, Query $query = null) {
		return $this->repository->oneById($id, $query);
	}
	
	public function all(Query $query = null) {
		return $this->repository->all($query);
	}
	
	public function count(Query $query = null) {
		return $this->repository->count($query);
	}
	
	public function allForUpVersion($query = null) {
		$collection = $this->repository->allWithTagAndCommit($query);
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
		return $this->repository->allWithTag($query);
	}
	
	public function allByOwner($owner, $query = null) {
		return $this->repository->allRepositoryByOwners([$owner]);
	}
	
}

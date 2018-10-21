<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\data\Query;
use yii2lab\domain\services\base\BaseActiveService;
use yii2module\vendor\domain\repositories\file\InfoRepository;

/**
 * Class InfoService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property-read InfoRepository $repository
 * @property-read \yii2module\vendor\domain\Domain $domain
 */
class InfoService extends BaseActiveService {
	
	public $ignore;
	
	public function allForRelease($query = null) {
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
	
	public function shortNamesByOwner($owner) {
		return $this->repository->shortNamesByOwner($owner);
	}
	
	public function usesById($id) {
		return $this->repository->usesById($id);
	}
	
	public function allWithGuide() {
		$query = Query::forge();
		$query->where('has_guide', true);
		return $this->repository->all($query);
	}
	
	public function allWithHasTest() {
		$query = Query::forge();
		$query->where('has_test', true);
		return $this->repository->all($query);
	}
	
}

<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\data\Query;
use yii2lab\domain\services\ActiveBaseService;
use yii2module\vendor\domain\repositories\file\InfoRepository;

/**
 * Class InfoService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property InfoRepository $repository
 */
class InfoService extends ActiveBaseService {
	
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
	
}

<?php

namespace yii2module\vendor\domain\repositories\file;

use yii\web\NotFoundHttpException;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\repositories\BaseRepository;
use yii2module\rest_client\helpers\ArrayHelper;
use yii2module\vendor\domain\entities\RepoEntity;
use yii2module\vendor\domain\helpers\RepositoryHelper;
use yii2module\vendor\domain\helpers\UseHelper;

class InfoRepository extends BaseRepository implements ReadInterface {
	
	protected $withList = ['branch', 'has_changes', 'has_readme', 'has_guide', 'has_license', 'has_test', 'version', 'need_release', 'head_commit'];
	
	public function isExistsById($id) {
		try {
			$this->oneById($id);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function isExists($condition) {
		/** @var Query $query */
		$query = Query::forge();
		if(is_array($condition)) {
			$query->whereFromCondition($condition);
		} else {
			$query->where($this->primaryKey, $condition);
		}
		try {
			$this->one($query);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function oneById($id, Query $query = null) {
		$query = Query::forge($query);
		$query->where('id', $id);
		return $this->one($query);
	}
	
	public function one($query = null) {
		$query = Query::forge($query);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException();
		}
		return $collection[0];
	}
	
	public function all(Query $query = null) {
		$query = Query::forge($query);
		$queryClone = $this->removeRelationWhere($query);
		$list = RepositoryHelper::allByOwners($this->domain->generator->owners);
		$filteredList = ArrayIterator::allFromArray($queryClone, $list);
		$listWithRelation = [];
		foreach($filteredList as $item) {
			$listWithRelation[] = $this->loadRelations($item, $query);
		}
		$collection = $this->forgeEntity($listWithRelation, RepoEntity::className());
		return ArrayIterator::allFromArray($query, $collection);
	}
	
	public function count(Query $query = null) {
		$query = Query::forge($query);
		$queryCount = Query::cloneForCount($query);
		$collection = $this->all($queryCount);
		return count($collection);
	}
	
	public function allChanged($query = null) {
		$query = Query::forge($query);
		$query->where('has_changes', true);
		return $this->all($query);
	}
	
	public function allWithTagAndCommit($query = null) {
		$query = Query::forge($query);
		$query->with(['tags', 'commits']);
		return $this->all($query);
	}
	
	public function allWithTag($query = null) {
		$query = Query::forge($query);
		$query->with(['tags']);
		return $this->all($query);
	}

	public function shortNamesByOwner($owner) {
		$pathList = RepositoryHelper::namesByOwner($owner);
		foreach($pathList as &$name) {
			$name = strpos($name,'yii2-') === 0 ? substr($name, 5) : $name;
		}
		return $pathList;
	}
	
	public function usesById($id) {
		$entity = $this->oneById($id);
		//prr($entity->alias,1,1);
		$uses = UseHelper::find($entity->directory);
		$res = [];
		foreach($uses as $use) {
			if($this->isHasStr($use, $entity->alias)) {
				$res['self'][] = $use;
			} elseif($this->isHasStr($use, ['yii\\', 'Yii'])) {
				$res['yii'][] = $use;
			} elseif($this->isHasStr($use, ['common\\', 'frontend\\', 'backend\\', 'console\\', 'api\\', 'domain\\', ])) {
				$res['application'][] = $use;
			} else {
				$res['misc'][] = $use;
			}
		}
		foreach($res['misc'] as $vendor) {
			$arr = explode('\\', $vendor);
			$output = array_slice($arr, 0, 2);
			$res['required_packages'][] = implode('\\', $output);
		}
		if(!empty($res['yii'])) {
			$res['required_packages'][] = 'yiisoft/yii2';
		}
		foreach($res as &$item) {
			$item = array_unique($item);
		}
		return $res;
	}
	
	private function isHasStr($str, $needles) {
		$needles = ArrayHelper::toArray($needles);
		foreach($needles as $needle) {
			if(strpos($str, $needle) === 0) {
				return true;
			}
		}
		return false;
	}
	
	private function removeRelationWhere(Query $query = null) {
		$queryClone = clone $query;
		if($query->getParam('where')) {
			$queryClone->removeParam('where');
			foreach($query->getParam('where') as $whereField => $whereValue) {
				if(!in_array($whereField, $this->withList)) {
					$queryClone->where($whereField, $whereValue);
				}
			}
		}
		return $queryClone;
	}
	
	private function mergeWhereToWith(Query $query) {
		$with = $query->getParam('with');
		$with = $with ?: [];
		$where = $query->getParam('where');
		if(empty($where)) {
			return $with;
		}
		foreach($where as $field => $value) {
			if(in_array($field, $this->withList)) {
				$with[] = $field;
			}
		}
		return $with;
	}
	
	private function loadRelations($item, Query $query) {
		$with = $this->mergeWhereToWith($query);
		$where = $query->getParam('where');
		$where = $where ?: [];
		if(empty($with)) {
			return $item;
		}
		$repo = RepositoryHelper::gitInstance($item['package']);
		if($repo) {
			if(in_array('tags', $with) || isset($where['version']) || isset($where['need_release'])) {
				$item['tags'] = $repo->getTagsSha();
			}
			if(in_array('commits', $with) || isset($where['need_release']) || isset($where['head_commit'])) {
				$item['commits'] = $repo->getCommits();
			}
			if(in_array('branch', $with)) {
				$item['branch'] = $repo->getCurrentBranchName();
			}
			if(in_array('has_changes', $with)) {
				$item['has_changes'] = $repo->hasChanges();
			}
		}
		$item = RepositoryHelper::getHasInfo($item, $with);
		return $item;
	}
	
}

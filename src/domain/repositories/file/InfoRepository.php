<?php

namespace yii2module\vendor\domain\repositories\file;

use yii\base\InvalidArgumentException;
use yii\web\NotFoundHttpException;
use yii2lab\extension\scenario\collections\ScenarioCollection;
use yii2lab\extension\arrayTools\helpers\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2module\vendor\domain\entities\RepoEntity;
use yii2module\vendor\domain\entities\RequiredEntity;
use yii2module\vendor\domain\filters\IsIgnoreFilter;
use yii2module\vendor\domain\filters\IsPackageFilter;
use yii2module\vendor\domain\helpers\RepositoryHelper;
use yii2module\vendor\domain\helpers\UseHelper;

/**
 * Class InfoRepository
 *
 * @package yii2module\vendor\domain\repositories\file
 *
 * @property-read \yii2module\vendor\domain\Domain $domain
 */
class InfoRepository extends BaseRepository implements ReadInterface {
	
	protected $withList = ['branch', 'has_changes', 'has_readme', 'has_changelog', 'has_guide', 'has_license', 'has_test', 'version', 'need_release', 'head_commit', 'remote_url'];
	
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
			throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
		}
		return $collection[0];
	}
	
	public function all(Query $query = null) {
		$query = Query::forge($query);
		$queryClone = $this->removeRelationWhere($query);
		$list = RepositoryHelper::allByOwners($this->domain->generator->owners);
		$list = $this->separateCollection($list);
		$filteredList = ArrayIterator::allFromArray($queryClone, $list);
		$listWithRelation = [];
		foreach($filteredList as $item) {
			$listWithRelation[] = $this->loadRelations($item, $query);
		}
		$collection = $this->forgeEntity($listWithRelation, RepoEntity::class);
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
		$uses = UseHelper::find($entity->directory);
		$res = UseHelper::listToMap($uses, $entity);
		$res['required_packages'] = $this->forgeRequiredPackages($res['required_packages']);
		return $res['required_packages'];
	}
	
	private function forgeRequiredPackages($collection) {
		$packages = [];
		foreach($collection as $package) {
			try {
				$package = FileHelper::getAlias('@' . $package);
				$package = str_replace(VENDOR_DIR . DS, '', $package);
				$packageArr = explode(BSL, $package);
				$package = $packageArr[0] . BSL . $packageArr[1];
				$package = str_replace(BSL, SL, $package);
				$version = \Yii::$app->extensions[$package];
				$packages[] = new RequiredEntity($version);
			} catch(InvalidArgumentException $e) {}
		}
		return $packages;
	}
	
	/**
	 * @param $collection
	 *
	 * @return \yii2lab\domain\values\BaseValue
	 */
	private function separateCollection($collection) {
		$filters = [
			[
				'class' => IsPackageFilter::class,
			],
			[
				'class' => IsIgnoreFilter::class,
				'ignore' => $this->domain->info->ignore,
			],
		];
		$filterCollection = new ScenarioCollection($filters);
		$collection =  $filterCollection->runAll($collection);
		return $collection;
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
			if(in_array('required_packages', $with)) {
				$item['required_packages'] = $this->usesById($item['id']);
			}
			if(in_array('remote_url', $with)) {
				$item['remote_url'] = $repo->showRemote();
			}
		}
		$item = RepositoryHelper::getHasInfo($item, $with);
		return $item;
	}
	
}

<?php

namespace yii2module\vendor\domain\repositories\file;

use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\helpers\yii\FileHelper;
use yii2module\vendor\domain\helpers\GitShell;
use yii2module\vendor\domain\entities\RepoEntity;
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
		$list = $this->allRepositoryByOwners($this->domain->generator->owners);
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
		$pathList = $this->namesByOwner($owner);
		foreach($pathList as &$name) {
			$name = strpos($name,'yii2-') === 0 ? substr($name, 5) : $name;
		}
		return $pathList;
	}
	
	public function usesById($id) {
		$entity = $this->oneById($id);
		return UseHelper::run($entity->directory);
	}
	
	private function namesByOwner($owner) {
		$dir = Yii::getAlias('@vendor/' . $owner);
		$pathList = FileHelper::scanDir($dir);
		return $pathList;
	}
	
	private function allRepositoryByOwners($owners) {
		$map = $this->namesMapByOwners($owners);
		$list = [];
		foreach($map as $owner => $repositories) {
			foreach($repositories as $repository) {
				$name = strpos($repository,'yii2-') == 0 ? substr($repository, 5) : $repository;
				$list[] = [
					'id' => $owner . '-' . $repository,
					'owner' => $owner,
					'name' => $name,
					'package' => $owner . SL . $repository,
				];
			}
		}
		return $list;
	}
	
	private function removeRelationWhere($query) {
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
		$repo = $this->gitRepositoryInstance($item['package']);
		$with = $this->mergeWhereToWith($query);
		$where = $query->getParam('where');
		$where = $where ?: [];
		if($repo) {
			if(!empty($with)) {
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
				if(in_array('has_readme', $with)) {
					$item['has_readme'] = $this->hasReadme($item['package']);
				}
				if(in_array('has_guide', $with)) {
					$item['has_guide'] = $this->hasGuide($item['package']);
				}
				if(in_array('has_license', $with)) {
					$item['has_license'] = $this->hasLicense($item['package']);
				}
				if(in_array('has_test', $with)) {
					$item['has_test'] = $this->hasTest($item['package']);
				}
			}
			return $item;
		}
		return null;
	}
	
	private function hasReadme($package) {
		$file = $this->getPath($package . SL . 'README.md');
		$isExists = file_exists($file);
		return $isExists;
	}
	
	private function hasGuide($package) {
		$dir = $this->getPath($package . SL . 'guide');
		$isExists = is_dir($dir);
		return $isExists;
	}
	
	private function hasLicense($package) {
		$file = $this->getPath($package . SL . 'LICENSE');
		$isExists = file_exists($file);
		return $isExists;
	}
	
	private function hasTest($package) {
		$dir = $this->getPath($package . SL . 'tests');
		$configFile = $this->getPath($package . SL . 'codeception.yml');
		$isExists = is_dir($dir) && file_exists($configFile);
		return $isExists;
	}
	
	private function namesMapByOwners($owners) {
		$map = [];
		foreach($owners as $owner) {
			$map[$owner] = $this->namesByOwner($owner);
		}
		return $map;
	}
	
	private function getPath($package) {
		$dir = Yii::getAlias('@vendor/' . $package);
		$dir = FileHelper::normalizePath($dir);
		return $dir;
	}
	
	private function gitRepositoryInstance($package) {
		$dir = $this->getPath($package);
		if(!$this->isGit($dir)) {
			return null;
		}
		return new GitShell($dir);
	}
	
	private function isGit($dir) {
		return is_dir($dir) && is_dir($dir . DS . '.git');
	}
	
}

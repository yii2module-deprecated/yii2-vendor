<?php

namespace yii2module\vendor\domain\repositories\file;

use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\helpers\yii\FileHelper;
use yii2module\github\domain\helpers\GitShell;
use yii2module\vendor\domain\entities\RepoEntity;

class InfoRepository extends BaseRepository {
	
	public function oneById($id, $query = null) {
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
	
	public function all($query = null) {
		$query = Query::forge($query);
		$list = $this->allRepositoryByOwners($this->domain->generator->owners);
		$filteredList = ArrayIterator::allFromArray($query, $list);
		$listWithRelation = [];
		foreach($filteredList as $item) {
			$listWithRelation[] = $this->loadRelations($item, $query);
		}
		return $this->forgeEntity($listWithRelation, RepoEntity::className());
	}
	
	public function allChanged($query = null) {
		$query = Query::forge($query);
		$query->with(['has_changes']);
		$collection = $this->all($query);
		$iterator = new ArrayIterator;
		$q = Query::forge();
		$q->where('has_changes', 1);
		$iterator->setCollection($collection);
		return $iterator->all($q);
	}
	
	public function allForUpVersion($query = null) {
		$query = Query::forge($query);
		$query->with(['tags', 'commits']);
		return $this->all($query);
	}
	
	public function allVersion($query = null) {
		$query = Query::forge($query);
		$query->with(['tags']);
		return $this->all($query);
	}
	
	public function allRepositoryByOwners($owners) {
		$map = $this->namesMapByOwners($owners);
		$list = [];
		foreach($map as $owner => $repositories) {
			foreach($repositories as $repository) {
				$name = strpos($repository,'yii2-') == 0 ? substr($repository, 5) : $repository;
				$list[] = [
					'id' => hash('crc32b', $owner . DOT . $repository),
					'owner' => $owner,
					'name' => $name,
					'package' => $owner . SL . $repository,
				];
			}
		}
		return $list;
	}
	
	private function loadRelations($item, $query) {
		$repo = $this->gitRepositoryInstance($item['package']);
		/** @var Query $query */
		$with = $query->getParam('with');
		if($repo) {
			if(!empty($with)) {
				if(in_array('tags', $with)) {
					$item['tags'] = $repo->getTagsSha();
				}
				if(in_array('commits', $with)) {
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
	
	private function namesByOwner($owner) {
		$dir = Yii::getAlias('@vendor/' . $owner);
		$pathList = FileHelper::scanDir($dir);
		return $pathList;
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

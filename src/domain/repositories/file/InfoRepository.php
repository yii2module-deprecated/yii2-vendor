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
	
	public function allChangedRepositoryByOwners($owners, $query = null) {
		$query = Query::forge($query);
		$query->with(['has_changes']);
		$collection = $this->all($owners, $query);
		$iterator = new ArrayIterator;
		$q = Query::forge();
		$q->where('has_changes', 1);
		$iterator->setCollection($collection);
		return $iterator->all($q);
	}
	
	public function one($fullName, $query = null) {
		$query = Query::forge($query);
		$query->where('package', $fullName);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException();
		}
		return $collection[0];
	}
	
	public function all($owners, $query = null) {
		$query = Query::forge($query);
		$list = $this->allRepositoryByOwners($owners);
		$newList = [];
		foreach($list as $item) {
			$repo = $this->gitRepositoryInstance($item['package']);
			if($repo) {
				$with = $query->getParam('with');
				if(in_array('tags', $with)) {
					$item['tags'] = $repo->getTagsSha();
				}
				if(in_array('commits', $with)) {
					$item['commits'] = $repo->getCommits();
				}
				if(in_array('has_changes', $with)) {
					$item['has_changes'] = $repo->hasChanges();
				}
				$newList[] = $item;
			}
		}
		return $this->forgeEntity($newList, RepoEntity::className());
	}
	
	public function allForUpVersion($owners, $query = null) {
		$query = Query::forge($query);
		$query->with(['tags', 'commits']);
		return $this->all($owners, $query);
	}
	
	public function allVersionRepositoryByOwners($owners, $query = null) {
		$query = Query::forge($query);
		$query->with(['tags']);
		return $this->all($owners, $query);
	}
	
	public function allRepositoryByOwners($owners) {
		$map = $this->namesMapByOwners($owners);
		$list = [];
		foreach($map as $owner => $repositories) {
			foreach($repositories as $repository) {
				$name = strpos($repository,'yii2-') == 0 ? substr($repository, 5) : $repository;
				$list[] = [
					'owner' => $owner,
					'name' => $name,
					'package' => $owner . SL . $repository,
				];
			}
		}
		return $list;
	}
	
	public function namesMapByOwners($owners) {
		$map = [];
		foreach($owners as $owner) {
			$map[$owner] = $this->namesByOwner($owner);
		}
		return $map;
	}
	
	public function namesByOwner($owner) {
		$dir = Yii::getAlias('@vendor/' . $owner);
		$pathList = FileHelper::scanDir($dir);
		return $pathList;
	}
	
	private function getPath($fullName) {
		$dir = Yii::getAlias('@vendor/' . $fullName);
		$dir = FileHelper::normalizePath($dir);
		return $dir;
	}
	
	private function gitRepositoryInstance($fullName) {
		$dir = $this->getPath($fullName);
		if(!$this->isGit($dir)) {
			return null;
		}
		return new GitShell($dir);
	}
	
	private function isGit($dir) {
		return is_dir($dir) && is_dir($dir . DS . '.git');
	}
}

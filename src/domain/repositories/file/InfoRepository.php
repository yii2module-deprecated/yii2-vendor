<?php

namespace yii2module\vendor\domain\repositories\file;

use Yii;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\helpers\yii\FileHelper;
use yii2module\github\domain\helpers\GitShell;

class InfoRepository extends BaseRepository {
	
	public function allChangedRepositoryByOwners($owners) {
		$list = $this->allRepositoryByOwners($owners);
		$newList = [];
		foreach($list as $item) {
			$repo = $this->gitRepositoryInstance($item['full_name']);
			if($repo && $repo->hasChanges()) {
				$newList[] = $item;
			}
		}
		return $newList;
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
					'full_name' => $owner . SL . $repository,
					'alias' => $owner . SL . $name,
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

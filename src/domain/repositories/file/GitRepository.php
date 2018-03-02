<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\domain\repositories\BaseRepository;
use yii2module\vendor\domain\entities\RepoEntity;
use yii2module\vendor\domain\helpers\RepositoryHelper;

class GitRepository extends BaseRepository {
	
	public function pull(RepoEntity $entity) {
		$repo = RepositoryHelper::gitInstance($entity->package);
		$repo->checkout('master');
		$result = $repo->pullWithInfo();
		if($result == 'Already up-to-date.') {
			return false;
		} else {
			return $result;
		}
	}
	
	public function push(RepoEntity $entity) {
		$repo = RepositoryHelper::gitInstance($entity->package);
		$repo->pushWithInfo();
	}
	
	public function checkout(RepoEntity $entity, $branch) {
		$repo = RepositoryHelper::gitInstance($entity->package);
		$repo->checkout($branch);
	}
	
}

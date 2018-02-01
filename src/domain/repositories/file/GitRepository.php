<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\domain\repositories\BaseRepository;
use yii2module\vendor\domain\helpers\RepositoryHelper;

class GitRepository extends BaseRepository {
	
	public function pull($entity) {
		$repo = RepositoryHelper::gitInstance($entity->package);
		$result = $repo->pullWithInfo();
		if($result == 'Already up-to-date.') {
			return false;
		} else {
			return $result;
		}
	}
	
	public function push($entity) {
		$repo = RepositoryHelper::gitInstance($entity->package);
		$repo->pushWithInfo();
	}
	
}

<?php

namespace yii2module\vendor\domain\repositories\file;

use yii\web\NotFoundHttpException;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\misc\helpers\FilterHelper;
use yii2module\rest_client\helpers\ArrayHelper;
use yii2module\vendor\domain\entities\RepoEntity;
use yii2module\vendor\domain\helpers\RepositoryHelper;

class GitRepository extends BaseRepository {
	
	public function pull($entity) {
		$repo = RepositoryHelper::gitInstance($entity->package);
		$result = $repo->pullWithInfo();
		if($result = 'Already up-to-date.') {
			return false;
		} else {
			return $result;
		}
	}
	
}

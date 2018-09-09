<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\domain\repositories\BaseRepository;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2lab\extension\store\Store;
use yii2module\vendor\domain\entities\PackageEntity;

class PackageRepository extends BaseRepository {
	
	public function load($alias = null) {
		$fileName = $this->getFileName($alias);
		$store = new Store('json');
		$config = $store->load($fileName);
		return $this->forgeEntity([
			'alias' => $alias,
			'config' => $config,
		]);
	}
	
	public function save(PackageEntity $entity) {
		$fileName = $this->getFileName($entity->alias);
		$store = new Store('json');
		$store->save($fileName, $entity->config);
	}
	
	private function getFileName($alias = null) {
		$path = empty($alias) ? ROOT_DIR : FileHelper::getAlias($alias);
		return $path . DS . 'composer.json';
	}
	
}

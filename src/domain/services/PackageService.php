<?php

namespace yii2module\vendor\domain\services;

use Yii;
use yii2lab\domain\services\ActiveBaseService;
use yii2mod\helpers\ArrayHelper;
use yii2module\vendor\domain\entities\PackageEntity;
use yii2module\vendor\domain\repositories\file\PackageRepository;

/**
 * Class PackageService
 *
 * @package yii2module\vendor\domain\services
 * @property PackageRepository $repository
 */
class PackageService extends ActiveBaseService {
	
	public function versionToDev()
	{
		/** @var PackageEntity $entity */
		$entity = $this->repository->load();
		$config = $entity->config;
		$config['require'] = $this->toDev($config['require']);
		$config['require-dev'] = $this->toDev($config['require-dev']);
		$entity->config = $config;
		$this->repository->save($entity);
	}
	
	public function versionUpdate()
	{
		/** @var PackageEntity $entity */
		$entity = $this->repository->load();
		$config = $entity->config;
		$collection = Yii::$app->vendor->info->allVersion();
		$flatCollection = ArrayHelper::map($collection, 'full_name', 'version');
		$config['require'] = $this->update($config['require'], $flatCollection);
		$config['require-dev'] = $this->update($config['require-dev'], $flatCollection);
		$entity->config = $config;
		$this->repository->save($entity);
	}
	
	private function update($config, $flatCollection) {
		foreach($flatCollection as $fullName => $version) {
			if(isset($config[$fullName])) {
				$config[$fullName] = $this->flexFormat($version);
			}
		}
		return $config;
	}
	
	private function flexFormat($version, $from = 2) {
		$arr = explode('.', $version);
		for($i = $from; $i < count($arr); $i++) {
			$arr[$i] = '*';
		}
		return implode('.', $arr);
	}
	
	private function toDev($config) {
		$ownerList = Yii::$app->vendor->generator->ownerList;
		foreach($config as $fullName => &$version) {
			$arr = explode(SL, $fullName);
			if(count($arr) > 1) {
				list($owner, $name) = $arr;
				if(in_array($owner, $ownerList)) {
					$version = 'dev-master';
				}
			}
		}
		return $config;
	}
 
}

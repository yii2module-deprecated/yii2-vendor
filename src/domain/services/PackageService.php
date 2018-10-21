<?php

namespace yii2module\vendor\domain\services;

use yii2lab\domain\services\base\BaseActiveService;
use yii2mod\helpers\ArrayHelper;
use yii2module\vendor\domain\entities\PackageEntity;
use yii2module\vendor\domain\repositories\file\PackageRepository;

/**
 * Class PackageService
 *
 * @package yii2module\vendor\domain\services
 * @property-read PackageRepository $repository
 * @property-read \yii2module\vendor\domain\Domain $domain
 */
class PackageService extends BaseActiveService {
	
	public $aliases = [EMP];
	
	public function versionToDev()
	{
		if(empty($this->aliases)) {
			return;
		}
		$aliases = ArrayHelper::toArray($this->aliases);
		foreach($aliases as $alias) {
			$this->versionToDevItem($alias);
		}
	}
	
	public function versionUpdate()
	{
		if(empty($this->aliases)) {
			return;
		}
		$aliases = ArrayHelper::toArray($this->aliases);
		$collection = \App::$domain->vendor->info->allVersion();
		foreach($aliases as $alias) {
			$this->versionUpdateItem($alias, $collection);
		}
	}
	
	private function versionToDevItem($alias)
	{
		/** @var PackageEntity $entity */
		$entity = $this->repository->load($alias);
		$config = $entity->config;
		$config['require'] = $this->toDev(ArrayHelper::getValue($config, 'require', []));
		$config['require-dev'] = $this->toDev(ArrayHelper::getValue($config, 'require-dev', []));
		$config = $this->removeEmptyValues($config);
		$entity->config = $config;
		$this->repository->save($entity);
	}
	
	private function versionUpdateItem($alias, $collection)
	{
		/** @var PackageEntity $entity */
		$entity = $this->repository->load($alias);
		$config = $entity->config;
		$flatCollection = ArrayHelper::map($collection, 'package', 'version');
		$config['require'] = $this->formatVersion(ArrayHelper::getValue($config, 'require', []), $flatCollection);
		$config['require-dev'] = $this->formatVersion(ArrayHelper::getValue($config, 'require-dev', []), $flatCollection);
		$config = $this->removeEmptyValues($config);
		if($entity->config != $config) {
			$entity->config = $config;
			$this->repository->save($entity);
		}
	}
	
	private function removeEmptyValues($config) {
		foreach($config as $key => $value) {
			if(empty($value)) {
				unset($config[$key]);
			}
		}
		return $config;
	}
	
	private function formatVersion($config, $flatCollection) {
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
		$owners = \App::$domain->vendor->generator->owners;
		foreach($config as $fullName => &$version) {
			$arr = explode(SL, $fullName);
			if(count($arr) > 1) {
				list($owner, $name) = $arr;
				if(in_array($owner, $owners)) {
					$version = 'dev-master';
				}
			}
		}
		return $config;
	}
 
}

<?php

namespace yii2module\vendor\domain\services;

use yii\helpers\ArrayHelper;
use yii2lab\domain\data\EntityCollection;
use yii2lab\domain\data\Query;
use yii2lab\domain\services\base\BaseActiveService;
use yii2module\vendor\domain\entities\DomainEntity;
use yii2module\vendor\domain\entities\PackageEntity;
use yii2module\vendor\domain\helpers\FindHelper;
use yii2module\vendor\domain\helpers\PrettyHelper;
use yii2module\vendor\domain\repositories\file\PrettyRepository;

/**
 * Class PrettyService
 *
 * @package yii2module\vendor\domain\services
 *
 * @property-read PrettyRepository $repository
 * @property-read \yii2module\vendor\domain\Domain $domain
 */
class PrettyService extends BaseActiveService {

	public function updateById($id, $data) {
		PrettyHelper::refreshDomain($id);
	}
	
	public function oneById($id, Query $query = null) {
		return $this->repository->oneById($id, $query);
	}
	
	public function all(Query $query = null) {
		/** @var PackageEntity[] $packageCollection */
		$packageCollection = $this->domain->info->all();
		$aliases = ArrayHelper::getColumn($packageCollection, 'alias');
		$aliases[] = 'domain';
		$domains = FindHelper::scanForDomain($aliases);
		$collection = new EntityCollection(DomainEntity::class);
		foreach($domains as $domain) {
			$domainEntity = new DomainEntity;
			$domainEntity->path = $domain;
			$collection[] = $domainEntity;
		}
		return $collection;
	}
	
}

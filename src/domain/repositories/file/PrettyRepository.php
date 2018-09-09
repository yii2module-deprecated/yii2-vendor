<?php

namespace yii2module\vendor\domain\repositories\file;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2module\vendor\domain\entities\DomainEntity;
use yii2module\vendor\domain\entities\PackageEntity;
use yii2module\vendor\domain\helpers\PrettyHelper;

class PrettyRepository extends BaseRepository {
	
	public function forgeEntity($data, $class = null) {
		return parent::forgeEntity($data, DomainEntity::class);
	}
	
	public function oneById($id, Query $query = null) {
		$domainDir = FileHelper::getAlias('@' . $id);
		$types = [
			'repositories' => ['Repository', 'Schema'],
			'services' => ['Service'],
			'interfaces' => ['Interface'],
			'entities' => ['Entity'],
		];
		$domain = [];
		foreach($types as $type => $postFix) {
			$domain[$type] = PrettyHelper::scanDomain($domainDir . DS . $type, $postFix);
		}
		return $this->forgeEntity($domain);
	}
	
	public function allPackagesDomain() {
		/** @var PackageEntity[] $packageCollection */
		$packageCollection = Yii::$domain->vendor->info->all();
		$aliases = [];
		foreach($packageCollection as $package) {
			$domainAliasName = $package->alias;
			$aliasesNew = PrettyHelper::scanForDomainRecursive($domainAliasName);
			$aliases = ArrayHelper::merge($aliases, $aliasesNew);
		}
		return $aliases;
	}
	
	public function allProjectDomain() {
		$baseAlias = 'domain';
		return PrettyHelper::scanForDomainRecursive($baseAlias);
	}
	
}

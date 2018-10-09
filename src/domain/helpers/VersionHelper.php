<?php

namespace yii2module\vendor\domain\helpers;

use yii\helpers\ArrayHelper;
use yii2module\vendor\domain\entities\CommitEntity;
use yii2module\vendor\domain\entities\RepoEntity;
use yii2module\vendor\domain\enums\VersionTypeEnum;

class VersionHelper {
	
	const UP = 1;
	const MIDDLE = 0;
	const DOWN = -1;
	
	public static function kkkk(RepoEntity $entity) {
		$rrrrrr = VersionHelper::seekRecommendation($entity);
		
		//rsort($rrrrrr);
		
		
		
		//prr($rrrrrr,1,1);
		
		$versionList = \yii\helpers\ArrayHelper::getColumn($entity->tags, 'version');
		$versionVariations = \yii2module\vendor\domain\helpers\VersionHelper::getVersionVariations($versionList);
		
		$result = [];
		foreach($versionVariations as $variationType => $variationVersion) {
			$result[$variationType] = [
				'type' => $variationType,
				'version' => $variationVersion,
				'weight' => $rrrrrr[$variationType],
				'is_recommended' => !empty($rrrrrr[$variationType]),
			];
		}
		
		return $result;
	}
	
	public static function seekRecommendation(RepoEntity $entity) {
		$types = [
			VersionTypeEnum::MINOR => [
				'remove',
				'delete',
			],
			VersionTypeEnum::MAJOR => [
				'make',
				'add',
				'create',
				'refacto',
				'deprecated',
			],
			VersionTypeEnum::PATCH => [
				'fix',
				'clean',
				'clear',
			],
		];
		
		$type = [];
		
		/** @var CommitEntity $commit */
		foreach($entity->commits as $commit) {
			if($commit->tag) {
				return $type;
			}
			foreach($types as $tName => $tValue) {
				foreach($tValue as $exp) {
					$exp = '#' . $exp . '#';
					if(preg_match($exp, $commit->message)) {
						$type[$tName]++;
					}
				}
			}
		}
		return $type;
	}
	
	public static function sort($versionCollection) {
		$versionCollection = ArrayHelper::toArray($versionCollection, [], false);
		$cmp = [self::class, 'sortCollectionCallback'];
		usort($versionCollection, $cmp);
		return $versionCollection;
	}
	
	public static function sortCollectionCallback($a, $b) {
		return self::sortCallback($a->version,  $b->version);
	}
	
	public static function sortCallback($a, $b) {
		if ($a == $b) {
			return VersionHelper::MIDDLE;
		}
		$isGreater = version_compare($a, $b, '<');
		return $isGreater ? VersionHelper::UP : VersionHelper::DOWN;
	}
	
	public static function getVersionVariations($versionList) {
		usort($versionList, [self::class, 'sortCallback']);
		$versionList = array_reverse($versionList);
		$tree = self::list2tree($versionList);
		$result = self::newVersions($tree);
		//$result['current'] = \yii2mod\helpers\ArrayHelper::last($versionList);
		return $result;
	}
	
	private static function newVersions($tree) {
		$items = [];
		$result = [];
		foreach([VersionTypeEnum::MAJOR, VersionTypeEnum::MINOR, VersionTypeEnum::PATCH] as $name) {
			$version = self::getLastFromTree($tree);
			$items[] = $version;
			$result[$name] = self::buildNextVersion($items);
			$tree = $tree[$version];
		}
		return $result;
	}
	
	private static function list2tree($versionList) {
		$tree = [];
		foreach($versionList as $version) {
			\yii2mod\helpers\ArrayHelper::setValue($tree, $version, $version);
		}
		return $tree;
	}
	
	private static function buildNextVersion($items) {
		$items = array_values($items);
		$lastIndex = count($items) - 1;
		$items[$lastIndex]++;
		$needItems = 3 - count($items);
		for($i = 0; $i < $needItems; $i++) {
			$items[] = '0';
		}
		return implode(DOT, $items);
	}
	
	private static function getLastFromTree($tree) {
		$versions = array_keys($tree);
		return \yii2mod\helpers\ArrayHelper::last($versions);
	}
	
}

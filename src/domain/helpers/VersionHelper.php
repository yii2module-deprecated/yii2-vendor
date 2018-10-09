<?php

namespace yii2module\vendor\domain\helpers;

use yii\helpers\ArrayHelper;

class VersionHelper {
	
	const UP = 1;
	const MIDDLE = 0;
	const DOWN = -1;
	
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
		foreach(['major', 'minor', 'patch'] as $name) {
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

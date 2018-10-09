<?php

namespace yii2module\vendor\domain\helpers;

use yii\helpers\ArrayHelper;
use yii2lab\extension\common\helpers\UrlHelper;
use yii2lab\extension\widget\helpers\WidgetHelper;
use yii2module\vendor\domain\entities\CommitEntity;
use yii2module\vendor\domain\entities\RepoEntity;
use yii2module\vendor\domain\enums\VersionTypeEnum;

class VersionHelper {
	
	const UP = 1;
	const MIDDLE = 0;
	const DOWN = -1;
	
	private static $types = [
		VersionTypeEnum::MAJOR => [
			'remove',
			'delete',
		],
		VersionTypeEnum::MINOR => [
			'make',
			'add',
			'create',
			'update',
			'upgrade',
			'use',
			'refacto',
			'deprecated',
			'move',
		],
		VersionTypeEnum::PATCH => [
			'fix',
			'clean',
			'clear',
			'todo',
		],
	];
	
	private static $remotes = [
		'git.wooppay.local' => [
			'uri' => [
				'newTag' => 'http://git.wooppay.local/{package}/tags/new',
			],
		],
		'github.com' => [
			'uri' => [
				'newTag' => 'https://github.com/{package}/releases/new',
			],
		],
	];
	
	public static function getReleaseUrl(RepoEntity $entity) {
		$url = UrlHelper::parse($entity->remote_url);
		$host = $url['host'];
		$newTagUrlTemplate = self::$remotes[$host]['uri']['newTag'];
		$newTagUrl = WidgetHelper::renderTemplate($newTagUrlTemplate, [
			'package' => $entity->package,
		]);
		return $newTagUrl;
	}
	
	public static function getVariations(RepoEntity $entity) {
		$recommendations = VersionHelper::seekRecommendation($entity);
		$versionList = ArrayHelper::getColumn($entity->tags, 'version');
		$versionVariations = VersionHelper::getVersionVariations($versionList);
		
		$result = [];
		foreach($versionVariations as $variationType => $variationVersion) {
			$result[$variationType] = [
				'type' => $variationType,
				'version' => $variationVersion,
				'weight' => $recommendations[$variationType],
				'is_recommended' => !empty($recommendations[$variationType]),
			];
		}
		
		return $result;
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
	
	private static function seekRecommendation(RepoEntity $entity) {
		$type = [];
		
		/** @var CommitEntity $commit */
		foreach($entity->commits as $commit) {
			if($commit->tag) {
				return $type;
			}
			foreach(self::$types as $tName => $tValue) {
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
	
	private static function getVersionVariations($versionList) {
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
		foreach(VersionTypeEnum::values() as $name) {
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

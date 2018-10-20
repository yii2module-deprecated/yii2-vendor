<?php

namespace yii2module\vendor\domain\helpers;

use yii\helpers\ArrayHelper;
use yii2lab\domain\data\EntityCollection;
use yii2lab\extension\common\helpers\Helper;
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
			'deep',
			'major',
		],
		VersionTypeEnum::MINOR => [
			'new',
			'make',
			'add',
			'create',
			'update',
			'upgrade',
			'use',
			'refacto',
			'deprecated',
			'move',
			'rename',
			'minor',
		],
		VersionTypeEnum::PATCH => [
			'fix',
			'clean',
			'clear',
			'todo',
			'note',
			'doc',
			'comment',
			'pretty',
			'copyright',
			'license',
			'guide',
			'test',
			'patch',
		],
	];
	
	private static $remotes = [
		'git.wooppay.local' => [
			'uri' => [
				'newTag' => '{package}/tags/new',
				'viewCommit' => '{package}/commit/{hash}',
			],
		],
		'github.com' => [
			'uri' => [
				'newTag' => '{package}/releases/new',
				'viewCommit' => '{package}/commit/{hash}',
			],
		],
	];
	
	private static function forgeUrl($remote_url, $path = null) {
		$url = UrlHelper::parse($remote_url);
		$result = $url['scheme'] . '://' . $url['host'];
		if($path) {
			$result .= SL . $path;
		}
		return $result;
	}
	
	private static function getProfileConfig($remote_url, $key = null) {
		$url = UrlHelper::parse($remote_url);
		$host = $url['host'];
		$config = self::$remotes[$host];
		if(!empty($key)) {
			return ArrayHelper::getValue($config, $key);
		}
		return $config;
	}
	
	public static function filterNewVersionCommits($collection) {
		/** @var CommitEntity[] $collection */
		$start = false;
		foreach($collection as $index => $commit) {
			if($commit->tag) {
				$start = true;
			}
			if($start) {
				unset($collection[$index]);
			}
		}
		return $collection;
	}
	
	public static function generateUrl(RepoEntity $entity, $paramName, $params) {
		$newTagUrlTemplate = self::getProfileConfig($entity->remote_url, 'uri.' . $paramName);
		$newTagUrl = WidgetHelper::renderTemplate($newTagUrlTemplate, $params);
		return self::forgeUrl($entity->remote_url, $newTagUrl);
	}
	
	public static function getVariations(RepoEntity $entity) {
		$recommendations = VersionHelper::seekRecommendation($entity);
		$versionList = ArrayHelper::getColumn($entity->tags, 'version');
		$versionVariations = VersionHelper::getVersionVariations($versionList);
		
		$result = [];
		$isFoundVariation = false;
		foreach($versionVariations as $variationType => $variationVersion) {
			$is_recommended = !empty($recommendations[$variationType]);
			if($isFoundVariation) {
				$is_recommended = false;
			}
			if($is_recommended) {
				$isFoundVariation = true;
			}
			$result[$variationType] = [
				'type' => $variationType,
				'version' => $variationVersion,
				'is_recommended' => $is_recommended,
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
					$exp = '#' . $exp . '#i';
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
		$tree = Helper::list2tree($versionList);
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

<?php

namespace yii2module\vendor\domain\helpers;

use Yii;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2module\lang\domain\enums\LanguageEnum;

class RepositoryHelper {
	
	public static function gitInstance($package) {
		$dir = self::getPath($package);
		if(!self::isGit($dir)) {
			return null;
		}
		return new GitShell($dir);
	}
	
	public static function getHasInfo($item, $with) {
		if(empty($with) || empty($item['package'])) {
			return $item;
		}
		if(in_array('has_readme', $with)) {
			$item['has_readme'] = RepositoryHelper::hasReadme($item['package']);
		}
		if(in_array('has_changelog', $with)) {
			$item['has_changelog'] = RepositoryHelper::hasChangelog($item['package']);
		}
		if(in_array('has_guide', $with)) {
			$item['has_guide'] = RepositoryHelper::hasGuide($item['package']);
		}
		if(in_array('has_license', $with)) {
			$item['has_license'] = RepositoryHelper::hasLicense($item['package']);
		}
		if(in_array('has_test', $with)) {
			$item['has_test'] = RepositoryHelper::hasTest($item['package']);
		}
		return $item;
	}
	
	public static function namesByOwner($owner) {
		$dir = Yii::getAlias('@vendor/' . $owner);
		$pathList = FileHelper::scanDir($dir);
		return $pathList;
	}
	
	public static function allByOwners($owners) {
		$map = self::namesMapByOwners($owners);
		$list = [];
		foreach($map as $owner => $repositories) {
			foreach($repositories as $repository) {
				$name = self::delYiiPrefix($repository);
				$list[] = [
					'id' => $owner . '-' . $repository,
					'owner' => $owner,
					'name' => $name,
					'package' => $owner . SL . $repository,
				];
			}
		}
		return $list;
	}
	
	private static function delYiiPrefix($repository) {
		return strpos($repository, 'yii2-') == 0 ? substr($repository, 5) : $repository;
	}
	
	private static function hasReadme($package) {
		 	$file = self::getPath($package . SL . 'README.md');
		$isExists = file_exists($file);
		return $isExists;
	}
	
	private static function hasChangelog($package) {
		$file = self::getPath($package . SL . 'CHANGELOG.md');
		$isExists = file_exists($file);
		return $isExists;
	}
	
	private static function hasGuide($package) {
		$dir = self::getPath($package . SL . 'guide' . SL . LanguageEnum::code(Yii::$app->language) . SL . 'README.md');
		$isExists = is_file($dir);
		return $isExists;
	}
	
	private static function hasLicense($package) {
		$file = self::getPath($package . SL . 'LICENSE');
		$isExists = file_exists($file);
		return $isExists;
	}
	
	private static function hasTest($package) {
		$dir = self::getPath($package . SL . 'tests');
		$configFile = self::getPath($package . SL . 'codeception.yml');
		$isExists = is_dir($dir) && file_exists($configFile);
		return $isExists;
	}
	
	private static function namesMapByOwners($owners) {
		$map = [];
		
		foreach($owners as $owner) {
			$names = self::namesByOwner($owner);
			//$names = self::filterList($owner, $names);
			//$names = self::filterIgnoreList($owner, $names);
			$map[$owner] = $names;
		}
		return $map;
	}
	
	/*private static function filterList($owner, $list) {
		$result = [];
		foreach($list as $k => $repo) {
			$dir = Yii::getAlias('@vendor/' . $owner);
			if(is_dir($dir . DS . $repo) && is_file($dir . DS . $repo . DS . 'composer.json')) {
				$result[] = $repo;
			}
		}
		return $result;
	}
	
	private static function filterIgnoreList($owner, $list) {
		$result = [];
		$ignore = ['yii2module/yii2-dashboard'];
		foreach($list as $k => $repo) {
			if(!in_array($owner . SL . $repo, $ignore)) {
				$result[] = $repo;
			}
		}
		return $result;
	}*/
	
	private static function getPath($package) {
		$dir = Yii::getAlias('@vendor/' . $package);
		$dir = FileHelper::normalizePath($dir);
		return $dir;
	}
	
	private static function isGit($dir) {
		return is_dir($dir) && is_dir($dir . DS . '.git');
	}
	
}

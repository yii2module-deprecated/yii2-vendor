<?php

namespace yii2module\vendor\domain\helpers;

use Yii;
use yii2lab\helpers\yii\FileHelper;

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
				$name = strpos($repository,'yii2-') == 0 ? substr($repository, 5) : $repository;
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
	
	private static function hasReadme($package) {
		 	$file = self::getPath($package . SL . 'README.md');
		$isExists = file_exists($file);
		return $isExists;
	}
	
	private static function hasGuide($package) {
		$dir = self::getPath($package . SL . 'guide');
		$isExists = is_dir($dir);
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
			$map[$owner] = self::namesByOwner($owner);
		}
		return $map;
	}
	
	private static function getPath($package) {
		$dir = Yii::getAlias('@vendor/' . $package);
		$dir = FileHelper::normalizePath($dir);
		return $dir;
	}
	
	private static function isGit($dir) {
		return is_dir($dir) && is_dir($dir . DS . '.git');
	}
	
}

<?php

namespace yii2module\vendor\domain\helpers;

use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;
use yii2lab\helpers\ClassHelper;
use yii2lab\helpers\yii\FileHelper;

class PrettyHelper {
	
	public static function scanForDomainRecursive($domainAliasName) {
		$domainAlias = '@' . $domainAliasName;
		$aliases = [];
		try {
			if(self::isDomainDir($domainAlias)) {
				$aliases[] = $domainAliasName;
			} else {
				$versions = self::scanForDomain($domainAlias);
				foreach($versions as $version) {
					$newAliases = self::scanForDomainRecursive($domainAliasName . SL . $version);
					if(!empty($newAliases)) {
						$aliases = ArrayHelper::merge($aliases, $newAliases);
					}
				}
			}
		} catch(InvalidArgumentException $e) {}
		return $aliases;
	}
	
	
	public static function scanDomain($dir, $types) {
		if(!is_dir($dir)) {
			return null;
		}
		$data = FileHelper::scanDir($dir);
		$data = array_flip($data);
		$result = [];
		foreach($data as $driver => $value) {
			$path = $dir . DS . $driver;
			if(is_dir($path)) {
				$result[$driver] = self::scanDomain($path, $types);
			} else {
				$name = self::parseClassName($driver, $types);
				$result[$name] = null;
			}
		}
		return $result;
	}
	
	private static function parseClassName($name, $types) {
		$class = FileHelper::fileRemoveExt($name);
		$isMatch = preg_match('#^([a-zA-Z]+)('.implode('|', $types).')#i', $class, $matches);
		if(!$isMatch) {
			return $name;
		}
		$class = $matches[1];
		$class{0} = strtolower($class{0});
		return $class;
	}
	
	private static function isDomainDir($domainDir) {
		$alias = substr($domainDir, 1);
		$alias = str_replace(SL, BSL, $alias);
		$className = FileHelper::fileRemoveExt($alias);
		if($className != $alias) {
			return false;
		}
		$classNameDomain = $className . BSL . 'Domain';
		if(class_exists($classNameDomain)) {
			$domainInstance = ClassHelper::createObject($classNameDomain);
			$isDomainClass = $domainInstance instanceof Domain;
			if($isDomainClass) {
				return true;
			}
		}
		$isServicesDirExists = is_dir(FileHelper::getAlias($domainDir)  . DS . 'services');
		return $isServicesDirExists;
	}
	
	private static function scanForDomain($alias) {
		$domainDir = FileHelper::getAlias($alias);
		if(!is_dir($domainDir)) {
			return [];
		}
		return FileHelper::scanDir($domainDir);
	}
	
	
}

<?php

namespace yii2module\vendor\domain\helpers;

use yii\base\InvalidArgumentException;
use yii2lab\domain\Domain;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2mod\helpers\ArrayHelper;

class FindHelper {
	
	public static function scanForDomain($sourceAliasNames) {
		if(empty($sourceAliasNames)) {
			return [];
		}
		$sourceAliasNames = ArrayHelper::toArray($sourceAliasNames);
		$aliases = [];
		foreach($sourceAliasNames as $domainAliasName) {
			$aliasesNew = FindHelper::scanForDomainRecursive($domainAliasName);
			$aliases = ArrayHelper::merge($aliases, $aliasesNew);
		}
		return $aliases;
	}
	
	private static function scanForDomainRecursive($domainAliasName) {
		$aliases = [];
		$domainAlias = '@' . $domainAliasName;
		$domains = self::getDomainsFromDir($domainAlias);
		if($domains) {
			foreach($domains as $domain) {
				$domain = FileHelper::fileRemoveExt($domain);
				$domainClass = ClassHelper::normalizeClassName($domainAliasName . BSL . $domain);
				if(self::isDomainClass($domainClass)) {
					$aliases[] = dirname($domainClass);
				}
			}
		}
		return $aliases;
	}
	
	private static function isDomainClass($domainClass) {
		$exclude = [
			'yii2module\\vendor\\domain\\data\\src\\domain\\Domain',
			'yii2woop\\common\\bundle\\summary\\domain\\Domain',
		];
		if(in_array($domainClass, $exclude)) {
			return false;
		}
		$classExists = class_exists($domainClass);
		if(!$classExists) {
			return false;
		}
		if(!is_subclass_of($domainClass, Domain::class)) {
			return false;
		}
		return true;
	}
	
	private static function getDomainsFromDir($domainAlias) {
		try {
			$domainDir = FileHelper::getAlias($domainAlias);
			$files = self::findDomains($domainDir);
		} catch(InvalidArgumentException $e) {
			return [];
		}
		$files = self::normalizeFileNames($files, $domainDir);
		return $files;
	}
	
	private static function findDomains($domainDir) {
		try {
			$files = FileHelper::findFiles($domainDir, [
				'only'=>['Domain.php'],
				'recursive' => true,
			]);
		} catch(InvalidArgumentException $e) {
			$files = [];
		}
		if(empty($files)) {
			return [];
		}
		return $files;
	}
	
	private static function normalizeFileNames($files, $domainDir) {
		foreach($files as &$file) {
			$file = str_replace($domainDir, '', $file);
			$file = trim($file, SL . BSL);
		}
		return $files;
	}
	
}

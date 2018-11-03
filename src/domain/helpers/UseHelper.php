<?php

namespace yii2module\vendor\domain\helpers;

use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2mod\helpers\ArrayHelper;

class UseHelper {

	public static function find($dir) {
		$fileList = self::findPhpFiles($dir);
		$uses = self::findUsesInFiles($fileList);
		sort($uses);
		return $uses;
	}
	
	public static function listToMap($uses, $entity) {
		$res = [];
		foreach($uses as $use) {
			if(self::isHasStr($use, [ClassHelper::normalizeClassName($entity->alias) . '\\', 'tests\\'])) {
				$res['self'][] = $use;
			} elseif(self::isHasStr($use, ['yii\\', 'Yii'])) {
				$res['yii'][] = $use;
			} elseif(self::isHasStr($use, ['common\\', 'frontend\\', 'backend\\', 'console\\', 'api\\', 'domain\\', ])) {
				$res['application'][] = $use;
			} else {
				$res['misc'][] = $use;
			}
		}
		$res['required_packages'] = self::getRequiredPackages($res);
		foreach($res as &$item) {
			$item = array_unique($item);
			$item = array_values($item);
		}
		return $res;
	}
	
	private static function getRequiredPackages($map) {
		$res = [];
		if(!empty($map['yii'])) {
			$res[] = 'yiisoft\yii2';
		}
		if(!empty($map['misc'])) {
			foreach($map['misc'] as $vendor) {
				$arr = explode('\\', $vendor);
				$output = array_slice($arr, 0, 2);
				$res[] = implode('\\', $output);
			}
		}
		return $res;
	}
	
	private static function isHasStr($str, $needles) {
		$needles = ArrayHelper::toArray($needles);
		foreach($needles as $needle) {
			if(strpos($str, $needle) === 0) {
				return true;
			}
		}
		return false;
	}
	
	protected static function findPhpFiles($dir) {
		$options['only'][] = '*.php';
		return FileHelper::findFiles($dir, $options);
	}
	
	protected static function findUsesInFiles($fileList) {
		$result = [];
		$search = '[^\w]+use\s+([a-z0-9_\\\\]+)[^\w]+';
		foreach($fileList as $file) {
			$matches = FileHelper::findInFileByExp($file, $search, 1);
			if($matches) {
				$matchesFlatten = ArrayHelper::flatten($matches);
				$result = ArrayHelper::merge($result, $matchesFlatten);
			}
		}
		/*foreach($result as &$use) {
			preg_match('#(.+)(\s+as\s+)(.+)#', $use, $matches1);
			if(!empty($matches1[1])) {
				$use = $matches1[1];
			}
		}*/
		/*$search = '[^\w\\\\\'"]+\\\\([a-z0-9_\\\\]+)[^\w]+';
		foreach($fileList as $file) {
			$matches = FileHelper::findInFileByExp($file, $search, 1);
			if($matches) {
				$matchesFlatten = ArrayHelper::flatten($matches);
				$result = ArrayHelper::merge($result, $matchesFlatten);
			}
		}*/
		
		foreach($result as $k => &$use) {
			$use = trim($use, ' \\');
			if(empty($use) || strpos($use, '\\') === false) {
				unset($result[$k]);
			}
		}
		$result = array_unique($result);
		$result = array_values($result);
		return $result;
	}
	
}

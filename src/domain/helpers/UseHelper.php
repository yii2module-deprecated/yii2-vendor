<?php

namespace yii2module\vendor\domain\helpers;

use yii2lab\helpers\yii\FileHelper;
use yii2mod\helpers\ArrayHelper;

class UseHelper {

	public static function find($dir) {
		$fileList = self::findPhpFiles($dir);
		$uses = self::findUsesInFiles($fileList);
		sort($uses);
		return $uses;
	}
	
	protected static function findPhpFiles($dir) {
		$options['only'][] = '*.php';
		return FileHelper::findFiles($dir, $options);
	}
	
	protected static function findUsesInFiles($fileList) {
		$result = [];
		$search = '\s+use\s+([^;]+);';
		foreach($fileList as $file) {
			$matches = FileHelper::findInFileByExp($file, $search, 1);
			if($matches) {
				$matchesFlatten = ArrayHelper::flatten($matches);
				$result = ArrayHelper::merge($result, $matchesFlatten);
			}
		}
		$result = array_unique($result);
		$result = array_values($result);
		return $result;
	}
	
}
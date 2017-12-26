<?php

namespace yii2module\vendor\domain\helpers;

use common\enums\rbac\PermissionEnum;
use Yii;
use yii\helpers\Inflector;
use yii2lab\helpers\yii\FileHelper;
use yii2lab\misc\interfaces\CommandInterface;
use yii2mod\helpers\ArrayHelper;
use yii2module\vendor\domain\commands\Base;

class UseHelper {

	public static function run($owner, $name) {
		$dir = self::packageDir($owner, $name);
		$fileList = self::findPhpFiles($dir);
		//prr($fileList,1,1);
		$permissions = self::findPermissionsInFiles($fileList);
		sort($permissions);
		//prr($permissions,1,1);
		//self::createAllPermissions($permissions);
		return $permissions;
	}
	
	protected static function createAllPermissions($permissions) {
		foreach($permissions as $permission) {
			if(!self::hasPermissionInEnum($permission)) {
				$permission = self::constToPermissionName($permission);
				self::createPermission($permission);
			}
		}
	}
	
	protected static function hasPermissionInEnum($permission) {
		return in_array($permission, PermissionEnum::keys());
	}
	
	protected static function createPermission($permission) {
		$permissionInstance = Yii::$app->authManager->createPermission($permission);
		Yii::$app->authManager->add($permissionInstance);
	}
	
	protected static function constToPermissionName($permission) {
		$permission = strtolower($permission);
		$permission = 'o' . Inflector::camelize($permission);
		return $permission;
	}
	
	protected static function findPhpFiles($dir) {
		$options['only'][] = '*.php';
		return FileHelper::findFiles($dir, $options);
	}
	
	protected static function findPermissionsInFiles($fileList) {
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
	
	protected static function packageDir($owner, $name) {
		return ROOT_DIR . DS . self::packageDirMini($owner, $name);
	}
	
	private static function packageDirMini($owner, $name) {
		return VENDOR . DS . $owner . DS . 'yii2-' . $name;
	}
}

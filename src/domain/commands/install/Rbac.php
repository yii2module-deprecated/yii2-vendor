<?php

namespace yii2module\vendor\domain\commands\install;

use Yii;
use yii\helpers\Inflector;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2mod\helpers\ArrayHelper;
use yii2module\vendor\domain\commands\Base;

class Rbac extends Base {

	public function run() {
		$fileList = $this->findPhpFiles();
		$permissions = $this->findPermissionsInFiles($fileList);
		$this->createAllPermissions($permissions);
	}
	
	protected function createAllPermissions($permissions) {
		foreach($permissions as $permission) {
			if(!$this->hasPermissionInEnum($permission)) {
				$permission = $this->constToPermissionName($permission);
				$this->createPermission($permission);
			}
		}
	}
	
	protected function hasPermissionInEnum($permission) {
		return in_array($permission, PermissionEnum::keys());
	}
	
	protected function createPermission($permission) {
		$permissionInstance = Yii::$app->authManager->createPermission($permission);
		Yii::$app->authManager->add($permissionInstance);
	}
	
	protected function constToPermissionName($permission) {
		$permission = strtolower($permission);
		$permission = 'o' . Inflector::camelize($permission);
		return $permission;
	}
	
	protected function findPhpFiles() {
		$options['only'][] = '*.php';
		$dir = $this->packageDir($this->data['owner'], $this->data['name']);
		return FileHelper::findFiles($dir, $options);
	}
	
	protected function findPermissionsInFiles($fileList) {
		$result = [];
		$search = 'PermissionEnum\:\:([A-Z_]+)';
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

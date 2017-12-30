<?php

namespace yii2module\vendor\admin\helpers;

// todo: отрефакторить - сделать нормальный интерфейс и родителя

use common\enums\rbac\PermissionEnum;
use yii2lab\helpers\ModuleHelper;

class Menu {
	
	static function getMenu() {
		return [
			'module' => 'vendor',
			'access' => PermissionEnum::VENDOR_MANAGE,
			'label' => ['vendor/main', 'title'],
			'icon' => 'cube',
			'items' => [
				[
					'label' => ['vendor/info', 'list'],
					'url' => 'vendor/info/list',
					//'icon' => 'circle-o ',
					'active' => ModuleHelper::isActiveUrl(['vendor/info/list', 'vendor/info/view']),
				],
				[
					'label' => ['vendor/info', 'list_changed'],
					'url' => 'vendor/info/list-changed',
					//'icon' => 'circle-o ',
					'active' => ModuleHelper::isActiveUrl('vendor/info/list-changed'),
				],
				[
					'label' => ['vendor/info', 'list_for_release'],
					'url' => 'vendor/info/list-for-release',
					//'icon' => 'circle-o ',
					'active' => ModuleHelper::isActiveUrl('vendor/info/list-for-release'),
				],
			],
		];
	}
	
}

<?php

namespace yii2module\vendor\admin\helpers;

use yii2lab\extension\menu\interfaces\MenuInterface;
use yii2lab\extension\common\helpers\ModuleHelper;
use yii2module\vendor\domain\enums\VendorPermissionEnum;

class Menu implements MenuInterface {
	
	public function toArray() {
		return [
			'module' => 'vendor',
			'access' => VendorPermissionEnum::MANAGE,
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

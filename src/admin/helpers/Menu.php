<?php

namespace yii2module\vendor\admin\helpers;

// todo: отрефакторить - сделать нормальный интерфейс и родителя

use common\enums\rbac\PermissionEnum;
use Yii;

class Menu {
	
	static function getMenu() {
		$url = Yii::$app->request->url;
		return [
			'module' => 'vendor',
			'access' => PermissionEnum::VENDOR_MANAGE,
			'label' => ['vendor/main', 'title'],
			'icon' => 'cube',
			'items' => [
				[
					'label' => ['vendor/local', 'list'],
					'url' => 'vendor/local/list',
					//'icon' => 'circle-o ',
					'active' => $url == '/vendor/local/list',
				],
				[
					'label' => ['vendor/local', 'list_changed'],
					'url' => 'vendor/local/list-changed',
					//'icon' => 'circle-o ',
					'active' => $url == '/vendor/local/list-changed',
				],
			],
		];
	}
	
}

<?php

namespace yii2module\vendor\domain;

use yii2lab\domain\enums\Driver;

class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'info' => Driver::FILE,
				'package' => Driver::FILE,
				'generator' => Driver::FILE,
			],
			'services' => [
				'info',
				'package',
				'generator' => [
					'author' => 'Yamshikov Vitaliy, WOOPPAY LLC',
					'email' => 'theyamshikov@yandex.ru',
					'owners' => [
						'yii2lab',
						'yii2module',
						'yii2woop',
					],
				],
			],
		];
	}
	
}
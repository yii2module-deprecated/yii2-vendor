<?php

namespace yii2module\vendor\domain;

use yii2lab\domain\enums\Driver;

class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'generator' => Driver::FILE,
			],
			'services' => [
				'generator' => [
					'author' => 'Yamshikov Vitaliy, WOOPPAY LLC',
					'email' => 'theyamshikov@yandex.ru',
					'ownerList' => [
						'yii2lab',
						'yii2module',
						'yii2woop',
					],
				],
			],
		];
	}
	
}
<?php

namespace yii2module\vendor\domain;

use yii2lab\domain\enums\Driver;

/**
 * Class Domain
 *
 * @package yii2module\vendor\domain
 *
 * @property \yii2module\vendor\domain\services\InfoService $info
 * @property \yii2module\vendor\domain\services\PackageService $package
 * @property \yii2module\vendor\domain\services\GitService $git
 * @property \yii2module\vendor\domain\services\TestService $test
 * @property \yii2module\vendor\domain\services\GeneratorService $generator
 */
class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'info' => Driver::FILE,
				'package' => Driver::FILE,
				'generator' => Driver::FILE,
				'git' => Driver::FILE,
				'test' => Driver::FILE,
			],
			'services' => [
				'info' => [
					'ignore' => [
						//'yii2module/yii2-test',
					],
				],
				'package' => [
					'aliases' => [
						'@root',
						'@vendor/yii2lab/yii2-application-template',
					],
				],
				'git',
				'test' => [
					'aliases' => [
						//'domain/v1/account',
					],
				],
				'generator' => [
					'author' => 'Yamshikov Vitaliy, WOOPPAY LLC',
					'email' => 'theyamshikov@yandex.ru',
					'owners' => [
						'yii2lab',
						'yii2module',
						'yii2woop',
						'yii2guide',
					],
				],
			],
		];
	}
	
}
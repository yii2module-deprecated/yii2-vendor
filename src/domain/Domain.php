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
					'author' => 'Author name',
					'email' => 'example@example.com',
					'ownerList' => [],
				],
			],
		];
	}
	
}
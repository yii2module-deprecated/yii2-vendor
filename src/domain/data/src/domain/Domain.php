<?php

namespace {owner}\{name}\domain;

/**
 * Class Domain
 * 
 * @package {owner}\{name}\domain
 */
class Domain extends \yii2lab\domain\Domain {

	public function config() {
		return [
			'repositories' => [
				'transaction',
			],
			'services' => [
				'transaction',
			],
		];
	}

}
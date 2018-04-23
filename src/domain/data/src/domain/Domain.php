<?php

namespace {owner}\{name}\domain;

/**
 * Class Domain
 * 
 * @package {owner}\{name}\domain
 *
 * @property-read \{owner}\{name}\domain\interfaces\services\{Entity}Interface ${entity}
 */
class Domain extends \yii2lab\domain\Domain {

	public function config() {
		return [
			'repositories' => [
				'{entity}',
			],
			'services' => [
				'{entity}',
			],
		];
	}

}
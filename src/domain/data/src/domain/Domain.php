<?php

namespace {owner}\{name}\domain;

/**
 * Class Domain
 * 
 * @package {owner}\{name}\domain
 *
 * @property-read \{owner}\{name}\domain\interfaces\services\{Entity}Interface ${entity}
 * @property \{owner}\{name}\domain\interfaces\repositories\RepositoriesInterface $repositories
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
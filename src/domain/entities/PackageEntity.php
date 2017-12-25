<?php

namespace yii2module\vendor\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class PackageEntity
 *
 * @package yii2module\vendor\domain\entities
 *
 * @property string $alias
 * @property array $config
 */
class PackageEntity extends BaseEntity {

	protected $alias = null;
	protected $config;
	
}

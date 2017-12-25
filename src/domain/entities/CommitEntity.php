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
class CommitEntity extends BaseEntity {

	protected $sha;
	protected $author;
	protected $date;
	protected $message;

}

<?php

namespace yii2module\vendor\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class PackageEntity
 *
 * @package yii2module\vendor\domain\entities
 *
 * @property $sha
 * @property $author
 * @property $date
 * @property $message
 * @property $tag
 */
class CommitEntity extends BaseEntity {

	protected $sha;
	protected $author;
	protected $date;
	protected $message;
	protected $tag;
	
	public function fieldType() {
		return [
			'tag' => [
				'type' => TagEntity::class,
			],
		];
	}
}

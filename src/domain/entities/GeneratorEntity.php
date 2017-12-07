<?php

namespace yii2module\vendor\domain\entities;

use yii2lab\domain\BaseEntity;

class GeneratorEntity extends BaseEntity {
	
	protected $id;
	protected $name;
	protected $title;
	protected $content;
	protected $updated_at;
	protected $is_deleted = false;
	protected $created_at;

	public function fieldType() {
		return [
			'id' => 'integer',
			'is_deleted' => 'boolean',
		];
	}

	public function rules() {
		return [
			[['title', 'content', 'name'], 'trim'],
			[['title', 'content', 'name'], 'required'],
		];
	}

}
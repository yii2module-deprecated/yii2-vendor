<?php

namespace yii2module\vendor\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\extension\yii\helpers\FileHelper;

/**
 * Class DomainEntity
 *
 * @package yii2module\vendor\domain\entities
 *
 * @property $path
 * @property $directory_alias
 * @property $directory
 * @property $class_name
 * @property $repositories
 * @property $services
 * @property $interfaces
 * @property $entities
 */
class DomainEntity extends BaseEntity {
	
	protected $path;
	protected $directory_alias;
	protected $directory;
	protected $class_name;
	protected $repositories;
	protected $services;
	protected $interfaces;
	protected $entities;
	
	public function setPath($value) {
		$this->path = str_replace('\\', '/', $value);
	}
	
	public function getDirectory() {
		return FileHelper::getAlias($this->getDirectoryAlias());
	}
	
	public function getDirectoryAlias() {
		return FileHelper::normalizeAlias($this->path);
	}
	
	public function getClassName() {
		return ClassHelper::normalizeClassName($this->getDirectoryAlias() . SL . 'Domain');
	}
	
}

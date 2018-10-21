<?php

namespace yii2module\vendor\console\events;

use yii2lab\domain\BaseEntity;

/**
 * Class DomainEvent
 *
 * @package yii2module\vendor\console\events
 *
 * @property $namespace
 * @property $name
 * @property $isActive
 * @property $drivers
 * @property $attributes
 * @property $types
 *
 */
class DomainEvent extends BaseEntity {
	
	protected $namespace;
	protected $name;
	protected $isActive;
	protected $drivers;
	protected $attributes;
	protected $types;
	
}

<?php

namespace yii2module\vendor\domain\filters\generator;

use yii2lab\extension\scenario\base\BaseScenario;
use yii\helpers\Inflector;

/**
 * Class BaseGenerator
 *
 * @package yii2module\vendor\domain\filters\generator
 *
 * @property string $namespace
 * @property-red string $alias
 * @property string $name
 */
abstract class BaseGenerator extends BaseScenario {
	
	protected $namespace;
	protected $name;
	
	public function getAlias() {
		return '@' .  str_replace(BSL, SL, $this->namespace);
	}
	
	public function getNamespace() {
		return $this->namespace;
	}
	
	public function setNamespace($namespace) {
		$this->namespace = str_replace(SL, BSL, $namespace);
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = Inflector::camelize($name);
		$this->name{0} = strtolower($this->name{0});
	}
}

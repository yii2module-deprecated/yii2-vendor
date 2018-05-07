<?php

namespace yii2module\vendor\domain\filters\generator;

use yii\helpers\Inflector;
use yii2lab\domain\BaseEntity;
use yii2lab\extension\code\entities\ClassEntity;
use yii2lab\extension\code\entities\DocBlockEntity;
use yii2lab\extension\code\entities\DocBlockParameterEntity;
use yii2lab\extension\code\enums\AccessEnum;
use yii2lab\extension\code\helpers\ClassHelper;

/**
 * Class EntityGenerator
 *
 * @package yii2module\vendor\domain\filters\generator
 *
 * @property array $attributes
 */
class EntityGenerator extends BaseGenerator {

	public $attributes;
	
	public function run() {
		$this->generateEntity();
	}
	
	private function generateEntity() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->namespace . '\\entities\\' . Inflector::camelize($this->name) . 'Entity';
		$uses = [
			['name' => BaseEntity::class],
		];
		$classEntity->extends = 'BaseEntity';
		
		$variables = $doc = [];
		foreach($this->attributes as $attribute) {
			$variables[] = [
				'name' => $attribute,
				'access' => AccessEnum::PROTECTED,
			];
			$doc[] = new DocBlockParameterEntity([
				'name' => DocBlockParameterEntity::NAME_PROPERTY,
				'value' => $attribute,
			]);
		}
		
		$classEntity->doc_block = new DocBlockEntity([
			'title' => 'Class' . SPC . $classEntity->name,
			'parameters' => $doc,
		]);
		
		$classEntity->variables = $variables;
		
		ClassHelper::generate($classEntity, $uses);
	}
	
}

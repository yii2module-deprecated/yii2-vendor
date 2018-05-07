<?php

namespace yii2module\vendor\domain\filters\generator;

use yii\helpers\Inflector;
use yii2lab\domain\BaseEntity;
use yii2lab\extension\code\entities\ClassEntity;
use yii2lab\extension\code\entities\DocBlockEntity;
use yii2lab\extension\code\helpers\ClassHelper;

class EntityGenerator extends BaseGenerator {

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
		
		$classEntity->doc_block = new DocBlockEntity([
			'title' => 'Class' . SPC . $classEntity->name,
		]);
		
		ClassHelper::generate($classEntity, $uses);
	}
	
}

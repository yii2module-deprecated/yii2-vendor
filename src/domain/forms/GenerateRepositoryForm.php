<?php

namespace yii2module\vendor\domain\forms;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\base\Model;
use yii2lab\domain\enums\Driver;

class GenerateRepositoryForm extends Model {
	
	public $namespace;
	public $name;
	public $drivers;
	public $isActive;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$collection = \App::$domain->vendor->pretty->all();
		$domainAliases = ArrayHelper::getColumn($collection, 'path');
		return [
			[['namespace', 'name', 'isActive'/*, 'drivers'*/], 'required'],
			['namespace', 'in', 'range' => $domainAliases],
			['isActive', 'boolean'],
			//['drivers', 'in', 'range' => Driver::values()],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'namespace' => Yii::t('vendor/generator_repository', 'namespace'),
			'name' => Yii::t('vendor/generator_repository', 'name'),
			//'drivers' => Yii::t('vendor/generator_repository', 'drivers'),
			'isUseSchema' => Yii::t('vendor/generator_repository', 'isUseSchema'),
			'isActive' => Yii::t('vendor/generator_repository', 'isActive'),
		];
	}
}

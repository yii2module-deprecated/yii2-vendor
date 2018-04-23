<?php

namespace {owner}\{name}\domain\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;

class TransactionRepository extends ActiveArRepository {
	
	protected $schemaClass = true;
	
	public function tableName()
	{
		return '{name}_transaction';
	}
	
}

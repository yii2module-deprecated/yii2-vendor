<?php

namespace {owner}\{name}\domain\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;
use {owner}\{name}\domain\interfaces\repositories\TransactionInterface;

class TransactionRepository extends ActiveArRepository implements TransactionInterface {
	
	protected $schemaClass = true;
	
	public function tableName()
	{
		return '{name}_transaction';
	}
	
}

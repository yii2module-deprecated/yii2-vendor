<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\input\Enter;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;

class GeneratorController extends Controller
{
	
	public function actionIndex()
	{
		$ownerSelect = Select::display('Select owner', [
			'yii2lab',
			'yii2module',
			'yii2woop',
		]);
		$owner = Select::getFirstValue($ownerSelect);
		$name = Enter::display('Enter vendor name');
		Yii::$app->vendor->generator->generate($owner, $name);
		Output::block('Success generated');
	}
	
}

<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;

class ConfigController extends Controller
{
	
	public function actionToDev()
	{
		Yii::$app->vendor->package->versionToDev();
		Output::block('Success converted version to dev-master');
	}
	
	public function actionUpdate()
	{
		Yii::$app->vendor->package->versionUpdate();
		Output::block('Packages version updated');
	}
	
}

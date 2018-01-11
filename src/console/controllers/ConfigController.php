<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;

class ConfigController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}
	
	/**
	 * Set dev-master package version
	 */
	public function actionToDev()
	{
		Output::line('Set dev-master package version...');
		Yii::$app->vendor->package->versionToDev();
		Output::block('Success converted version to dev-master');
	}
	
	/**
	 * Set new package version
	 */
	public function actionUpdate()
	{
		Output::line('Getting package info...');
		Yii::$app->vendor->package->versionUpdate();
		Output::block('Packages version updated');
	}
	
}

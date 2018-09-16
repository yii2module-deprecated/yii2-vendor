<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\console\base\Controller;

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
		\App::$domain->vendor->package->versionToDev();
		Output::block('Success converted version to dev-master');
	}
	
	/**
	 * Set new package version
	 */
	public function actionUpdate()
	{
		Output::line('Getting package info...');
		\App::$domain->vendor->package->versionUpdate();
		Output::block('Packages version updated');
	}
	
}

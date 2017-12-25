<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;
use yii2module\rest_client\helpers\ArrayHelper;

class InfoController extends Controller
{
	
	public function actionAllChanged()
	{
		$collection = Yii::$app->vendor->info->allChanged();
		if(!empty($collection)) {
			$names = ArrayHelper::getColumn($collection, 'alias');
			Output::line();
			Output::arr($names, 'Changed repository list');
		} else {
			Output::block('All repository fixed!', 'Message');
		}
	}
	
	public function actionAllVersion()
	{
		$collection = Yii::$app->vendor->info->allVersion();
		if(!empty($collection)) {
			Output::line();
			$flatCollection = ArrayHelper::map($collection, 'full_name', 'version');
			Output::arr($flatCollection, 'Repository version list');
		} else {
			Output::block('Empty list!', 'Message');
		}
	}
	
	public function actionAllForUpVersion()
	{
		$collection = Yii::$app->vendor->info->allForUpVersion();
		//prr($collection,1,1);
		if(!empty($collection)) {
			Output::line();
			$flatCollection = ArrayHelper::map($collection, 'package', 'version');
			Output::arr($flatCollection, 'Repository list for release');
		} else {
			Output::block('Empty list!', 'Message');
		}
	}
	
}

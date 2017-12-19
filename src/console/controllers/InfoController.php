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
	
}

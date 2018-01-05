<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii\helpers\Inflector;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;
use yii2module\rest_client\helpers\ArrayHelper;

class GitController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}
	
	public function actionPull()
	{
		$collection = Yii::$app->vendor->info->all();
		Output::pipe('Git pull packages');
		foreach($collection as $entity) {
			try {
				$result = Yii::$app->vendor->git->pull($entity);
				$outputLine = $entity->package . SPC . DOT . DOT . DOT . SPC;
				if($result) {
					$outputLine .= PHP_EOL . $result . PHP_EOL;
				} else {
					$outputLine .= 'Already up-to-date';
				}
			} catch(\yii2lab\misc\exceptions\ShellException $e) {
				Yii::$app->end();
			}
			Output::line($outputLine);
		}
		Output::pipe();
		Output::line();
	}
	
}

<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;
use yii2lab\misc\exceptions\ShellException;

class GitController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}
	
	/**
	 * Git pull for all packages
	 */
	public function actionPull()
	{
		$collection = Yii::$app->vendor->info->all();
		Output::pipe('Git pull packages');
		foreach($collection as $entity) {
			Output::line($entity->package);
			try {
				$result = Yii::$app->vendor->git->pull($entity);
				if($result) {
					Output::line();
					Output::line();
					Output::line($result);
					Output::line();
					Output::line();
				}
			} catch(ShellException $e) {
				Yii::$app->end();
			}
		}
		Output::pipe();
		Output::line();
	}
	
}

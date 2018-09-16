<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\shell\exceptions\ShellException;

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
		$collection = \App::$domain->vendor->info->all();
		Output::pipe('Git pull packages');
		foreach($collection as $entity) {
			Output::line($entity->package);
			try {
				$result = \App::$domain->vendor->git->pull($entity);
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
	
	/**
	 * Git push for all packages
	 */
	public function actionPush()
	{
		$collection = \App::$domain->vendor->info->all();
		Output::pipe('Git push packages');
		foreach($collection as $entity) {
			Output::line($entity->package);
			try {
				Output::line();
				\App::$domain->vendor->git->push($entity);
				Output::line();
			} catch(ShellException $e) {
				Yii::$app->end();
			}
		}
		Output::pipe();
		Output::line();
	}
	
}

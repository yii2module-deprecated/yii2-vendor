<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;
use yii2lab\misc\exceptions\ShellException;

class TestController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}
	
	/**
	 * Git pull for all packages
	 */
	public function actionRun()
	{
		$collection = Yii::$app->vendor->info->allWithHasTest();
		$allCount = 0;
		$failPackages = [];
		Output::pipe('Test packages');
		foreach($collection as $entity) {
			$allCount++;
			$output = $entity->package;
			try {
				$result = Yii::$app->vendor->test->run($entity);
				if(!empty($result['result'])) {
					$output .= SPC . 'ok. tests: ' . $result['testCount'] . '. assertions: ' . $result['assertionCount'];
				} else {
					$failPackages[] = $entity->package;
					$output .= PHP_EOL . PHP_EOL . 'FAIL ' . $result['text'] . PHP_EOL . PHP_EOL;
				}
			} catch(ShellException $e) {
				$failPackages[] = $entity->package;
				$output .= SPC . 'FAIL';
			}
			Output::line($output);
		}
		Output::line();
		Output::pipe();
		Output::line();
		
		Output::line('All: ' . $allCount);
		Output::line('OK: ' . ($allCount - count($failPackages)));
		Output::line('Fail: ' . count($failPackages));
		
		if(count($failPackages)) {
			Output::line();
			Output::arr($failPackages, 'List of packages with errors');
		} else {
			Output::line();
			Output::pipe('All tests are OK!');
		}
		
		Output::line();
	}
	
}

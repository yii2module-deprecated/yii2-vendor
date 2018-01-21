<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;

class TestController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}
	
	/**
	 * Test all packages
	 */
	public function actionRun()
	{
		$collection = Yii::$app->vendor->info->allWithHasTest();
		$failPackages = [];
		$allTestCount = $allAssertCount = 0;
		Output::pipe('Test packages');
		foreach($collection as $entity) {
			$output = $entity->package;
			$result = Yii::$app->vendor->test->run($entity);
			$dots = Output::getDots($entity->package, 40);
			if(!empty($result['result'])) {
				$output .= SPC . $dots . SPC . 'OK. tests: ' . $result['testCount'] . '. assertions: ' . $result['assertionCount'];
				$allTestCount = $allTestCount + $result['testCount'];
				$allAssertCount = $allAssertCount + $result['assertionCount'];
			} else {
				$failPackages[] = $entity->package;
				$output .= SPC . $dots . SPC . 'FAIL';
			}
			Output::line($output);
		}
		
		Output::pipe();
		Output::line();
		
		$allCount = count($collection);
		$failCount = count($failPackages);
		$okCount = $allCount - $failCount;
		
		Output::line('All packages' . SPC . Output::getDots('All packages', 18) . SPC . $allCount);
		Output::line('OK packages' . SPC . Output::getDots('OK packages', 18) . SPC . $okCount);
		Output::line('Fail packages' . SPC . Output::getDots('Fail packages', 18) . SPC . $failCount);
		Output::line('Total test' . SPC . Output::getDots('Total test', 18) . SPC . $allTestCount);
		Output::line('Total assert' . SPC . Output::getDots('Total assert', 18) . SPC . $allAssertCount);
		
		if($failCount) {
			Output::line();
			Output::arr($failPackages, 'List of packages with errors');
		} else {
			Output::line();
			Output::pipe('All tests are OK!');
		}
		
		Output::line();
	}
	
}

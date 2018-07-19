<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii\console\ExitCode;
use yii2lab\console\helpers\Output;
use yii2lab\console\base\Controller;

class TestController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}
	
	/**
	 * Test packages and project
	 */
	public function actionAll()
	{
		$collection = Yii::$domain->vendor->test->directoriesWithHasTestAll();
		Output::pipe('Test all (count: ' . count($collection) . ')');
		$this->runCollection($collection);
		Output::line();
	}
	
	/**
	 * Test packages
	 */
	public function actionPackage()
	{
		$collection = Yii::$domain->vendor->test->directoriesWithHasForPackage();
		Output::pipe('Test packages (count: ' . count($collection) . ')');
		$this->runCollection($collection);
		Output::line();
	}
	
	/**
	 * Test project
	 */
	public function actionProject()
	{
		$collection = Yii::$domain->vendor->test->directoriesWithHasTestForProject();
		Output::pipe('Test project (count: ' . count($collection) . ')');
		$this->runCollection($collection);
		Output::line();
	}
	
	private function runCollection($collection) {
		if(empty($collection)) {
			Output::line();
			Output::pipe('Tests not found!');
			return;
		}
		$failPackages = [];
		$allTestCount = $allAssertCount = 0;
		foreach($collection as $entity) {
			$dots = Output::getDots($entity['name'], 40);
			$packageName = $entity['name'];
			$packageName .= SPC . $dots;
			Output::line($packageName, null);
			$result = Yii::$domain->vendor->test->run($entity['directory']);
			$resultData = '';
			if(!empty($result['result'])) {
				$resultData .= SPC . 'OK. tests: ' . $result['testCount'] . '. assertions: ' . $result['assertionCount'];
				$allTestCount = $allTestCount + $result['testCount'];
				$allAssertCount = $allAssertCount + $result['assertionCount'];
			} else {
				$failPackages[] = $entity['name'];
				$resultData .= SPC . 'FAIL';
			}
			Output::line($resultData);
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
			return ExitCode::UNSPECIFIED_ERROR;
		} else {
			Output::line();
			Output::pipe('All tests are OK!');
			return ExitCode::OK;
		}
	}
}

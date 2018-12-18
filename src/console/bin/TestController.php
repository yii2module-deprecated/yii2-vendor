<?php

namespace yii2module\vendor\console\bin;

use Yii;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii2lab\extension\console\helpers\input\Question;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\console\base\Controller;
use yii2lab\domain\data\EntityCollection;
use yii2module\vendor\domain\entities\TestEntity;

class TestController extends \yii\base\Component
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
		$collection = \App::$domain->vendor->test->directoriesWithHasTestAll();
		Output::pipe('Test all (count: ' . count($collection) . ')');
		$this->runCollection($collection);
		Output::line();
	}
	
	/**
	 * Test packages
	 */
	public function actionPackage()
	{
		$collection = \App::$domain->vendor->test->directoriesWithHasForPackage();
		Output::pipe('Test packages (count: ' . count($collection) . ')');
		$this->runCollection($collection);
		Output::line();
	}
	
	/**
	 * Test project
	 */
	public function actionProject()
	{
		$collection = \App::$domain->vendor->test->directoriesWithHasTestForProject();
		Output::pipe('Test project (count: ' . count($collection) . ')');
		$this->runCollection($collection);
		Output::line();
	}
	
	private function runCollection($collection) {
		if(empty($collection)) {
			Output::line();
			Output::pipe('Tests not found!');
			return ExitCode::OK;
		}
		$failPackages = [];
		$allTestCount = $allAssertCount = 0;
		$resultCollection = new EntityCollection(TestEntity::class);
		foreach($collection as $entity) {
			$dots = Output::getDots($entity['name'], 40);
			$packageName = $entity['name'];
			$packageName .= SPC . $dots;
			Output::line($packageName, null);
			$testEntity = \App::$domain->vendor->test->run($entity['directory']);
            $resultCollection[] = $testEntity;
			$resultData = '';
            $allTestCount = $allTestCount + $testEntity->tests;
            $allAssertCount = $allAssertCount + $testEntity->assertions;
            
            if(empty($testEntity->error)) {
				if(empty($testEntity->tests)) {
					Output::line(' SKIP.', null, Console::FG_YELLOW);
				} else {
					Output::line(' OK.', null, Console::FG_GREEN);
				}
				$resultData .= SPC . 'tests: ' . $testEntity->tests . '. assertions: ' . $testEntity->assertions;
			} else {
				$failPackages[] = $entity['name'];
				Output::line(' FAIL.', null, Console::FG_RED);
				$resultData .= SPC . 'errors: ' . $testEntity->error . '. assertions: ' . $testEntity->assertions;
			}
			Output::line($resultData, 'after');
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
			Output::arr($failPackages, Output::wrap('List of packages with errors', Console::FG_RED));
            $this->showErrorDetails($resultCollection);
			return ExitCode::UNSPECIFIED_ERROR;
		} else {
			Output::line();
			Output::pipe('All tests are OK!', '-', Console::FG_GREEN);
			return ExitCode::OK;
		}
	}

	private function showErrorDetails($resultCollection) {
        if(empty($resultCollection)) {
            return;
        }
        $isShowErrors = Question::confirm2('Is show error details?', false);
        if(!$isShowErrors) {
            return;
        }
        foreach($resultCollection as $testItem) {
            if($testItem->error) {
                Output::line($testItem->text, $testItem->directory);
            }
        }
    }

}

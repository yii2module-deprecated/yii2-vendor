<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii\helpers\Inflector;
use yii2lab\console\helpers\input\Select;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;
use yii\helpers\ArrayHelper;

class InfoController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}
	
	/**
	 * Changed package list
	 */
	public function actionAllChanged()
	{
		Output::line('Getting package info...');
		$collection = Yii::$domain->vendor->info->allChanged();
		if(!empty($collection)) {
			$names = ArrayHelper::getColumn($collection, 'alias');
			Output::line();
			Output::arr($names, 'Changed repository list');
		} else {
			Output::block('All repository fixed!', 'Message');
		}
	}
	
	/**
	 * Package list with version
	 */
	public function actionAllVersion()
	{
		Output::line('Getting package info...');
		$collection = Yii::$domain->vendor->info->allVersion();
		if(!empty($collection)) {
			$flatCollection = ArrayHelper::map($collection, 'package', 'version');
			Output::line();
			Output::arr($flatCollection, 'Repository version list');
		} else {
			Output::block('Empty list!', 'Message');
		}
	}
	
	/**
	 * Package list for release
	 */
	public function actionAllForRelease()
	{
		Output::line('Getting package info...');
		$collection = Yii::$domain->vendor->info->allForRelease();
		if(!empty($collection)) {
			$flatCollection = ArrayHelper::map($collection, 'package', 'version');
			Output::line();
			Output::arr($flatCollection, 'Repository list for release');
		} else {
			Output::block('Empty list!', 'Message');
		}
	}
	
	/**
	 * Get package dependencies
	 */
	public function actionPackageUses()
	{
		list($owner, $name) = $this->inputPackage();
		Output::line('Find uses in package...');
		$uses = Yii::$domain->vendor->info->usesById($owner . '-yii2-' . $name);
		Output::line();
		foreach($uses as $placeName => $list) {
			Output::items($list, Inflector::titleize($placeName));
			Output::line();
		}
	}
	
	/*
	public function actionGitPull()
	{
		$collection = Yii::$domain->vendor->info->all();
		Output::line();
		Output::pipe('Git pull packages');
		foreach($collection as $entity) {
			$result = Yii::$domain->vendor->info->pull($entity);
			$outputLine = $entity->package . SPC . DOT . DOT . DOT . SPC;
			if($result) {
				$outputLine .= PHP_EOL . $result . PHP_EOL;
			} else {
				$outputLine .= 'Already up-to-date';
			}
			Output::line($outputLine);
		}
		Output::pipe();
		Output::line();
	}
	*/
	
	private function inputPackage() {
		$ownerSelect = Select::display('Select owner', Yii::$domain->vendor->generator->owners);
		$owner = Select::getFirstValue($ownerSelect);
		$names = Yii::$domain->vendor->info->shortNamesByOwner($owner);
		$nameSelect = Select::display('Select package', $names);
		$name = Select::getFirstValue($nameSelect);
		Output::line();
		return [$owner, $name];
	}
	
}
